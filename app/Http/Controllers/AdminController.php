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

        // Mengambil data peminjaman terbaru untuk ditampilkan di tabel
        $peminjaman_terbaru = Peminjaman::with(['user', 'sarpras'])->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'jumlah_akun',
            'jumlah_sarpras',
            'peminjaman_menunggu',
            'peminjaman_disetujui',
            'peminjaman_terbaru'
        ));
    }
}
