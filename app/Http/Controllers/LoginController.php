<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        return User::Login($request);
    }

    public function logout(Request $request)
    {
        User::Logout($request);

        return redirect()->route('public.beranda.index');
    }
    public function register(Request $request)
    {
        // Cek apakah email sudah terdaftar
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            if (!$existingUser->is_verified) {
                // Email sudah terdaftar tapi belum diverifikasi
                return back()
                    ->withErrors(['email' => 'Email ini sudah terdaftar tapi belum diverifikasi.'])
                    ->withInput()
                    ->with('email_exists', $request->email)
                    ->with('email_verified', false);
            } else {
                // Email sudah terdaftar dan sudah diverifikasi
                return back()
                    ->withErrors(['email' => 'Email ini sudah terdaftar di sistem. Silakan login atau gunakan email lain.'])
                    ->withInput()
                    ->with('email_exists', $request->email)
                    ->with('email_verified', true);
            }
        }

        $u = User::Register($request);

        if ($u instanceof \Illuminate\Http\RedirectResponse) {
            return $u;
        }

        if (!$u) {
            return back()->withErrors(['register' => 'Pendaftaran gagal.'])->withInput();
        }

        // Simpan role ke session untuk digunakan di halaman verifikasi
        session(['role' => $request->role]);

        // Arahkan ke halaman tunggu verifikasi
        return redirect()->route('verification.waiting')->with('success', 'Pendaftaran berhasil! Silakan verifikasi email Anda.');
    }
}
