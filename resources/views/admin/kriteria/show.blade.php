@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto my-10 px-4">

    {{-- Header & Navigasi --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Kriteria</h1>
            <p class="text-sm text-gray-500">Rincian informasi untuk kriteria terpilih.</p>
        </div>
        <a href="{{ route('admin.kriteria.index') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    {{-- Kartu Utama --}}
    <div class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden">

        {{-- Bagian Atas: Judul Kriteria --}}
        <div class="p-8 border-b border-gray-100">
            <span class="text-xs font-bold tracking-wider text-gray-400 uppercase">Nama Kriteria</span>
            <h2 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $kriteria->nama_kriteria }}</h2>
        </div>

        <div class="p-8">
            {{-- Grid Informasi Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                {{-- Card 1: Tipe Kriteria (Netral) --}}
                <div class="flex items-center p-4 rounded-lg border border-gray-200 bg-white">
                    <div class="p-3 rounded-full mr-4 bg-gray-100 text-gray-600">
                        @if($kriteria->tipe === 'benefit')
                            {{-- Ikon Naik --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        @else
                            {{-- Ikon Turun --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tipe Kriteria</p>
                        <p class="text-xl font-bold text-gray-900">
                            {{ ucfirst($kriteria->tipe) }}
                        </p>
                    </div>
                </div>

                {{-- Card 2: Bobot (Netral) --}}
                <div class="flex items-center p-4 rounded-lg border border-gray-200 bg-white">
                    <div class="p-3 rounded-full mr-4 bg-gray-100 text-gray-600">
                        {{-- Ikon Timbangan --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nilai Bobot</p>
                        <p class="text-xl font-bold text-gray-900 font-mono">
                            {{ number_format($kriteria->bobot, 4) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Metadata (Tanggal) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-100 pt-6 text-sm text-gray-500">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Dibuat: <span class="font-medium text-gray-700">{{ $kriteria->created_at->format('d M Y, H:i') }}</span></span>
                </div>
                <div class="flex items-center gap-2 md:justify-end">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Terakhir update: <span class="font-medium text-gray-700">{{ $kriteria->updated_at->format('d M Y, H:i') }}</span></span>
                </div>
            </div>

        </div>

        {{-- Footer Actions --}}
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
             <a href="{{ route('admin.kriteria.edit', $kriteria) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 active:bg-gray-900 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Kriteria
            </a>
        </div>
    </div>
</div>
@endsection
