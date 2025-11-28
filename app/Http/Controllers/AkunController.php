<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    /**
     * Menampilkan daftar semua akun.
     */
    public function index()
    {
        $akuns = User::with('userRole')->latest()->paginate(10);
        return view('admin.akun.index', compact('akuns'));
    }

    /**
     * Menampilkan form untuk membuat akun baru.
     */
    public function tambah_akun()
    {
        $roles = Role::all();
        return view('admin.akun.tambah_akun', compact('roles'));
    }

    /**
     * Menyimpan akun baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'nomor_telepon' => 'nullable|string|max:20',
            'password'      => 'required|string|min:8|confirmed',
            'role_id'       => 'required|exists:roles,id_role',
        ]);

        // 2. Panggil Model untuk menyimpan (Enkapsulasi)
        User::storeAkun($validatedData);

        return redirect()->route('admin.akun.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit akun.
     */
    public function edit_akun(User $akun)
    {
        $roles = Role::all();
        return view('admin.akun.edit_akun', compact('akun', 'roles'));
    }

    /**
     * Memperbarui data akun di database.
     */
    public function update(Request $request, User $akun)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($akun->id_akun, 'id_akun')],
            'nomor_telepon' => 'nullable|string|max:20',
            'role_id'       => 'required|exists:roles,id_role',
            'password'      => 'nullable|string|min:8|confirmed',
        ]);

        // 2. Panggil Model untuk update (Enkapsulasi)
        $akun->updateAkun($validatedData);

        return redirect()->route('admin.akun.index')
            ->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Menghapus akun dari database.
     */
    public function hapus_akun(User $akun)
    {
        // Validasi logika bisnis (Policy) tetap bisa di controller atau dipindah ke Model/Policy
        if (Auth::check() && Auth::id() === $akun->id_akun) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Panggil Model untuk hapus
        $akun->deleteAkun();

        return redirect()->route('admin.akun.index')
            ->with('success', 'Akun berhasil dihapus.');
    }

    public function lihat_akun($id)
    {
        $u = User::findOrFail($id);
        return view('admin.akun.lihat_akun', compact('u'));
    }
}
