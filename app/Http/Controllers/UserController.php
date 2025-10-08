<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use App\Models\Sarpras;

class UserController extends Controller
{
    public function dashboardUser()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $totalRuangan = Peminjaman::where('id_akun', $user->id_akun ?? $user->id)
            ->whereHas('sarpras', function ($q) {
                $q->where('jenis', 'ruangan');
            })->count();

        $totalProyektor = Peminjaman::where('id_akun', $user->id_akun ?? $user->id)
            ->whereHas('sarpras', function ($q) {
                $q->where('jenis', 'proyektor');
            })->count();

        $peminjamanAktif = Peminjaman::where('id_akun', $user->id_akun ?? $user->id)
            ->orderBy('tgl_pinjam', 'desc')
            ->get();

        return view('user.peminjaman', compact('totalRuangan', 'totalProyektor', 'peminjamanAktif'));
    }
}
