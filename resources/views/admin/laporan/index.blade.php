@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Laporan</h1>
            <p class="text-sm text-gray-500">Analisis dan statistik sistem</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('laporan.Pdf') }}"
               class="flex items-center gap-2 px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
                {{-- Download Icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 10.5L12 15m0 0l4.5-4.5M12 15V3" />
                </svg>
                Unduh PDF
            </a>

            <a href="{{ route('laporan.xlsx') }}"
               class="flex items-center gap-2 px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                {{-- File Spreadsheet Icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5h7.5M8.25 9h7.5M8.25 13.5h7.5M4.5 19.5h15M4.5 3h15a1.5 1.5 0 011.5 1.5v18a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 22.5v-18A1.5 1.5 0 014.5 3z" />
                </svg>
                Export Excel
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="flex items-center justify-between p-5 text-white bg-gray-800 rounded-xl">
            <div>
                <p class="text-sm text-gray-300">Total Peminjaman</p>
                <h2 class="text-3xl font-bold">{{ $totalPeminjaman }}</h2>
                {{-- <p class="mt-1 text-xs text-green-400">+12% dari Minggu Lalu</p> --}}
            </div>
            <div class="p-3 bg-green-600 rounded-lg">
                {{-- Clipboard Icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 2.25h6a2.25 2.25 0 012.25 2.25v15a2.25 2.25 0 01-2.25 2.25H9A2.25 2.25 0 016.75 19.5v-15A2.25 2.25 0 019 2.25z" />
                </svg>
            </div>
        </div>

        <div class="flex items-center justify-between p-5 text-white bg-gray-800 rounded-xl">
            <div>
                <p class="text-sm text-gray-300">Peminjaman Perhari ini</p>
                <h2 class="text-3xl font-bold">{{ $peminjamanHariIni }}</h2>
                {{-- <p class="mt-1 text-xs text-red-400">-5% dari hari lalu</p> --}}
            </div>
            <div class="p-3 bg-blue-600 rounded-lg">
                {{-- Calendar Icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 8.25h18M4.5 7.5v12.75A2.25 2.25 0 006.75 22.5h10.5A2.25 2.25 0 0019.5 20.25V7.5" />
                </svg>
            </div>
        </div>

        <div class="flex items-center justify-between p-5 text-white bg-gray-800 rounded-xl">
            <div>
                <p class="text-sm text-gray-300">Waktu Lama Peminjaman Per Hari</p>
                <h2 class="text-3xl font-bold">{{ $waktuRataRata }} jam</h2>
                {{-- <p class="mt-1 text-xs text-green-400">+0,5% dari hari lalu</p> --}}
            </div>
            <div class="p-3 bg-yellow-500 rounded-lg">
                {{-- Clock Icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Peminjam Teratas & Sarpras Terpopuler -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Peminjam Teratas -->
        <div class="p-5 bg-white shadow rounded-xl">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold text-gray-800">Peminjam Teratas</h2>
                <select class="p-1 text-sm text-gray-700 border rounded-lg">
                    <option>Perbulan</option>
                    <option>Pertahun</option>
                </select>
            </div>
            <ul class="space-y-3">
                @foreach($peminjamTeratas as $index => $peminjam)
                <li class="flex items-center justify-between pb-2 border-b">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center text-white bg-gray-900 rounded-full w-7 h-7">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $peminjam['nama'] }}</p>
                            <p class="text-sm text-gray-500">{{ $peminjam['email'] }}</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-700">{{ $peminjam['jumlah'] }} Peminjaman</span>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Sarpras Terpopuler -->
        <div class="p-5 bg-white shadow rounded-xl">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold text-gray-800">Sarpras Terpopuler</h2>
                <select class="p-1 text-sm text-gray-700 border rounded-lg">
                    <option>Perbulan</option>
                    <option>Pertahun</option>
                </select>
            </div>
            <ul class="space-y-3">
                @foreach($sarprasTerpopuler as $index => $sarpras)
                <li class="flex items-center justify-between pb-2 border-b">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center text-white bg-gray-900 rounded-full w-7 h-7">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $sarpras['nama'] }}</p>
                            <p class="text-sm text-gray-500">{{ $sarpras['lokasi'] }}</p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-700">{{ $sarpras['jumlah'] }} Peminjaman</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
