<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/CheckRole.php
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check() || !in_array(Auth::user()->userRole->nama_role, $roles)) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }
        return $next($request);
    }
}
