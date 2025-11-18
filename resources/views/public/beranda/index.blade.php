@extends('layouts.guest')
@section('title', 'Sarpras - Sistem Informasi Sarana dan Prasarana')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Hero Section -->
    <div class="relative w-full h-72 md:h-[545px]">
        <!-- Background Image -->
        <img src="{{ asset('storage/images/GKT.jpg') }}"
            class="w-full h-full object-cover" alt="Sarana Prasarana">

        <!-- Overlay Gelap -->
        <div class="absolute inset-0 bg-black/60"></div>

        <!-- Teks di Tengah -->
        <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white px-4">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">
                Sistem Informasi Sarana dan Prasarana
            </h1>
            <p class="text-lg opacity-90">
                SIMPERSITE hadir sebagai solusi inovatif untuk pengelolaan peminjaman ruangan dan proyektor yang digunakan<br>oleh civitas Jurusan Teknologi Informasi.
            </p>
        </div>
    </div>


    <div class="container mx-auto px-4 md:px-6 py-8">
        <!-- Layout 2 Kolom: Kalender (1/4) + Konten (3/4) -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-12">
            <!-- Kalender Compact Modern (1/4) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-smtransition-all duration-300 sticky top-4">
                    <!-- Header Kalender -->
                    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="bg-white/20 backdrop-blur-sm p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-white">Jadwal</h3>
                                    <p class="text-blue-100 text-xs">Peminjaman</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kalender -->
                    <div class="p-3">
                        <div id="calendar" class="modern-compact-calendar"></div>
                    </div>

                    <!-- Legend -->
                    <div class="px-4 pb-4 border-t border-gray-100">
                        <div class="flex items-center justify-center gap-3 pt-3">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 shadow-sm"></div>
                                <span class="text-xs text-gray-600 font-medium">Terpakai</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 border-2 border-blue-400"></div>
                                <span class="text-xs text-gray-600 font-medium">Hari Ini</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Jurusan (3/4) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl overflow-hidden border border-gray-200 shadow-md hover:shadow-sm transition-shadow duration-300">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 md:p-8 text-white">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="hidden md:block border-l-4 border-white h-24"></div>
                                <div class="text-center md:text-left">
                                    <h2 class="text-2xl md:text-3xl font-bold mb-2">Teknologi Informasi</h2>
                                    <p class="text-blue-100 text-sm mb-2">Politeknik Negeri Tanah Laut</p>
                                    <div class="flex items-center justify-center md:justify-start text-blue-50 text-xs">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Panggung, Kec.Pelaihari, Kab.Tanah Laut, Kalimantan Selatan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats dengan Design Modern -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4 p-4 md:p-6 bg-gradient-to-br from-gray-50 to-blue-50">
                        <div class="group bg-white rounded-lg p-4 text-center shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                            <div class="text-2xl md:text-3xl font-bold bg-gradient-to-br from-blue-600 to-blue-800 bg-clip-text text-transparent group-hover:scale-110 transition-transform duration-300">
                                {{ ($RuanganTersedia ?? 0)}}
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 mt-1 font-medium">Total Ruangan</p>
                        </div>
                        <div class="group bg-white rounded-lg p-4 text-center shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                            <div class="text-2xl md:text-3xl font-bold bg-gradient-to-br from-blue-600 to-blue-800 bg-clip-text text-transparent group-hover:scale-110 transition-transform duration-300">
                                {{ ($ProyektorTersedia ?? 0) + ($ProyektorTerpakai ?? 0) + ($ProyektorPerbaikan ?? 0) }}
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 mt-1 font-medium">Total Proyektor</p>
                        </div>
                        <div class="group bg-white rounded-lg p-4 text-center shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                            <div class="text-2xl md:text-3xl font-bold bg-gradient-to-br from-green-600 to-green-800 bg-clip-text text-transparent group-hover:scale-110 transition-transform duration-300">
                                {{ $RuanganTersedia ?? 0 }}
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 mt-1 font-medium">Ruangan Tersedia</p>
                        </div>
                        <div class="group bg-white rounded-lg p-4 text-center shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                            <div class="text-2xl md:text-3xl font-bold bg-gradient-to-br from-green-600 to-green-800 bg-clip-text text-transparent group-hover:scale-110 transition-transform duration-300">
                                {{ $ProyektorTersedia ?? 0 }}
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 mt-1 font-medium">Proyektor Tersedia</p>
                        </div>
                        <div class="group bg-white rounded-lg p-4 text-center shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                            <div class="text-2xl md:text-3xl font-bold bg-gradient-to-br from-orange-600 to-red-600 bg-clip-text text-transparent group-hover:scale-110 transition-transform duration-300">
                                {{ ($RuanganTerpakai ?? 0) + ($ProyektorTerpakai ?? 0) }}
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 mt-1 font-medium">Terpakai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Catatan Penting -->
        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-[#179ACE] p-4 rounded-r-xl shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-[#179ACE] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="font-bold text-blue-900 mb-1">Informasi Penting</h4>
                    <p class="text-sm text-blue-800">
                        Sistem ini menampilkan data real-time ketersediaan sarana dan prasarana di Jurusan Teknologi Informasi.
                        Untuk peminjaman atau reservasi, silakan hubungi bagian administrasi atau gunakan sistem booking online.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/calender.js'])
@endpush

