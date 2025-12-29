@extends('layouts.app')

@section('title', 'Akun - Index')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6 md:p-8">
    <div class="max-w-6xl mx-auto">

        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-700 mb-2">Manajemen Akun</h1>
            <p class="text-slate-600">Kelola semua akun pengguna sistem dengan mudah</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="mb-6 flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
            <form method="GET" action="{{ route('admin.akun.index') }}" class="flex-1 max-w-md">
                @if(request('nama'))
                    <input type="hidden" name="nama" value="{{ request('nama') }}">
                @endif
                @if(request('email'))
                    <input type="hidden" name="email" value="{{ request('email') }}">
                @endif

                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama atau email..."
                        class="w-full pl-10 pr-4 py-2 text-sm border border-slate-300 rounded-lg
                            focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent
                            transition-all duration-200">
                </div>
            </form>

            <a href="{{ route('admin.akun.tambah_akun', ['id' => 'new']) }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700
                   text-white font-medium text-xs uppercase rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Akun
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @if(count($u ?? []) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Nama</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Role</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($u as $akuns)
                            <tr class="hover:bg-slate-50 transition-colors duration-150">
                                <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                    <div class="text-center">
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                    <div class="flex items-center gap-3">
                                        {{ $akuns->nama }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $akuns->email }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-start text-xs font-medium">
                                        {{ $akuns->userRole->nama_role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.akun.lihat_akun', $akuns->id_akun) }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-600
                                           bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-150">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-slate-600 font-medium">Tidak ada akun ditemukan</p>
                </div>
            @endif
        </div>

    </div>
</div>

<style>
    @media (max-width: 768px) {
        .responsive-stack {
            flex-direction: column;
        }
    }
</style>
@endsection
