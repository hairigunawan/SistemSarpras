<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Jika user belum diverifikasi dan bukan admin, arahkan ke halaman verifikasi
            if (!$user->is_verified && $user->userRole->nama_role !== 'Admin') {
                return redirect()->route('verification.waiting');
            }
        }

        return $next($request);
    }
}
