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
            <!-- Filter Periode -->
            <div class="flex items-center gap-4">
                <label for="periode" class="text-sm font-medium text-gray-700">Filter Periode:</label>
                <select id="periode" name="periode" onchange="updateFilters()" class="p-2 text-sm text-gray-700 border border-gray-300 rounded-lg">
                    <option value="perbulan" {{ $periode == 'perbulan' ? 'selected' : '' }}>Perbulan</option>
                    <option value="persemester" {{ $periode == 'persemester' ? 'selected' : '' }}>Persemester</option>
                </select>
            </div>
            <form id="filterForm" method="GET" action="{{ route('laporan.index') }}" class="hidden">
                <input type="hidden" id="statusInput" name="status" value="">
                <input type="hidden" id="periodeInput" name="periode" value="">
            </form>
        <div class="flex gap-3">
            <a href="{{ route('laporan.pdf', ['periode' => $periode]) }}"
               class="flex gap-3 px-3 py-2 text-white transition bg-blue-500 rounded-lg items-center text-sm shadow hover:bg-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 10.5L12 15m0 0l4.5-4.5M12 15V3" />
                </svg>
                Unduh PDF
            </a>

            <a href="{{ route('laporan.excel', ['periode' => $periode]) }}"
               class="flex gap-3 px-3 py-2 text-sm items-center text-white transition bg-green-600 rounded-lg shadow hover:bg-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5h7.5M8.25 9h7.5M8.25 13.5h7.5M4.5 19.5h15M4.5 3h15a1.5 1.5 0 011.5 1.5v18a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 22.5v-18A1.5 1.5 0 014.5 3z" />
                </svg>
                Export Excel
            </a>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="flex items-center justify-between p-5 text-white bg-gradient-to-l from-blue-500 to-blue-600 rounded-xl">
            <div>
                <p class="text-sm text-gray-300">Total Peminjaman</p>
                <h2 class="text-3xl font-bold">{{ $totalPeminjaman }}</h2>
            </div>
            <div class="p-3 bg-green-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 2.25h6a2.25 2.25 0 012.25 2.25v15a2.25 2.25 0 01-2.25 2.25H9A2.25 2.25 0 016.75 19.5v-15A2.25 2.25 0 019 2.25z" />
                </svg>
            </div>

        </div>

        <div class="flex items-center justify-between p-5 text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl">
            <div>
                <p class="text-sm text-gray-300">Peminjaman Hari Ini</p>
                <h2 class="text-3xl font-bold">{{ $PeminjamanHariIni }}</h2>
            </div>
            <div class="p-3 bg-blue-600 rounded-lg">
                <!-- Calendar Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 8.25h18M4.5 7.5v12.75A2.25 2.25 0 006.75 22.5h10.5A2.25 2.25 0 0019.5 20.25V7.5" />
                </svg>
            </div>
        </div>

        <div class="flex items-center justify-between p-5 text-white bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl">
            <div>
                <p class="text-sm text-gray-300">Waktu Rata-Rata Peminjaman</p>
                <h2 class="text-3xl font-bold">{{ number_format($waktuRataRata, 1) }} jam</h2>
            </div>
            <div class="flex p-3 bg-yellow-500 rounded-lg items-center">
                <!-- Clock Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Peminjam Teratas & Sarpras Terpopuler -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Peminjam Teratas -->
        <div class="p-5 bg-white shadow rounded-xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-semibold text-gray-800">Peminjam Teratas</h2>
                <span class="text-xs text-gray-500">({{ $periodeLabel }})</span>
            </div>
            <ul class="space-y-3">
                @forelse($peminjamTeratas as $index => $peminjam)
                <li class="flex items-center justify-between pb-2 border-b border-gray-200 last:border-b-0">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-full text-sm font-bold shadow-md">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $peminjam['nama'] }}</p>
                            <p class="text-xs text-gray-500">{{ $peminjam['email'] }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-700">{{ $peminjam['jumlah'] }} Peminjaman</span>
                </li>
                @empty
                <li class="text-sm text-gray-500">Tidak ada data peminjam teratas.</li>
                @endforelse
            </ul>
        </div>

        <!-- Sarpras Terpopuler -->
        <div class="p-5 bg-white shadow rounded-xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-semibold text-gray-800">Sarpras Terpopuler</h2>
                <span class="text-xs text-gray-500">({{ $periodeLabel }})</span>
            </div>
            <ul class="space-y-3">
                @forelse($sarprasTerpopuler as $index => $sarpras)
                <li class="flex items-center justify-between pb-2 border-b border-gray-200 last:border-b-0">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-full text-sm font-bold shadow-md">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $sarpras['nama'] }}</p>
                            <p class="text-xs text-gray-500">{{ $sarpras['lokasi' ?? 'merk_'] }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-700">{{ $sarpras['jumlah'] }} Peminjaman</span>
                </li>
                @empty
                <li class="text-sm text-gray-500">Tidak ada data sarpras terpopuler.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<script>
    function updateFilters() {
        const status = document.getElementById('status').value;
        const periode = document.getElementById('periode').value;

        document.getElementById('statusInput').value = status;
        document.getElementById('periodeInput').value = periode;

        document.getElementById('filterForm').submit();
    }
</script>
@endsection
