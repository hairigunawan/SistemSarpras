<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Peminjaman;

class CountPeminjamanHariIni
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $today = Carbon::today()->format('Y-m-d');
        $lastCountDate = session('peminjaman_hari_ini_date');
        
        // Reset counter jika tanggal sudah berubah atau session belum ada
        if ($lastCountDate !== $today) {
            $peminjamanHariIniCount = Peminjaman::whereDate('tanggal_pinjam', $today)->count();
            session(['peminjaman_hari_ini_count' => $peminjamanHariIniCount]);
            session(['peminjaman_hari_ini_date' => $today]);
        }
        
        return $next($request);
    }
}