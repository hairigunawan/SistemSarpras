<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Menampilkan form verifikasi email
     */
    public function showVerificationForm(Request $request)
    {
        if (Auth::check() && Auth::user()->is_verified) {
            return redirect()->route('home');
        }

        return view('auth.verify', [
            'email' => $request->email ?? old('email')
        ]);
    }

    /**
     * Memverifikasi email dengan kode
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'verification_code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if ($user->is_verified) {
            return back()->with('success', 'Email Anda sudah terverifikasi.');
        }

        if ($user->verifyEmail($request->verification_code)) {
            // Login user setelah verifikasi berhasil
            Auth::login($user);

            // Arahkan user berdasarkan peran
            if ($user->userRole->nama_role === 'Admin') {
                return redirect()->route('admin.dashboard.index')->with('success', 'Email berhasil diverifikasi!');
            } else {
                return redirect()->route('public.beranda.index.auth')->with('success', 'Email berhasil diverifikasi!');
            }
        } else {
            return back()->withErrors(['verification_code' => 'Kode verifikasi tidak valid atau sudah kadaluarsa.']);
        }
    }

    /**
     * Mengirim ulang kode verifikasi
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        if ($user->is_verified) {
            return back()->with('success', 'Email Anda sudah terverifikasi.');
        }

        if ($user->sendVerificationEmail()) {
            return redirect()->route('verification.waiting')->with('success', 'Kode verifikasi baru telah dikirim ke email Anda.');
        } else {
            return back()->withErrors(['email' => 'Gagal mengirim kode verifikasi. Silakan coba lagi.']);
        }
    }

    /**
     * Menampilkan halaman tunggu verifikasi
     */
    public function showWaitingPage()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->is_verified) {
                return view('auth.waiting', compact('user'));
            }
        }

        return redirect()->route('login');
    }
}
