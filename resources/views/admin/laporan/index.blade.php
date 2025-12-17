@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<!-- Container Utama dengan x-data untuk logic filter -->
<div x-data="laporanPage()" class="min-h-screen p-6 bg-gray-50/50 space-y-8">
\
    <form id="filterForm" method="GET" action="{{ route('laporan.index') }}" class="hidden">
        <input type="hidden" id="periodeInput" name="periode" :value="periode">\
    </form>

    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Laporan & Analisis</h1>
            <p class="mt-1 text-sm text-gray-500">Ringkasan statistik peminjaman dan performa sistem.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Custom Dropdown dengan Alpine.js -->
            <div class="relative z-20" @click.outside="openDropdown = false">
                <button
                    @click="openDropdown = !openDropdown"
                    class="flex items-center justify-between w-40 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all"
                >
                    <span x-text="formatLabel(periode)"></span>
                    <svg class="w-4 h-4 ml-2 text-gray-400 transition-transform duration-200" :class="openDropdown ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
\
                <div
                    x-show="openDropdown"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 w-40 mt-2 origin-top-right bg-white border border-gray-100 rounded shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    style="display: none;"
                >
                    <div class="p-1">
                        <button @click="setPeriode('perbulan')" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 rounded hover:bg-blue-50 hover:text-blue-600 transition-colors" :class="periode === 'perbulan' ? 'bg-blue-50 text-blue-700' : ''">
                            Perbulan
                        </button>
                        <button @click="setPeriode('persemester')" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 rounded hover:bg-blue-50 hover:text-blue-600 transition-colors" :class="periode === 'persemester' ? 'bg-blue-50 text-blue-700' : ''">
                            Persemester
                        </button>
                    </div>
                </div>
            </div>

            <div class="w-px h-8 bg-gray-200 hidden md:block"></div>

            <!-- Export Buttons -->
            <a href="{{ route('laporan.pdf', ['periode' => $periode]) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white transition-all bg-gray-900 rounded hover:bg-gray-800 shadow-lg shadow-gray-200 hover:shadow-xl">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                PDF
            </a>

            <a href="{{ route('laporan.excel', ['periode' => $periode]) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-green-700 transition-all bg-green-50 border border-green-200 rounded hover:bg-green-100 hover:border-green-300">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Excel
            </a>
        </div>
    </div>
\
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">\
        <div class="relative p-6 overflow-hidden bg-white border border-gray-100 shadow-xs rounded group hover:shadow-sm transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Peminjaman</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $totalPeminjaman }}</h3>
                </div>
            </div>
        </div>

        <div class="relative p-6 overflow-hidden bg-white border border-gray-100 shadow-xs rounded group hover:shadow-sm transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Peminjaman Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $PeminjamanHariIni }}</h3>
                </div>
            </div>
        </div>

        <div class="relative p-6 overflow-hidden bg-white border border-gray-100 shadow-xs rounded group hover:shadow-sm transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Rata-rata Durasi</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($waktuRataRata, 1) }} <span class="text-sm font-normal text-gray-500">Jam</span></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div class="flex flex-col bg-white border border-gray-100 shadow-sm rounded">
            <div class="grid items-center justify-between px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Peminjam Teratas</h2>
                <p class="text-xs text-gray-500 mt-0.5">Ranking berdasarkan frekuensi ({{ $periodeLabel }})</p>
            </div>
            <div class="p-6">
                <div class="space-y-5">
                    @forelse($peminjamTeratas as $index => $peminjam)
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <span class="flex items-center justify-center w-8 h-8 text-xs font-bold {{ $index < 3 ? 'text-blue-600 bg-blue-100' : 'text-gray-500 bg-gray-100' }} rounded-full">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $peminjam['nama'] }}</p>
                                <p class="text-xs text-gray-500">{{ $peminjam['email'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-gray-900">{{ $peminjam['jumlah'] }}</span>
                            <span class="text-xs text-gray-500">x</span>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        <p class="text-sm">Belum ada data peminjam</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="flex flex-col bg-white border border-gray-100 shadow-sm rounded">
            <div class="grid items-center justify-between px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Sarpras Terpopuler</h2>
                <p class="text-xs text-gray-500 mt-0.5">Barang paling sering dipinjam ({{ $periodeLabel }})</p>
            </div>
            <div class="p-6">
                <div class="space-y-5">
                    @forelse($sarprasTerpopuler as $index => $sarpras)
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <span class="flex items-center justify-center w-8 h-8 text-xs font-bold {{ $index < 3 ? 'text-green-600 bg-green-100' : 'text-gray-500 bg-gray-100' }} rounded-full">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 group-hover:text-green-600 transition-colors">{{ $sarpras['nama'] }}</p>
                                <p class="text-xs text-gray-500">
                                    @if($sarpras['type'] === 'ruangan')
                                        {{ $sarpras['lokasi'] ?? 'N/A' }}
                                    @elseif($sarpras['type'] === 'proyektor')
                                        {{ $sarpras['merk'] ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-700">{{ $sarpras['jumlah'] }}</span>
                            <span class="text-xs text-gray-500">x</span>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center text-gray-500">
                         <svg class="w-12 h-12 mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        <p class="text-sm">Belum ada data barang</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function laporanPage() {
        return {
            openDropdown: false,
            periode: '{{ $periode }}',

            formatLabel(val) {
                if (val === 'persemester') return 'Persemester';
                return 'Perbulan';
            },

            setPeriode(val) {
                this.periode = val;
                this.openDropdown = false;

                this.$nextTick(() => {
                    document.getElementById('filterForm').submit();
                });
            }
        }
    }
</script>
@endsection
