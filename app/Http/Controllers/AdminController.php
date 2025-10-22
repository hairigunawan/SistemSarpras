<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sarpras;
use App\Models\Peminjaman;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin dengan data ringkasan.
     */
    public function dashboard()
    {
        // Menghitung jumlah data untuk ditampilkan di card statistik
        $jumlah_akun = User::count();
        $jumlah_sarpras = Sarpras::count();
        $peminjaman_menunggu = Peminjaman::where('status', 'Menunggu')->count();
        $peminjaman_disetujui = Peminjaman::where('status', 'Disetujui')->count();

        // Statistik ruangan
        $ruanganTersedia = Sarpras::where('jenis_sarpras', 'Ruangan')->where('status', 'Tersedia')->count();
        $ruanganTerpakai = Sarpras::where('jenis_sarpras', 'Ruangan')->where('status', 'Dipinjam')->count();
        $ruanganPerbaikan = Sarpras::where('jenis_sarpras', 'Ruangan')->where('status', 'Perbaikan')->count();

        // Statistik proyektor
        $proyektorTersedia = Sarpras::where('jenis_sarpras', 'Proyektor')->where('status', 'Tersedia')->count();
        $proyektorTerpakai = Sarpras::where('jenis_sarpras', 'Proyektor')->where('status', 'Dipinjam')->count();
        $proyektorPerbaikan = Sarpras::where('jenis_sarpras', 'Proyektor')->where('status', 'Perbaikan')->count();

        // Mengambil data peminjaman terbaru untuk ditampilkan di tabel
        $peminjaman_terbaru = Peminjaman::with(['user', 'sarpras'])->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'jumlah_akun',
            'jumlah_sarpras',
            'peminjaman_menunggu',
            'peminjaman_disetujui',
            'ruanganTersedia',
            'ruanganTerpakai',
            'ruanganPerbaikan',
            'proyektorTersedia',
            'proyektorTerpakai',
            'proyektorPerbaikan',
            'peminjaman_terbaru'
        ));
    }
    // method index untuk menampilkan halaman dashboard admin
    public function index()
    {
        return view('admin.dashboard');
    }
}
