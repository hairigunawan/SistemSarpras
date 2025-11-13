@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Kartu Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Akun -->
        <div class="bg-gradient-to-br bg-gray-800 p-4 rounded-xl shadow-lg transform transition-transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Akun</p>
                    <p class="text-3xl text-white font-bold mt-2">{{ $jumlah_akun ?? 0 }}</p>
                </div>
                <div class="bg-blue-700 bg-opacity-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Sarpras -->
        <div class="bg-gradient-to-br bg-gray-800 p-4 rounded-xl shadow-lg transform transition-transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Sarpras</p>
                    <p class="text-3xl text-white font-bold mt-2">{{ $jumlah_sarpras ?? 0 }}</p>
                </div>
                <div class="bg-purple-700 bg-opacity-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Peminjaman Menunggu -->
        <a href="{{ route('admin.peminjaman.index', ['status' => 'Menunggu']) }}" class="block">
            <div class="bg-gradient-to-br bg-gray-800 p-4 rounded-xl shadow-lg transform transition-transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-sm font-medium">Peminjaman Menunggu</p>
                        <p class="text-3xl text-white font-bold mt-2">{{ $peminjaman_menunggu ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-600 bg-opacity-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- Peminjaman Disetujui -->
        <a href="{{ route('admin.peminjaman.index', ['status' => 'disetujui']) }}" class="block">
            <div class="bg-gradient-to-br bg-gray-800 p-4 rounded-xl shadow-lg transform transition-transform hover:scale-105">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Peminjaman Disetujui</p>
                        <p class="text-3xl text-white font-bold mt-2">{{ $peminjaman_disetujui ?? 0 }}</p>
                    </div>
                    <div class="bg-green-700 bg-opacity-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Grafik Statistik -->
    <div class="bg-white p-4 border rounded-xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Statistik Peminjaman</h3>
            <div class="flex gap-2">
                <div>
                    <p class="w-8 h-1 bg-blue-500"></p>
                    <p class="text-xs">Ruangan</p>
                </div>
                <div>
                    <p class="w-8 h-1 bg-green-500"></p>
                    <p class="text-xs">Proyektor</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <button class="px-3 py-1 text-sm bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">Minggu</button>
                <button class="px-3 py-1 text-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">Bulan</button>
                <button class="px-3 py-1 text-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">Tahun</button>
            </div>
        </div>

        <!-- Grafik Sederhana -->
        <div>
            <div class="max-h-96 h-96 flex items-end justify-between space-x-3">
                <div class="">
                    <div class="flex flex-col items-center flex-2">
                        <span class="bg-blue-500 rounded-t-lg" style="height: 60%"></span>
                        <span class="bg-green-500 rounded-t-lg" style="height: 70%"></span>
                    </div>
                    <p>Senin</p>
                </div>
                <div class="">
                    <div class="flex flex-col items-center flex-2">
                        <span class="bg-blue-500 rounded-t-lg" style="height: 60%"></span>
                        <span class="bg-green-500 rounded-t-lg" style="height: 70%"></span>
                    </div>
                    <p>Selasa</p>
                </div>
                <div class="">
                    <div class="flex flex-col items-center flex-2">
                        <span class="bg-blue-500 rounded-t-lg" style="height: 60%"></span>
                        <span class="bg-green-500 rounded-t-lg" style="height: 70%"></span>
                    </div>
                    <p>Rabu</p>
                </div>
                <div class="">
                    <div class="flex flex-col items-center flex-2">
                        <span class="bg-blue-500 rounded-t-lg" style="height: 60%"></span>
                        <span class="bg-green-500 rounded-t-lg" style="height: 70%"></span>
                    </div>
                    <p>Kamis</p>
                </div>
                <div class="">
                    <div class="flex flex-col items-center flex-2">
                        <span class="bg-blue-500 rounded-t-lg" style="height: 60%"></span>
                        <span class="bg-green-500 rounded-t-lg" style="height: 70%"></span>
                    </div>
                    <p>Jumat</p>
                </div>
                <div class="">
                    <div class="flex flex-col items-center flex-2">
                        <span class="bg-blue-500 rounded-t-lg" style="height: 60%"></span>
                        <span class="bg-green-500 rounded-t-lg" style="height: 70%"></span>
                    </div>
                    <p>Sabtu</p>
                </div>
                <div class="">
                    <div class="flex flex-col items-center flex-2">
                        <span class="bg-blue-500 rounded-t-lg" style="height: 60%"></span>
                        <span class="bg-green-500 rounded-t-lg" style="height: 70%"></span>
                    </div>
                    <p>Minggu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Aksi Cepat -->
    <div class="border p-4 rounded-xl bg-gray-700">
        <h3 class="text-lg font-normal text-gray-100 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Tambah Ruangan -->
            <a href="{{ route('sarpras.ruangan.tambah_ruangan') }}" class="group">
                <div class="bg-white p-4 rounded-xl hover:border transition-all duration-300 border border-gray-100 group-hover:border-blue-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-blue-100 p-4 rounded-full group-hover:bg-blue-200 transition-colors mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-medium">Tambah Ruangan</h3>
                    </div>
                </div>
            </a>

            <!-- Tambah Proyektor -->
            <a href="{{ route('sarpras.proyektor.tambah_proyektor') }}" class="group">
                <div class="bg-white p-4 rounded-xl hover:border transition-all duration-300 border border-gray-100 group-hover:border-purple-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-purple-100 p-4 rounded-full group-hover:bg-purple-200 transition-colors mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-medium">Tambah Proyektor</h3>
                    </div>
                </div>
            </a>

            <!-- Laporan PDF -->
            <a href="{{ route('laporan.pdf') }}" class="group">
                <div class="bg-white p-4 rounded-xl hover:border transition-all duration-300 border border-gray-100 group-hover:border-red-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-red-100 p-4 rounded-full group-hover:bg-red-200 transition-colors mb-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-medium">Laporan PDF</h3>
                    </div>
                </div>
            </a>

            <!-- Laporan Excel -->
            <a href="{{ route('laporan.excel') }}" class="group">
                <div class="bg-white p-4 rounded-xl hover:border transition-all duration-300 border border-gray-100 group-hover:border-green-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-green-100 p-4 rounded-full group-hover:bg-green-200 transition-colors mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v1a1 1 0 001 1h4a1 1 0 001-1v-1m3-2V8a2 2 0 00-2-2H8a2 2 0 00-2 2v6m9-5h-6a2 2 0 100 4h6a2 2 0 100-4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-medium">Laporan Excel</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
