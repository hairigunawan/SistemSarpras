<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // ... (Fungsi index, show, dan lainnya tetap sama) ...

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = Peminjaman::with(['user', 'sarpras']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->has('search') && $request->search) {
            $query->whereHas('sarpras', function ($q) use ($request) {
                $q->where('nama_sarpras', 'like', '%' . $request->search . '%');
            });
        }

        $peminjaman = $query->latest()->get();
        return view('admin.peminjaman.index', compact('peminjaman', 'status'));
    }

    public function show($id)
    {
        $mainPeminjaman = Peminjaman::with(['sarpras', 'user'])->findOrFail($id);

        $conflictingPeminjaman = Peminjaman::where('id_sarpras', $mainPeminjaman->id_sarpras)
            ->where('id_peminjaman', '!=', $id)
            ->where('status', 'Menunggu')
            ->where(function ($query) use ($mainPeminjaman) {
                $query->where(function ($q) use ($mainPeminjaman) {
                    $q->where('tanggal_pinjam', '<=', $mainPeminjaman->tanggal_kembali)
                        ->where('tanggal_kembali', '>=', $mainPeminjaman->tanggal_pinjam);
                });
            })
            ->with('user')
            ->get();

        $candidates = collect([$mainPeminjaman])->merge($conflictingPeminjaman);
        $rankedPeminjaman = [];

        if ($candidates->count() > 1 && $mainPeminjaman->status == 'Menunggu') {
            $weights = [
                'jenis_kegiatan' => 0.0746,
                'jumlah_peserta' => 0.3932,
                'waktu_pengajuan' => 0.1633,
                'durasi_peminjaman' => 0.3690,
            ];
            $criteriaTypes = [
                'jenis_kegiatan' => 'benefit',
                'jumlah_peserta' => 'benefit',
                'waktu_pengajuan' => 'cost',
                'durasi_peminjaman' => 'cost',
            ];
            $matrix = [];
            $peminjamanMap = [];
            foreach ($candidates as $p) {
                $nilaiKegiatan = $this->convertKegiatanToValue($p->keterangan);
                $waktuPengajuan = Carbon::parse($p->tanggal_pinjam)->diffInDays(Carbon::parse($p->created_at)) + 1;
                $durasi = Carbon::parse($p->tanggal_kembali . ' ' . $p->jam_selesai)->diffInHours(Carbon::parse($p->tanggal_pinjam . ' ' . $p->jam_mulai));
                $matrix[$p->id_peminjaman] = [
                    'jenis_kegiatan' => $nilaiKegiatan,
                    'jumlah_peserta' => $p->jumlah_peserta ?? 1,
                    'waktu_pengajuan' => $waktuPengajuan,
                    'durasi_peminjaman' => $durasi > 0 ? $durasi : 1,
                ];
                $peminjamanMap[$p->id_peminjaman] = $p;
            }
            $normalizedMatrix = [];
            $minMax = [];
            foreach (array_keys($weights) as $criteria) {
                $column = array_column($matrix, $criteria);
                $minMax[$criteria] = ['min' => min($column), 'max' => max($column)];
            }
            foreach ($matrix as $id_p => $values) {
                foreach ($values as $criteria => $value) {
                    if ($criteriaTypes[$criteria] == 'benefit') {
                        $normalizedMatrix[$id_p][$criteria] = $minMax[$criteria]['max'] > 0 ? $value / $minMax[$criteria]['max'] : 0;
                    } else {
                        $normalizedMatrix[$id_p][$criteria] = $value > 0 ? $minMax[$criteria]['min'] / $value : 0;
                    }
                }
            }
            $scores = [];
            foreach ($normalizedMatrix as $id_p => $values) {
                $score = 0;
                foreach ($values as $criteria => $normalizedValue) {
                    $score += $normalizedValue * $weights[$criteria];
                }
                $scores[$id_p] = $score;
            }
            arsort($scores);
            foreach ($scores as $id_p => $score) {
                $rankedPeminjaman[] = ['peminjaman' => $peminjamanMap[$id_p], 'kriteria' => $matrix[$id_p], 'skor' => $score];
            }
        } elseif ($mainPeminjaman->status == 'Menunggu') {
            $rankedPeminjaman[] = ['peminjaman' => $mainPeminjaman, 'skor' => 1];
        }

        return view('admin.peminjaman.show', [
            'mainPeminjaman' => $mainPeminjaman,
            'rankedPeminjaman' => $rankedPeminjaman,
            'conflictingCount' => $conflictingPeminjaman->count()
        ]);
    }

    private function convertKegiatanToValue($keterangan)
    {
        $keterangan = strtolower($keterangan ?? '');
        if (str_contains($keterangan, 'seminar') || str_contains($keterangan, 'workshop')) return 5;
        if (str_contains($keterangan, 'rapat penting') || str_contains($keterangan, 'pelatihan')) return 4;
        if (str_contains($keterangan, 'kuliah pengganti') || str_contains($keterangan, 'rapat internal')) return 3;
        if (str_contains($keterangan, 'diskusi kelompok') || str_contains($keterangan, 'belajar bersama')) return 2;
        return 1;
    }

    /**
     * [BARU] Menyimpan pengajuan peminjaman dari form publik.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_sarpras' => 'required|exists:sarpras,id_sarpras',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'nomor_whatsapp' => 'required|string|max:15',
            'jumlah_peserta' => 'required|integer|min:1',
            'keterangan' => 'required|string|max:500',
        ]);

        $validatedData['id_akun'] = Auth::id();
        $validatedData['status'] = 'Menunggu';

        Peminjaman::create($validatedData);

        return redirect()->route('public.peminjaman.riwayat')->with('success', 'Pengajuan peminjaman berhasil dikirim. Silakan tunggu konfirmasi dari admin.');
    }

    public function approve(Request $request, $id)
    {
        $approvedPeminjaman = Peminjaman::findOrFail($id);

        if ($approvedPeminjaman->status !== 'Menunggu') {
            return redirect()->route('admin.peminjaman.show', $id)->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $conflictingPeminjaman = Peminjaman::where('id_sarpras', $approvedPeminjaman->id_sarpras)
            ->where('id_peminjaman', '!=', $id)
            ->where('status', 'Menunggu')
            ->where(function ($query) use ($approvedPeminjaman) {
                $query->where('tanggal_pinjam', '<=', $approvedPeminjaman->tanggal_kembali)
                    ->where('tanggal_kembali', '>=', $approvedPeminjaman->tanggal_pinjam)
                    ->where(function ($timeQuery) use ($approvedPeminjaman) {
                        $timeQuery->where('jam_mulai', '<', $approvedPeminjaman->jam_selesai)
                            ->where('jam_selesai', '>', $approvedPeminjaman->jam_mulai);
                    });
            })
            ->get();

        foreach ($conflictingPeminjaman as $conflict) {
            $conflict->update([
                'status' => 'Ditolak',
                'alasan_penolakan' => 'Jadwal bentrok dengan peminjaman lain yang telah disetujui.',
            ]);
        }

        $approvedPeminjaman->update(['status' => 'Disetujui']);
        $approvedPeminjaman->sarpras->update(['status' => 'Dipinjam']);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil disetujui. Pengajuan lain yang bentrok telah otomatis ditolak.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'Ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function complete(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'Selesai']);
        $peminjaman->sarpras->update(['status' => 'Tersedia']);
        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil diselesaikan.');
    }

    public function riwayat()
    {
        $userId = Auth::id();
        $peminjaman = Peminjaman::where('id_akun', $userId)->with('sarpras')->latest()->get();
        return view('public.peminjaman.riwayat', compact('peminjaman'));
    }


public function pengajuan(Request $request)
{
    // validasi data seperti biasa
    $request->validate([
        'id_sarpras' => 'required',
        'tanggal_pinjam' => 'required|date',
        // tambah validasi lain kalau ada
    ]);

    Peminjaman::create([
        'id_user' => Auth::id(),
        'id_sarpras' => $request->id_sarpras,
        'tanggal_pinjam' => $request->tanggal_pinjam,
        'status' => 'Menunggu',
        'hari_pengajuan' => Carbon::now()->translatedFormat('l'),
    ]);

    return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil diajukan.');
}

public function prioritasRuangan()
    {
        $peminjamans = Peminjaman::whereHas('sarpras', function ($query) {
            $query->where('jenis_sarpras', 'Proyektor');
        })->with('sarpras')->get();

        return view('admin.prioritas.ruangan', compact('peminjaman'));
    }

    public function prioritasProyektor()
{
    $peminjamans = Peminjaman::whereHas('sarpras', function ($query) {
        $query->where('jenis_sarpras', 'Proyektor');
    })->with('sarpras')->get();


    return view('admin.prioritas.proyektor', compact('peminjaman'));
}

    public function hitungPrioritas(Request $request)
    {
        // Contoh logika hitung sederhana
        $total = Peminjaman::count();
        return redirect()->back()->with('success', 'Perhitungan prioritas selesai! Total data: ' . $total);
    }

}
