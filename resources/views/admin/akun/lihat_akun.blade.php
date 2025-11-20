@extends('layouts.app')

@section('title', 'Detail Akun')

@section('content')
<div class="bg-white rounded-lg p-6 shadow-md">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Detail Akun</h1>
        <div class="flex space-x-4">
            <a href="{{ route('admin.akun.edit_akun', $u->id_akun) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Edit Akun</a>
            <form action="{{ route('admin.akun.hapus_akun', $u->id_akun) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Hapus Akun</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-gray-600">Nama:</p>
            <p class="text-lg font-semibold">{{ $u->nama }}</p>
        </div>
        <div>
            <p class="text-gray-600">Email:</p>
            <p class="text-lg font-semibold">{{ $u->email }}</p>
        </div>
        <div>
            <p class="text-gray-600">Role:</p>
            <p class="text-lg font-semibold">{{ $u->userRole->nama_role ?? '-' }}</p>
        </div>
        <div>
            <p class="text-gray-600">Dibuat pada:</p>
            <p class="text-lg font-semibold">{{ $u->created_at->format('d M Y H:i') }}</p>
        </div>
        <div>
            <p class="text-gray-600">Terakhir diperbarui:</p>
            <p class="text-lg font-semibold">{{ $u->updated_at->format('d M Y H:i') }}</p>
        </div>
    </div>

    <div class="mt-8">
        <a href="{{ route('admin.akun.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Kembali ke Daftar Akun</a>
    </div>

</div>
@endsection
