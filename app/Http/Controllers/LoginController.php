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
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ]);
        }

        // Cek apakah user adalah admin
        if ($user->userRole->nama_role !== 'Admin') {
            return back()->withErrors([
                'email' => 'Hanya admin yang dapat login dengan email dan password. Gunakan login Google untuk user lainnya.',
            ]);
        }

        // Cek password untuk admin
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah.',
            ]);
        }

        // Login admin
        Auth::login($user);

        // Arahkan langsung ke dashboard admin
        return redirect()->route('admin.dashboard.index');
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
            'role' => 'required|in:Dosen,Mahasiswa',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $role = Role::where('nama_role', $validated['role'])->first();

        if (! $role) {
            return back()->withErrors(['role' => 'Role tidak ditemukan.'])->withInput();
        }

        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role->id_role,
        ]);

        Auth::login($user);

        if (in_array($role->nama_role, ['Dosen', 'Mahasiswa'])) {
            return redirect()->route('public.beranda.index');
        }

        return redirect()->route('public.beranda.index');
    }
}
