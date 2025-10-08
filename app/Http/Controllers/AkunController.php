<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    /**
     * Menampilkan daftar semua akun.
     */
    public function index()
    {
        $akuns = User::with('role')->latest()->paginate(10);
        return view('admin.akun.index', compact('akuns'));
    }

    /**
     * Menampilkan form untuk membuat akun baru.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.akun.create', compact('roles'));
    }

    /**
     * Menyimpan akun baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id_role',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('akun.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit akun.
     */
    public function edit(User $akun)
    {
        $roles = Role::all();
        return view('admin.akun.edit', compact('akun', 'roles'));
    }

    /**
     * Memperbarui data akun di database.
     */
    public function update(Request $request, User $akun)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($akun->id_akun, 'id_akun')],
            'role_id' => 'required|exists:roles,id_role',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $akun->nama = $request->nama;
        $akun->email = $request->email;
        $akun->role_id = $request->role_id;

        if ($request->filled('password')) {
            $akun->password = Hash::make($request->password);
        }

        $akun->save();

        return redirect()->route('akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Menghapus akun dari database.
     */
    public function destroy(User $akun)
    {
        // Tambahkan validasi agar tidak bisa menghapus akun sendiri jika perlu
        // if (auth()->id() == $akun->id_akun) {
        //     return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        // }

        $akun->delete();
        return redirect()->route('akun.index')->with('success', 'Akun berhasil dihapus.');
    }
}
