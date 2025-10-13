<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    /**
     * Menampilkan halaman landing publik dengan statistik.
     */
    public function index()
    {
        // Hitung statistik ruangan
        $RuanganTersedia = Sarpras::where('jenis_sarpras', 'Ruangan')->where('status', 'Tersedia')->count();
        $RuanganTerpakai = Sarpras::where('jenis_sarpras', 'Ruangan')->where('status', 'Dipinjam')->count();
        $RuanganPerbaikan = Sarpras::where('jenis_sarpras', 'Ruangan')->where('status', 'Perbaikan')->count();

        // Hitung statistik proyektor
        $ProyektorTersedia = Sarpras::where('jenis_sarpras', 'Proyektor')->where('status', 'Tersedia')->count();
        $ProyektorTerpakai = Sarpras::where('jenis_sarpras', 'Proyektor')->where('status', 'Dipinjam')->count();
        $ProyektorPerbaikan = Sarpras::where('jenis_sarpras', 'Proyektor')->where('status', 'Perbaikan')->count();



        // Ambil data laboratorium terpakai (peminjaman yang sedang berlangsung)
        $labs = Peminjaman::with(['sarpras'])
            ->where('status', 'Disetujui')
            ->whereHas('sarpras', function ($query) {
                $query->where('jenis_sarpras', 'Ruangan');
            })
            ->get()
            ->map(function ($peminjaman) {
                return [
                    'nama' => $peminjaman->sarpras->nama_sarpras,
                    'kelas' => $peminjaman->nama_peminjam ?? 'N/A',
                    'matkul' => $peminjaman->keterangan ?? 'N/A',
                    'waktu' => $peminjaman->jam_mulai . ' - ' . $peminjaman->jam_selesai,
                ];
            })->take(3)->toArray();

        return view('public.beranda.index', compact(
            'RuanganTersedia',
            'RuanganTerpakai',
            'RuanganPerbaikan',
            'ProyektorTersedia',
            'ProyektorTerpakai',
            'ProyektorPerbaikan',
            'labs'
        ));
    }

    /**
     * Menampilkan form untuk mengajukan peminjaman publik.
     */
    /**
     * Menampilkan form untuk mengajukan peminjaman publik.
     */
    public function createPeminjaman()
    {
        // Ambil semua peminjaman yang statusnya 'Disetujui'
        $peminjamanDisetujui = Peminjaman::where('status', 'Disetujui')->get();

        $jadwalPeminjaman = [];

        // Ambil sarpras yang statusnya 'Tersedia' untuk dipilih
        $sarprasTersedia = Sarpras::where('status', 'Tersedia')->get();

        // Proses setiap peminjaman yang disetujui untuk membuat rentang tanggal
        foreach ($peminjamanDisetujui as $peminjaman) {
            $id = $peminjaman->id_sarpras;
            if (!isset($jadwalPeminjaman[$id])) {
                $jadwalPeminjaman[$id] = [];
            }

            // Buat rentang tanggal dari tanggal pinjam s.d. tanggal kembali
            $period = \Carbon\CarbonPeriod::create($peminjaman->tanggal_pinjam, $peminjaman->tanggal_kembali);
            foreach ($period as $date) {
                $jadwalPeminjaman[$id][] = $date->format('Y-m-d');
            }
            // Pastikan tidak ada tanggal duplikat
            $jadwalPeminjaman[$id] = array_unique($jadwalPeminjaman[$id]);
        }

        // Kirim kedua variabel ke view SETELAH semua data diproses
        return view('public.peminjaman.create', compact('sarprasTersedia', 'jadwalPeminjaman'));
    }
    /**
     * Menyimpan data pengajuan peminjaman publik baru.
     */
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'telepon_peminjam' => 'required|string|max:20',
            'id_sarpras' => 'required|exists:sarpras,id_sarpras',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keterangan' => 'nullable|string',
        ]);

        // Logika untuk cek jadwal bentrok
        $isBentrok = Peminjaman::where('id_sarpras', $request->id_sarpras)
            ->whereIn('status_peminjaman', ['Disetujui', 'Menunggu']) // Cek jadwal yang sudah disetujui atau masih menunggu
            ->where(function ($query) use ($request) {
                // Waktu mulai dan selesai dari request peminjaman baru
                $pinjam_mulai = $request->tanggal_pinjam . ' ' . $request->jam_mulai;
                $pinjam_selesai = $request->tanggal_kembali . ' ' . $request->jam_selesai;

                // Kondisi tumpang tindih:
                // (start1 < end2) and (start2 < end1)
                $query->where(function ($q) use ($pinjam_mulai) {
                    // Pengecekan dimana peminjaman LAMA berakhir SETELAH peminjaman BARU dimulai
                    $q->whereRaw("CONCAT(tanggal_kembali, ' ', jam_selesai) > ?", [$pinjam_mulai]);
                })->where(function ($q) use ($pinjam_selesai) {
                    // DAN peminjaman LAMA dimulai SEBELUM peminjaman BARU berakhir
                    $q->whereRaw("CONCAT(tanggal_pinjam, ' ', jam_mulai) < ?", [$pinjam_selesai]);
                });
            })
            ->exists(); // Cukup cek apakah ada data yang bentrok

        // Jika ditemukan jadwal yang bentrok, kembalikan dengan pesan error
        if ($isBentrok) {
            return back()->withErrors([
                'id_sarpras' => 'Jadwal yang Anda pilih bentrok dengan peminjaman lain. Silakan pilih tanggal atau jam yang berbeda.'
            ])->withInput(); // withInput() agar data yang sudah diisi tidak hilang
        }

        // Jika tidak ada bentrok, buat data peminjaman baru
        Peminjaman::create([
            'id_akun' => Auth::id(), // Menggunakan ID user yang sedang login
            'id_sarpras' => $request->id_sarpras,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan' => $request->keterangan,
            'nama_peminjam' => Auth::user()->nama, // Ambil dari data user
            'email_peminjam' => Auth::user()->email, // Ambil dari data user
            'telepon_peminjam' => $request->telepon_peminjam,
            'status_peminjaman' => 'Menunggu', // Status default saat pengajuan
        ]);

        return redirect()->route('public.peminjaman.daftarpeminjaman')
            ->with('success', 'Peminjaman berhasil dikirim dan sedang diproses.');
    }

    public function daftarpeminjaman()
    {
        // Misalnya ambil semua data peminjaman dari model Peminjaman
        $peminjaman = \App\Models\Peminjaman::latest()->get();

        // Tampilkan view publik
        return view('public.peminjaman.daftarpeminjaman', compact('peminjaman'));
    }

    public function saranaPrasarana()
    {
        $halamansarpras = \App\Models\Halamansarpras::latest()->get();

        // Tampilkan view publik
        return view('public.user.halamansarpras', compact('halamansarpras'));
    }
}
