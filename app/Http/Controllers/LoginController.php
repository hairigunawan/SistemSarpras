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
        $u = User::Register($request);

        if (!$u) {
            return back()->withErrors(['register' => 'Pendaftaran gagal.'])->withInput();
        }

        if ($u->relationLoaded('userRole') && $u->userRole) {
            if (in_array($u->userRole->nama_role, ['Dosen', 'Mahasiswa'])) {
                return redirect()->route('public.beranda.index.auth');
            }
        }

        return redirect()->route('public.beranda.index');
    }
}
