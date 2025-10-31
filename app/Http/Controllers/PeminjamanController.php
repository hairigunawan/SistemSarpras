<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Ruangan;
use App\Models\Proyektor;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // ... (Fungsi index, show, dan lainnya tetap sama) ...

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = Peminjaman::with(['user', 'ruangan', 'proyektor']);

        if ($status !== 'all') {
            $query->where('status_peminjaman', $status);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('ruangan', function ($qr) use ($request) {
                    $qr->where('nama_ruangan', 'like', "%{$request->search}%");
                })->orWhereHas('proyektor', function ($qp) use ($request) {
                    $qp->where('nama_proyektor', 'like', "%{$request->search}%");
                });
            });
        }

        $role = Auth::user()->userRole->nama_role ?? '';

        $peminjaman = $query->latest()->get();
        return view('admin.peminjaman.index', compact('peminjaman', 'role', 'status'));
    }

    public function lihat_peminjaman($id)
    {
        $mainPeminjaman = Peminjaman::with(['ruangan', 'proyektor', 'user'])->findOrFail($id);

        $conflictingPeminjaman = Peminjaman::where(function ($query) use ($mainPeminjaman) {
            if ($mainPeminjaman->id_ruangan) {
                $query->where('id_ruangan', $mainPeminjaman->id_ruangan);
            } else {
                $query->where('id_proyektor', $mainPeminjaman->id_proyektor);
            }
        })
            ->where('id_peminjaman', '!=', $id)
            ->where('status_peminjaman', 'Menunggu')
            ->where(function ($query) use ($mainPeminjaman) {
                $query->where('tanggal_pinjam', '<=', $mainPeminjaman->tanggal_kembali);
                $query->where('tanggal_kembali', '>=', $mainPeminjaman->tanggal_pinjam);
                $query->where('jam_mulai', '<', $mainPeminjaman->jam_selesai);
                $query->where('jam_selesai', '>', $mainPeminjaman->jam_mulai);
            })
            ->with('user')
            ->get();
        $candidates = collect([$mainPeminjaman])->merge($conflictingPeminjaman);

        return view('admin.peminjaman.lihat_peminjaman', compact('mainPeminjaman', 'candidates'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_ruangan' => 'nullable|exists:ruangans,id_ruangan',
            'id_proyektor' => 'nullable|exists:proyektors,id_proyektor',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'nomor_whatsapp' => 'required|string|max:15',
            'jumlah_peserta' => 'required|integer|min:1',
            'jenis_kegiatan' => 'required|string|max:500',
        ]);

        if (empty($validatedData['id_ruangan']) && empty($validatedData['id_proyektor'])) {
            return back()->withErrors(['id_sarpras' => 'Pilih salah satu Ruangan atau Proyektor.'])->withInput();
        }
        if (!empty($validatedData['id_ruangan']) && !empty($validatedData['id_proyektor'])) {
            return back()->withErrors(['id_sarpras' => 'Hanya boleh memilih satu Ruangan atau Proyektor.'])->withInput();
        }

        $isRuangan = !empty($validatedData['id_ruangan']);

        $isBentrok = Peminjaman::where(function ($query) use ($validatedData, $isRuangan) {
            if ($isRuangan) {
                $query->where('id_ruangan', $validatedData['id_ruangan']);
            } else {
                $query->where('id_proyektor', $validatedData['id_proyektor']);
            }
        })
            ->whereIn('status_peminjaman', ['Disetujui', 'Menunggu'])
            ->where(function ($query) use ($validatedData) {
                $pinjam_mulai = "{$validatedData['tanggal_pinjam']} {$validatedData['jam_mulai']}";
                $pinjam_selesai = "{$validatedData['tanggal_kembali']} {$validatedData['jam_selesai']}";

                $query->where(function ($q) use ($pinjam_mulai) {
                    $q->whereRaw("CONCAT(tanggal_kembali, ' ', jam_selesai) > ?", [$pinjam_mulai]);
                })->where(function ($q) use ($pinjam_selesai) {
                    $q->whereRaw("CONCAT(tanggal_pinjam, ' ', jam_mulai) < ?", [$pinjam_selesai]);
                });
            })
            ->exists();

        if ($isBentrok) {
            return back()->withErrors([
                'tanggal_pinjam' => 'Jadwal yang Anda pilih bentrok dengan peminjaman lain. Silakan pilih tanggal atau jam yang berbeda.'
            ])->withInput();
        }

        Peminjaman::create([
            'id_akun' => Auth::id(),
            'id_ruangan' => $validatedData['id_ruangan'] ?? null,
            'id_proyektor' => $validatedData['id_proyektor'] ?? null,
            'tanggal_pinjam' => $validatedData['tanggal_pinjam'],
            'tanggal_kembali' => $validatedData['tanggal_kembali'],
            'jam_mulai' => $validatedData['jam_mulai'],
            'jam_selesai' => $validatedData['jam_selesai'],
            'jumlah_peserta' => $validatedData['jumlah_peserta'],
            'jenis_kegiatan' => $validatedData['jenis_kegiatan'],
            'nama_peminjam' => Auth::user()->nama,
            'email_peminjam' => Auth::user()->email,
            'nomor_whatsapp' => $validatedData['nomor_whatsapp'],
            'status_peminjaman' => 'Menunggu',
        ]);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dikirim. Silakan tunggu konfirmasi dari admin.');
    }

    public function approve($id)
    {
        $approvedPeminjaman = Peminjaman::findOrFail($id);

        if ($approvedPeminjaman->status_peminjaman !== 'Menunggu') {
            return redirect()->route('admin.peminjaman.lihat_peminjaman', $id)
                ->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $sarprasId = $approvedPeminjaman->id_ruangan ?? $approvedPeminjaman->id_proyektor;
        $isRuangan = $approvedPeminjaman->id_ruangan !== null;

        $existingApproved = Peminjaman::where(function ($query) use ($sarprasId, $isRuangan) {
            if ($isRuangan) {
                $query->where('id_ruangan', $sarprasId);
            } else {
                $query->where('id_proyektor', $sarprasId);
            }
        })
            ->where('status_peminjaman', 'Disetujui')
            ->where(function ($query) use ($approvedPeminjaman) {
                $query->where(function ($q) use ($approvedPeminjaman) {
                    $q->where('tanggal_pinjam', '<=', $approvedPeminjaman->tanggal_kembali)
                        ->where('tanggal_kembali', '>=', $approvedPeminjaman->tanggal_pinjam);
                });
            })
            ->where(function ($timeQuery) use ($approvedPeminjaman) {
                $timeQuery->where('jam_mulai', '<', $approvedPeminjaman->jam_selesai)
                    ->where('jam_selesai', '>', $approvedPeminjaman->jam_mulai);
            })
            ->exists();

        if ($existingApproved) {
            return redirect()->route('admin.peminjaman.lihat_peminjaman', $id)
                ->with('error', 'Sudah ada peminjaman Disetujui untuk sarpras ini pada waktu yang bentrok.');
        }

        $conflictingPeminjaman = Peminjaman::where('id_peminjaman', '!=', $id)
            ->whereIn('status_peminjaman', ['Menunggu', 'Disetujui'])
            ->where(function ($query) use ($approvedPeminjaman) {
                $query->where('tanggal_pinjam', '<=', $approvedPeminjaman->tanggal_kembali)
                    ->where('tanggal_kembali', '>=', $approvedPeminjaman->tanggal_pinjam)
                    ->where('jam_mulai', '<', $approvedPeminjaman->jam_selesai)
                    ->where('jam_selesai', '>', $approvedPeminjaman->jam_mulai);
            })
            ->get();

        foreach ($conflictingPeminjaman as $conflict) {
            $conflict->update([
                'status_peminjaman' => 'Ditolak',
                'alasan_penolakan' => 'Jadwal bentrok dengan peminjaman lain yang telah disetujui.',
            ]);
        }

        Peminjaman::where('id_akun', $approvedPeminjaman->id_akun)
            ->where(function ($query) use ($sarprasId, $isRuangan) {
                if ($isRuangan) {
                    $query->where('id_ruangan', $sarprasId);
                } else {
                    $query->where('id_proyektor', $sarprasId);
                }
            })
            ->where('id_peminjaman', '!=', $id)
            ->where('status_peminjaman', 'Menunggu')
            ->update([
                'status_peminjaman' => 'Ditolak',
                'alasan_penolakan' => 'Pengajuan lain oleh peminjam yang sama telah disetujui.',
            ]);

        $approvedPeminjaman->update(['status_peminjaman' => 'Disetujui']);

        $idStatusDipinjam = Status::where('nama_status', 'Dipinjam')->first()->id_status;
        if ($isRuangan) {
            Ruangan::where('id_ruangan', $sarprasId)->update(['id_status' => $idStatusDipinjam]);
        } else {
            Proyektor::where('id_proyektor', $sarprasId)->update(['id_status' => $idStatusDipinjam]);
        }

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil disetujui. Pengajuan lain yang bentrok atau duplikat telah otomatis ditolak.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status_peminjaman = 'Ditolak';
        $peminjaman->alasan_penolakan = $request->alasan_penolakan;
        $peminjaman->save();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function complete($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status_peminjaman' => 'Selesai']);

        $sarprasId = $peminjaman->id_ruangan ?? $peminjaman->id_proyektor;
        $isRuangan = $peminjaman->id_ruangan !== null;
        $idStatusTersedia = Status::where('nama_status', 'Tersedia')->first()->id_status;

        if ($isRuangan) {
            Ruangan::where('id_ruangan', $sarprasId)->update(['id_status' => $idStatusTersedia]);
        } else {
            Proyektor::where('id_proyektor', $sarprasId)->update(['id_status' => $idStatusTersedia]);
        }

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil diselesaikan.');
    }

    public function riwayat()
    {
        $userId = Auth::id();
        $peminjaman = Peminjaman::where('id_akun', $userId)->with(['ruangan', 'proyektor'])->latest()->get();
        return view('public.peminjaman.riwayat', compact('peminjaman'));
    }

    public function create()
    {
        $ruanganTersedia = Ruangan::where('id_status', Status::where('nama_status', 'Tersedia')->first()->id_status)->get();
        $proyektorTersedia = Proyektor::where('id_status', Status::where('nama_status', 'Tersedia')->first()->id_status)->get();

        $selectedSarprasType = request('sarpras_type');
        $selectedSarprasId = request('sarpras_id');

        return view('public.peminjaman.create', compact('ruanganTersedia', 'proyektorTersedia', 'selectedSarprasType', 'selectedSarprasId'));
    }


    public function approvedDates($type, $idSarpras)
    {
        $query = Peminjaman::with('user');

        if ($type === 'ruangan') {
            $query->where('id_ruangan', $idSarpras);
        } elseif ($type === 'proyektor') {
            $query->where('id_proyektor', $idSarpras);
        } else {
            return response()->json(['error' => 'Invalid type specified.'], 400);
        }

        $approved = $query->where('status_peminjaman', 'Disetujui')
            ->get(['id_peminjaman', 'id_akun', 'nama_peminjam', 'tanggal_pinjam', 'tanggal_kembali', 'jam_mulai', 'jam_selesai', 'jenis_kegiatan', 'jumlah_peserta'])
            ->map(fn ($p) => [
                'id_peminjaman' => $p->id_peminjaman,
                'id_akun' => $p->id_akun,
                'peminjam_nama' => $p->nama_peminjam ?? optional($p->user)->name,
                'tanggal_pinjam' => $p->tanggal_pinjam,
                'tanggal_kembali' => $p->tanggal_kembali,
                'jam_mulai' => Carbon::parse($p->jam_mulai)->format('H:i'),
                'jam_selesai' => Carbon::parse($p->jam_selesai)->format('H:i'),
                'jenis_kegiatan' => $p->jenis_kegiatan,
                'jumlah_peserta' => $p->jumlah_peserta,
            ]);

        $grouped = [];
        foreach ($approved as $item) {
            $key = $item['tanggal_pinjam'];
            if (!isset($grouped[$key])) $grouped[$key] = [];
            $grouped[$key][] = $item;
        }

        return response()->json(['approvedDetails' => $grouped]);
    }
}
