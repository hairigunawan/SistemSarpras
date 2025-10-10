<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Sarpras;
use Illuminate\Http\Request;

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
            ->whereHas('sarpras', function($query) {
                $query->where('jenis_sarpras', 'Ruangan');
            })
            ->get()
            ->map(function($peminjaman) {
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
    public function createPeminjaman()
    {
        // Ambil sarpras yang statusnya 'Tersedia' untuk dipilih
        $sarprasTersedia = Sarpras::where('status', 'Tersedia')->get();
        return view('public.peminjaman.create', compact('sarprasTersedia'));
    }

    /**
     * Menyimpan data pengajuan peminjaman publik baru.
     */
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'email_peminjam' => 'required|email|max:255',
            'telepon_peminjam' => 'required|string|max:20',
            'id_sarpras' => 'required|exists:sarpras,id_sarpras',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keterangan' => 'nullable|string',
        ]);

        // Tambahan: Logika untuk cek jadwal bentrok bisa ditambahkan di sini

        Peminjaman::create([
            'id_akun' => null, // Public peminjaman
            'id_sarpras' => $request->id_sarpras,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan' => $request->keterangan,
            'nama_peminjam' => $request->nama_peminjam,
            'email_peminjam' => $request->email_peminjam,
            'telepon_peminjam' => $request->telepon_peminjam,
            'status' => 'Menunggu', // Status default saat pengajuan
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

public function sarpras()
{
    $sarpras = \App\Models\Sarpras::latest()->get();

    // Tampilkan view publik
    return view('user.sarpras', compact('sarpras'));

}

}
    