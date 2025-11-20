@extends('layouts.app')

@section('title', 'Akun - Index')

@section('content')
<div class="bg-white rounded-lg p-6">

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
        <h1 class="text-2xl font-bold">Daftar Akun</h1>

        <div class="flex justify-between gap-4">
            <form method="GET" action="{{ route('admin.akun.index') }}" class="flex items-center space-x-2">
                @if(request('jenis'))
                    <input type="hidden" name="nama" value="{{ request('nama') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Akun" class="w-full md:w-64 px-4 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-300">
            </form>

            <a href="{{ route('admin.akun.tambah_akun', ['id' => 'new']) }}"
            class="px-4 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
            + Tambah Akun
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border text-gray-700 font-medium">Nama</th>
                    <th class="p-3 border text-gray-700 font-medium">Email</th>
                    <th class="p-3 border text-gray-700 font-medium">Role</th>
                    <tr class="p-3 border text-gray-700 font-medium w-32 text-center">Aksi</tr>
                </tr>
            </thead>
            <tbody>
                @foreach($akuns ?? [] as $a)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border text-sm">{{ $a->nama }}</td>
                    <td class="p-3 border text-sm">{{ $a->email }}</td>
                    <td class="p-3 border text-sm">{{ $a->userRole->nama_role ?? '-' }}</td>
                    <td class="p-3 border text-sm text-center">
                        <a href="{{ route('admin.akun.edit_akun', $a->id) }}"
                           class="text-blue-600 px-6 py-1.5 hover:rounded-lg hover:bg-blue-100">Edit</a>
                    </td>
                    <td class="p-3 border text-sm text-center">
                        <a href="{{ route('admin.akun.lihat_akun', $a->id) }}"
                           class="text-blue-600 px-6 py-1.5 hover:rounded-lg hover:bg-blue-100">deatil</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
