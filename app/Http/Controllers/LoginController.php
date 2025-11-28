<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah user dengan email ini ada
        $u = User::where('email', $request->email)->first();

        if (!$u) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ]);
        }

        // Cek password
        if (!Hash::check($request->password, $u->password)) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ]);
        }

        // Login user
        Auth::login($u);

        // Arahkan user berdasarkan peran
        if ($u->userRole->nama_role === 'Admin') {
            return redirect()->route('admin.dashboard.index');
        } else {
            return redirect()->route('public.beranda.index.auth');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('public.beranda.index');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/|unique:users,nomor_telepon',
            'role' => 'required|in:Dosen,Mahasiswa',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $role = Role::where('nama_role', $validated['role'])->first();

        if (! $role) {
            return back()->withErrors(['role' => 'Role tidak ditemukan.'])->withInput();
        }

        $u = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'nomor_telepon' => $validated['nomor_telepon'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role->id_role,
        ]);

        Auth::login($u);

        if (in_array($role->nama_role, ['Dosen', 'Mahasiswa'])) {
            return redirect()->route('public.beranda.index.auth');
        }

        return redirect()->route('public.beranda.index');
    }
}
