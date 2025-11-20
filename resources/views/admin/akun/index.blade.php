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
            class="flex gap-2 px-4 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
                Tambah Akun
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
                    <th class="p-3 border text-gray-700 font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($akuns ?? [] as $a)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border text-sm">{{ $a->nama }}</td>
                    <td class="p-3 border text-sm">{{ $a->email }}</td>
                    <td class="p-3 border text-sm">{{ $a->userRole->nama_role }}</td>
                    <td class="p-3 border text-sm text-center">
                        <a href="{{ route('admin.akun.lihat_akun', $a->id_akun) }}"
                           class="text-blue-600 px-6 py-1.5 hover:rounded-lg hover:bg-blue-100">detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
