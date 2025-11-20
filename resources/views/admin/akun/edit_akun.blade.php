@extends('layouts.app')
@section('title', 'Akun - Edit Akun')
@section('content')

<div class="max-w-xl mx-auto py-8">

    <h1 class="text-2xl font-bold mb-6">Edit Akun</h1>

    <form method="POST" action="{{ route('admin.akun.update', $akun) }}" 
          class="bg-white p-6 rounded-lg shadow">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium">Nama</label>
            <input name="nama" value="{{ old('nama', $akun->nama) }}" 
                   class="w-full p-2 border rounded-md">
        </div>

        <div class="mb-4">
            <label class="block font-medium">Email</label>
            <input name="email" value="{{ old('email', $akun->email) }}" 
                   class="w-full p-2 border rounded-md">
        </div>

        <div class="mb-4">
            <label class="block font-medium">Password (kosongkan jika tidak diubah)</label>
            <input type="password" name="password"
                   class="w-full p-2 border rounded-md">
        </div>

        <div class="mb-4">
            <label class="block font-medium">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full p-2 border rounded-md">
        </div>

        <div class="mb-4">
            <label class="block font-medium">Role</label>
            <select name="role_id" class="w-full p-2 border rounded-md">
                @foreach($roles ?? [] as $r)
                    <option value="{{ $r->id_role }}"
                        {{ old('role_id', $akun->role_id) == $r->id_role ? 'selected' : '' }}>
                        {{ $r->nama_role }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" 
                class="w-full py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Simpan Perubahan
        </button>
    </form>

    <a href="{{ route('admin.akun.index') }}" 
       class="inline-block mt-4 text-blue-600 hover:underline">
       ← Kembali
    </a>

</div>
@endsection
