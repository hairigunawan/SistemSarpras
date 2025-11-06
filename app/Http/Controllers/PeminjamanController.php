<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Ruangan;
use App\Models\Proyektor;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $mainPeminjaman = Peminjaman::with(['ruangan', 'proyektor', 'user', 'lokasi'])->findOrFail($id);

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

        // Update status sumber daya menggunakan helper
        PeminjamanHelper::updateResourceStatus($peminjaman, 'Selesai');

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil diselesaikan.');
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Cek apakah peminjaman sudah disetujui atau selesai
        if ($peminjaman->status_peminjaman === 'Disetujui' || $peminjaman->status_peminjaman === 'Selesai') {
            return redirect()->route('admin.peminjaman.index')->with('error', 'Peminjaman tidak dapat disetujui karena status sudah: ' . $peminjaman->status_peminjaman);
        }

        // Cek konflik dengan peminjaman lain YANG SUDAH DISETUJUI menggunakan helper
        $conflictRequest = new \stdClass();
        $conflictRequest->tanggal_pinjam = $peminjaman->tanggal_pinjam;
        $conflictRequest->tanggal_kembali = $peminjaman->tanggal_kembali;
        $conflictRequest->jam_mulai = $peminjaman->jam_mulai;
        $conflictRequest->jam_selesai = $peminjaman->jam_selesai;

        // Set ID sumber daya dari peminjaman
        if ($peminjaman->id_ruangan) {
            $conflictRequest->id_ruangan = $peminjaman->id_ruangan;
        } else {
            $conflictRequest->id_proyektor = $peminjaman->id_proyektor;
        }

        // Cek apakah ada konflik dengan peminjaman yang sudah disetujui
        if (PeminjamanHelper::checkConflict($conflictRequest, $peminjaman)) {
            $conflictItems = [];
            if ($peminjaman->id_ruangan) {
                $conflictItems[] = 'Ruangan';
            }
            if ($peminjaman->id_proyektor) {
                $conflictItems[] = 'Proyektor';
            }
            $bentrokMessage = implode(' dan ', $conflictItems) . ' peminjaman ditolak karena bentrok dengan peminjam yang sudah disetujui';

            // Tambahkan alasan penolakan ke peminjaman
            $peminjaman->alasan_penolakan = $bentrokMessage;
            $peminjaman->status_peminjaman = 'Ditolak';
            $peminjaman->save();

            return back()->withErrors(['bentrok' => $bentrokMessage])->withInput();
        }

        // Cari peminjaman lain dengan sumber daya yang sama yang memiliki konflik waktu
        $conflictingPins = Peminjaman::where('id_peminjaman', '!=', $id)
            ->where('status_peminjaman', 'Menunggu')
            ->where(function ($query) use ($peminjaman) {
                // Filter berdasarkan jenis sumber daya
                if ($peminjaman->id_ruangan) {
                    $query->where('id_ruangan', $peminjaman->id_ruangan);
                } else {
                    $query->where('id_proyektor', $peminjaman->id_proyektor);
                }
            })
            ->where(function ($timeQuery) use ($peminjaman) {
                // Pengecekan konflik waktu yang lebih komprehensif
                $timeQuery->where(function ($subQuery) use ($peminjaman) {
                    $subQuery->where('tanggal_pinjam', '<=', $peminjaman->tanggal_kembali)
                             ->where('tanggal_kembali', '>=', $peminjaman->tanggal_pinjam)
                             ->where('jam_mulai', '<', $peminjaman->jam_selesai)
                             ->where('jam_selesai', '>', $peminjaman->jam_mulai);
                });
            })
            ->get();

        // Jika ada peminjaman lain yang konflik, otomatis ditolak
        if ($conflictingPins->count() > 0) {
            foreach ($conflictingPins as $conflictingPin) {
                $conflictingPin->status_peminjaman = 'Ditolak';
                $conflictingPin->alasan_penolakan = 'Peminjaman ditolak karena sumber daya sudah dipinjam pada jam yang sama';
                $conflictingPin->save();
            }
        }

        // Jika tidak ada konflik, setujui peminjaman
        $peminjaman->status_peminjaman = 'Disetujui';
        $peminjaman->save();

        // Update status sumber daya menggunakan helper
        PeminjamanHelper::updateResourceStatus($peminjaman, 'Disetujui');

        // Otomatis menolak peminjaman yang konflik
        $rejectedCount = PeminjamanHelper::autoRejectConflictingPeminjaman($peminjaman);
        
        // Tambahkan informasi penolakan otomatis ke pesan sukses
        $successMessage = 'Peminjaman berhasil disetujui.';
        if ($rejectedCount > 0) {
            $successMessage .= " Otomatis menolak {$rejectedCount} peminjaman yang konflik.";
        }

        return redirect()->route('admin.peminjaman.index')->with('success', $successMessage);
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
