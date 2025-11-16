<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Ruangan;
use App\Models\Proyektor;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Helpers\PeminjamanHelper;

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

        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('ruangan', function ($qr) use ($search) {
                    $qr->where('nama_ruangan', 'like', "%{$search}%");
                })->orWhereHas('proyektor', function ($qp) use ($search) {
                    $qp->where('nama_proyektor', 'like', "%{$search}%");
                })->orWhereHas('user', function ($qu) use ($search) {
                    $qu->where('nama', 'like', "%{$search}%");
                })->orWhere('nama_peminjam', 'like', "%{$search}%");
            });
        }

        $role = optional(Auth::user()->userRole)->nama_role ?? '';

        $peminjaman = $query->latest()->get();
        return view('admin.peminjaman.index', compact('peminjaman', 'role', 'status'));
    }

    public function lihat_peminjaman($id)
    {
        $mainPeminjaman = Peminjaman::with(['ruangan', 'proyektor', 'user', 'lokasi'])->findOrFail($id);

        // Ambil semua peminjaman yang konflik (termasuk yang sudah disetujui)
        $conflictingPeminjaman = Peminjaman::where(function ($query) use ($mainPeminjaman) {
            if ($mainPeminjaman->id_ruangan) {
                $query->where('id_ruangan', $mainPeminjaman->id_ruangan);
            } elseif ($mainPeminjaman->id_proyektor) {
                $query->where('id_proyektor', $mainPeminjaman->id_proyektor);
            }
        })
            ->where('id_peminjaman', '!=', $id)
            ->where(function ($query) use ($mainPeminjaman) {
                $query->where('tanggal_pinjam', '<=', $mainPeminjaman->tanggal_kembali);
                $query->where('tanggal_kembali', '>=', $mainPeminjaman->tanggal_pinjam);
                $query->where('jam_mulai', '<', $mainPeminjaman->jam_selesai);
                $query->where('jam_selesai', '>', $mainPeminjaman->jam_mulai);
            })
            ->with(['ruangan', 'proyektor', 'user', 'lokasi'])
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
            return back()->withErrors(['id_sarpras' => 'Pilih minimal satu Ruangan atau Proyektor.'])->withInput();
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
            'nama_peminjam' => Auth::user()->name,
            'email_peminjam' => Auth::user()->email,
            'nomor_whatsapp' => $validatedData['nomor_whatsapp'],
            'status_peminjaman' => 'Menunggu',
        ]);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dikirim. Silakan tunggu konfirmasi dari admin.');
    }



    public function riwayat()
    {
        $userId = Auth::id();
        $peminjaman = Peminjaman::where('id_akun', $userId)->with(['ruangan', 'proyektor'])->latest()->get();
        return view('admin.peminjaman.riwayat', compact('peminjaman'));
    }

    public function approvedDates($type, $idSarpras)
    {
        $query = Peminjaman::with('user');

        if ($type === 'ruangan') {
            $query->where('id_ruangan', $idSarpras);
        } elseif ($type === 'proyektor') {
            $query->where('id_proyektor', $idSarpras);
        } else if ($type !== 'all' || $idSarpras !== 'all') { // Hanya kembalikan error jika bukan 'all/all' dan bukan type yang valid
            return response()->json(['error' => 'Invalid type or ID specified.'], 400);
        }

        $approved = $query->where('status_peminjaman', 'Disetujui')
            ->get(['id_peminjaman', 'id_akun', 'nama_peminjam', 'tanggal_pinjam', 'tanggal_kembali', 'jam_mulai', 'jam_selesai', 'jenis_kegiatan', 'jumlah_peserta', 'id_ruangan', 'id_proyektor']) // Pastikan id_ruangan dan id_proyektor selalu diambil
            ->map(fn($p) => [
                'id_peminjaman' => $p->id_peminjaman,
                'id_akun' => $p->id_akun,
                'peminjam_nama' => $p->nama_peminjam ?? optional($p->user)->name,
                'tanggal_pinjam' => $p->tanggal_pinjam,
                'tanggal_kembali' => $p->tanggal_kembali,
                'jam_mulai' => Carbon::parse($p->jam_mulai)->format('H:i'),
                'jam_selesai' => Carbon::parse($p->jam_selesai)->format('H:i'),
                'jenis_kegiatan' => $p->jenis_kegiatan,
                'jumlah_peserta' => $p->jumlah_peserta,
                'id_sarpras' => $p->id_ruangan ?? $p->id_proyektor,
                'sarpras_type' => $p->id_ruangan ? 'ruangan' : ($p->id_proyektor ? 'proyektor' : null), // Menambahkan sarpras_type
            ]);

        $grouped = [];
        foreach ($approved as $item) {
            $key = $item['tanggal_pinjam'];
            if (!isset($grouped[$key])) $grouped[$key] = [];
            $grouped[$key][] = $item;
        }

        return response()->json(['approvedDetails' => $grouped]);
    }

    public function approve(Request $request, $id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Cek apakah status saat ini masih Menunggu
            if ($peminjaman->status_peminjaman !== 'Menunggu') {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Peminjaman tidak dapat disetujui karena status saat ini bukan Menunggu.',
                        'redirect' => route('admin.peminjaman.index')
                    ], 422);
                }
                return redirect()->route('admin.peminjaman.index')->with('error', 'Peminjaman tidak dapat disetujui karena status saat ini bukan Menunggu.');
            }

            // Update status dengan explicitly mass assignment
            $peminjaman->status_peminjaman = 'Disetujui';
            $peminjaman->save();

            // Update status sumber daya (ruangan dan/atau proyektor) menjadi Dipakai
            if ($peminjaman->id_ruangan) {
                PeminjamanHelper::updateResourceStatus($peminjaman, 'Disetujui');
            }
            if ($peminjaman->id_proyektor) {
                PeminjamanHelper::updateResourceStatus($peminjaman, 'Disetujui');
            }

            // Otomatis tolak peminjaman yang konflik
            PeminjamanHelper::autoRejectConflictingPeminjaman($peminjaman);

            // Log untuk debugging
            Log::info('Peminjaman approved', [
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'status_sebelumnya' => 'Menunggu',
                'status_setelahnya' => 'Disetujui',
                'id_ruangan' => $peminjaman->id_ruangan,
                'id_proyektor' => $peminjaman->id_proyektor,
                'user' => Auth::user()->name ?? 'Unknown'
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peminjaman berhasil disetujui.',
                    'redirect' => route('admin.peminjaman.index')
                ]);
            }

            return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil disetujui.');
        } catch (\Exception $e) {
            Log::error('Error approving peminjaman', [
                'id_peminjaman' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyetujui peminjaman.',
                    'redirect' => route('admin.peminjaman.index')
                ], 500);
            }

            return redirect()->route('admin.peminjaman.index')->with('error', 'Terjadi kesalahan saat menyetujui peminjaman.');
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Cek apakah status saat ini masih Menunggu
            if ($peminjaman->status_peminjaman !== 'Menunggu') {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Peminjaman tidak dapat ditolak karena status saat ini bukan Menunggu.',
                        'redirect' => route('admin.peminjaman.index')
                    ], 422);
                }
                return redirect()->route('admin.peminjaman.index')->with('error', 'Peminjaman tidak dapat ditolak karena status saat ini bukan Menunggu.');
            }

            $validatedData = $request->validate([
                'alasan_penolakan' => 'required|string|max:500',
            ]);

            // Update status dan alasan penolakan
            $peminjaman->status_peminjaman = 'Ditolak';
            $peminjaman->alasan_penolakan = $validatedData['alasan_penolakan'];
            $peminjaman->save();

            // Log untuk debugging
            Log::info('Peminjaman rejected', [
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'status_sebelumnya' => 'Menunggu',
                'status_setelahnya' => 'Ditolak',
                'user' => Auth::user()->name ?? 'Unknown'
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peminjaman berhasil ditolak.',
                    'redirect' => route('admin.peminjaman.index')
                ]);
            }

            return redirect()->route('admin.peminjaman.lihat_peminjaman', $peminjaman->id_peminjaman)->with('success', 'Peminjaman berhasil ditolak.');
        } catch (\Exception $e) {
            Log::error('Error rejecting peminjaman', [
                'id_peminjaman' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menolak peminjaman.',
                    'redirect' => route('admin.peminjaman.index')
                ], 500);
            }

            return redirect()->route('admin.peminjaman.index')->with('error', 'Terjadi kesalahan saat menolak peminjaman.');
        }
    }

    public function complete($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Cek apakah status saat ini adalah Disetujui
            if ($peminjaman->status_peminjaman !== 'Disetujui') {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Peminjaman tidak dapat diselesaikan karena status saat ini bukan Disetujui.',
                        'redirect' => route('admin.peminjaman.index')
                    ], 422);
                }
                return redirect()->route('admin.peminjaman.index')->with('error', 'Peminjaman tidak dapat diselesaikan karena status saat ini bukan Disetujui.');
            }

            // Update status
            $peminjaman->status_peminjaman = 'Selesai';
            $peminjaman->save();

            // Update status sumber daya (ruangan dan/atau proyektor) menjadi Tersedia
            if ($peminjaman->id_ruangan) {
                PeminjamanHelper::updateResourceStatus($peminjaman, 'Selesai');
            }
            if ($peminjaman->id_proyektor) {
                PeminjamanHelper::updateResourceStatus($peminjaman, 'Selesai');
            }

            // Log untuk debugging
            Log::info('Peminjaman completed', [
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'status_sebelumnya' => 'Disetujui',
                'status_setelahnya' => 'Selesai',
                'id_ruangan' => $peminjaman->id_ruangan,
                'id_proyektor' => $peminjaman->id_proyektor,
                'user' => Auth::user()->name ?? 'Unknown'
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peminjaman berhasil diselesaikan.',
                    'redirect' => route('admin.peminjaman.index')
                ]);
            }

            return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil diselesaikan.');
        } catch (\Exception $e) {
            Log::error('Error completing peminjaman', [
                'id_peminjaman' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyelesaikan peminjaman.',
                    'redirect' => route('admin.peminjaman.index')
                ], 500);
            }

            return redirect()->route('admin.peminjaman.index')->with('error', 'Terjadi kesalahan saat menyelesaikan peminjaman.');
        }
    }

    public function showRejectForm($id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        return view('admin.peminjaman.reject_form', compact('peminjaman'));
    }
}
