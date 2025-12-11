<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    public function index(Request $request)
    {
        return User::HalamanUtama($request);
    }

    public function tambah_akun()
    {
        $roles = Role::all();
        return view('admin.akun.tambah_akun', compact('roles'));
    }

    public function store(Request $request)
    {
        User::storeAkun($request);

        return redirect()->route('admin.akun.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit_akun(User $akun)
    {
        $roles = Role::all();
        return view('admin.akun.edit_akun', compact('akun', 'roles'));
    }

    public function update(Request $request, User $akun)
    {
        return User::EditAkun($request, $akun);
    }

    public function hapus_akun(User $akun)
    {
        return User::HapusAkun($akun);
    }

    public function lihat_akun($id)
    {
        $u = User::findOrFail($id);
        return view('admin.akun.lihat_akun', compact('u'));
    }
}
