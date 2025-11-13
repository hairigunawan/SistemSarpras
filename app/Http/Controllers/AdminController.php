<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\Peminjaman;
use App\Models\Proyektor;
use App\Helpers\ProyektorStatusHelper;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin dengan data ringkasan.
     */
    public function dashboard()
    {
        // Menghitung jumlah data untuk ditampilkan di card statistik
        $jumlah_akun = User::count();
        $jumlah_sarpras = Ruangan::count();
        $peminjaman_menunggu = Peminjaman::where('status_peminjaman', 'Menunggu')->count();
        $peminjaman_disetujui = Peminjaman::where('status_peminjaman', 'Disetujui')->count();

        // Statistik ruangan
        $ruanganTersedia = Ruangan::whereHas('status', function ($query) {
            $query->where('nama_status', 'Tersedia');
        })->count();
        $ruanganTerpakai = Ruangan::whereHas('status', function ($query) {
            $query->where('nama_status', 'Dipinjam');
        })->count();
        $ruanganPerbaikan = Ruangan::whereHas('status', function ($query) {
            $query->where('nama_status', 'Perbaikan');
        })->count();

        // Perbarui status proyektor berdasarkan peminjaman aktif
        ProyektorStatusHelper::updateProyektorStatus();

        // Statistik proyektor
        $proyektorTersedia = Proyektor::whereHas('status', function ($query) {
            $query->where('nama_status', 'Tersedia');
        })->count();
        $proyektorTerpakai = Proyektor::whereHas('status', function ($query) {
            $query->where('nama_status', 'Dipinjam');
        })->count();
        $proyektorPerbaikan = Proyektor::whereHas('status', function ($query) {
            $query->where('nama_status', 'Perbaikan');
        })->count();

        // Mengambil data peminjaman terbaru untuk ditampilkan di tabel
        $peminjaman_terbaru = Peminjaman::with('user', 'ruangan', 'proyektor')->latest()->take(5)->get();

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
        return view('admin.dashboard.index');
    }
}
