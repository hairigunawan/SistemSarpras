@extends('layouts.guest')

@section('title', 'Form Peminjaman Sarpras')

@section('content')

@php
    $defRuanganId = old('id_ruangan', ($selectedSarprasType == 'ruangan' ? $selectedSarprasId : ''));
    $defRuanganLabel = '';
    if($defRuanganId) {
        $found = $ruanganTersedia->firstWhere('id_ruangan', $defRuanganId);
        $defRuanganLabel = $found ? $found->nama_ruangan : '';
    }

    $defProyektorId = old('id_proyektor', ($selectedSarprasType == 'proyektor' ? $selectedSarprasId : ''));
    $defProyektorLabel = '';
    if($defProyektorId) {
        $found = $proyektorTersedia->firstWhere('id_proyektor', $defProyektorId);
        $defProyektorLabel = $found ? $found->nama_proyektor : '';
    }
    $defLokasiId = old('id_lokasi');
    $defLokasiLabel = $defLokasiId && isset($lokasiList[$defLokasiId]) ? $lokasiList[$defLokasiId] : '';

    $defRuanganProyektorId = old('id_ruangan_proyektor');
    $defRuanganProyektorLabel = '';
    if($defRuanganProyektorId) {
        $found = $allRuangan->firstWhere('id_ruangan', $defRuanganProyektorId);
        $defRuanganProyektorLabel = $found ? $found->nama_ruangan : '';
    }

    $defKegiatan = old('jenis_kegiatan');
@endphp

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-700 tracking-tight sm:text-4xl">
                Formulir Peminjaman
            </h1>
            <p class="mt-3 max-w-2xl mx-auto text-lg text-gray-500">
                Lengkapi detail di bawah untuk mengajukan peminjaman sarana & prasarana.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden"
             x-data="peminjamanForm({
                ruanganId: '{{ $defRuanganId }}',
                ruanganLabel: '{{ $defRuanganLabel }}',
                proyektorId: '{{ $defProyektorId }}',
                proyektorLabel: '{{ $defProyektorLabel }}',
                lokasiId: '{{ $defLokasiId }}',
                lokasiLabel: '{{ $defLokasiLabel }}',
                ruanganProyektorId: '{{ $defRuanganProyektorId }}',
                ruanganProyektorLabel: '{{ $defRuanganProyektorLabel }}',
                kegiatan: '{{ $defKegiatan }}'
             })">

            <form action="{{ route('public.peminjaman.store') }}" method="POST" class="divide-y divide-gray-100">
                @csrf

                <div class="px-8 py-8 bg-blue-50/30">
                    <div class="flex items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-700">Informasi Peminjam</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="relative group">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Nama Lengkap</label>
                            <div class="flex items-center text-sm px-4 py-2 bg-white border border-gray-200 rounded text-gray-700 font-normal">
                                {{ Auth::check() ? (Auth::user()->name ?? Auth::user()->nama) : '-' }}
                            </div>
                            <input type="hidden" name="nama_peminjam" value="{{ Auth::check() ? (Auth::user()->name ?? Auth::user()->nama) : '' }}">
                        </div>

                        <div class="relative group">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Email</label>
                            <div class="flex items-center text-sm px-4 py-2 bg-white border border-gray-200 rounded text-gray-700 font-normal">
                                {{ Auth::check() ? Auth::user()->email : '-' }}
                            </div>
                            <input type="hidden" name="email" value="{{ Auth::check() ? Auth::user()->email : '' }}">
                        </div>

                        <div class="relative group">
                            <label class="block text-sm font-medium text-gray-500 mb-2">WhatsApp</label>
                            <div class="flex items-center text-sm px-4 py-2 bg-white border border-gray-200 rounded text-gray-700 font-normal">
                                {{ Auth::check() ? Auth::user()->nomor_telepon : '-' }}
                            </div>
                            <input type="hidden" name="nomor_telepon" value="{{ Auth::check() ? Auth::user()->nomor_telepon : '' }}">
                        </div>
                    </div>
                </div>

                <div class="px-8 py-8">
                    <div class="flex items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-700">Detail Sarpras & Waktu</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-8">

                        <div class="md:col-span-2">
                            <label class="block font-semibold text-gray-700 mb-4">Pilih Item Peminjaman</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div class="relative" x-data="{ open: false }">
                                    <label class="block text-sm font-medium text-gray-600 mb-2">Ruangan</label>
                                    <input type="hidden" name="id_ruangan" x-model="form.ruanganId">

                                    <button type="button" @click="open = !open" @click.outside="open = false"
                                        class="relative w-full bg-white border border-gray-300 rounded pl-4 pr-10 py-2 text-left cursor-pointer sm:text-sm shadow-sm hover:border-blue-400 transition-colors duration-200">
                                        <span class="block truncate"
                                            :class="!form.ruanganId ? 'text-gray-400' : 'text-gray-900'"
                                            x-text="form.ruanganLabel || 'Pilih Ruangan'">
                                        </span>
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>

                                    <ul x-show="open" x-cloak
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        class="absolute z-20 mt-1 w-full bg-white shadow-lg max-h-60 rounded-xl py-1 text-base ring-1 sm:text-sm overflow-auto">

                                        <li class="text-gray-500 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-50"
                                            @click="selectRuangan('', ''); open = false">
                                            <span class="font-normal block truncate italic">Pilih Ruangan</span>
                                        </li>

                                        @foreach($ruanganTersedia as $ruangan)
                                            <li class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50 transition-colors"
                                                @click="selectRuangan('{{ $ruangan->id_ruangan }}', '{{ $ruangan->nama_ruangan }}'); open = false">
                                                <span class="block truncate" :class="form.ruanganId == '{{ $ruangan->id_ruangan }}' ? 'font-semibold text-[#1180ab]' : 'font-normal'">
                                                    {{ $ruangan->nama_ruangan }}
                                                </span>
                                                <span x-show="form.ruanganId == '{{ $ruangan->id_ruangan }}'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#1180ab]">
                                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @error('id_ruangan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="relative" x-data="{ open: false }">
                                    <label class="block text-sm font-medium text-gray-600 mb-2">Proyektor</label>
                                    <input type="hidden" name="id_proyektor" x-model="form.proyektorId">

                                    <button type="button" @click="open = !open" @click.outside="open = false"
                                        class="relative w-full bg-white border border-gray-300 rounded pl-4 pr-10 py-2 text-left cursor-pointer sm:text-sm shadow-sm hover:border-blue-400 transition-colors duration-200">
                                        <span class="block truncate"
                                            :class="!form.proyektorId ? 'text-gray-400' : 'text-gray-900'"
                                            x-text="form.proyektorLabel || 'Pilih Proyektor'">
                                        </span>
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>

                                    <ul x-show="open" x-cloak
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        class="absolute z-20 mt-1 w-full bg-white shadow-lg max-h-60 rounded-xl py-1 text-base ring-1 sm:text-sm overflow-auto">

                                        <li class="text-gray-500 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-50"
                                            @click="selectProyektor('', ''); open = false">
                                            <span class="font-normal block truncate italic">Pilih Proyektor</span>
                                        </li>

                                        @foreach($proyektorTersedia as $proyektor)
                                            <li class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50 transition-colors"
                                                @click="selectProyektor('{{ $proyektor->id_proyektor }}', '{{ $proyektor->nama_proyektor }}'); open = false">
                                                <span class="block truncate" :class="form.proyektorId == '{{ $proyektor->id_proyektor }}' ? 'font-semibold text-gray-600' : 'font-normal'">
                                                    {{ $proyektor->nama_proyektor }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @error('id_proyektor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div x-show="form.ruanganId || form.proyektorId" x-transition.opacity
                                class="mt-4 p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-center space-x-3 text-sm text-gray-600">
                                <svg class="w-5 h-5 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>
                                    Anda akan meminjam:
                                    <span x-show="form.ruanganId" class="font-bold" x-text="form.ruanganLabel"></span>
                                    <span x-show="form.ruanganId && form.proyektorId"> & </span>
                                    <span x-show="form.proyektorId" class="font-bold" x-text="form.proyektorLabel"></span>
                                </span>
                            </div>
                        </div>

                        <div x-show="isHanyaProyektor" x-cloak
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="md:col-span-2 border-t border-dashed border-gray-200 pt-6 mt-2">

                            <div class="flex items-center mb-4">
                                <h3 class="font-semibold text-gray-700">Detail Lokasi Penggunaan Proyektor</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div x-data="{ open: false }" class="relative">
                                    <label class="block text-sm font-medium text-gray-600 mb-2">Lokasi</label>
                                    <input type="hidden" name="id_lokasi" x-model="form.lokasiId">

                                    <button type="button" @click="open = !open" @click.outside="open = false"
                                        class="relative w-full bg-white border border-gray-300 rounded pl-4 pr-10 py-2 text-left cursor-pointer sm:text-sm shadow-sm transition-colors">
                                        <span class="block truncate" :class="!form.lokasiId ? 'text-gray-400' : 'text-gray-900'" x-text="form.lokasiLabel || 'Pilih Lokasi'"></span>
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                        </span>
                                    </button>

                                    <ul x-show="open" x-cloak
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-xl py-1 text-base ring-1 sm:text-sm">
                                        @foreach($lokasiList as $id => $lokasi)
                                            <li class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50"
                                                @click="form.lokasiId = '{{ $id }}'; form.lokasiLabel = '{{ $lokasi }}'; open = false">
                                                <span class="block truncate" :class="form.lokasiId == '{{ $id }}' ? 'font-semibold text-gray-600' : 'font-normal'">{{ $lokasi }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @error('id_lokasi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div x-data="{ open: false }" class="relative">
                                    <label class="block text-sm font-medium text-gray-600 mb-2">Detail Ruangan</label>
                                    <input type="hidden" name="id_ruangan_proyektor" x-model="form.ruanganProyektorId">

                                    <button type="button" @click="open = !open" @click.outside="open = false"
                                        class="relative w-full bg-white border border-gray-300 rounded pl-4 pr-10 py-2 text-left cursor-pointer sm:text-sm shadow-sm transition-colors">
                                        <span class="block truncate" :class="!form.ruanganProyektorId ? 'text-gray-400' : 'text-gray-900'" x-text="form.ruanganProyektorLabel || 'Pilih Ruangan'"></span>
                                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                        </span>
                                    </button>

                                    <ul x-show="open" x-cloak
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-xl py-1 text-base ring-1 sm:text-sm">
                                        <li class="text-gray-500 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-50"
                                            @click="form.ruanganProyektorId = ''; form.ruanganProyektorLabel = ''; open = false">
                                        </li>
                                        @foreach($allRuangan as $ruangan)
                                            <li class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50"
                                                @click="form.ruanganProyektorId = '{{ $ruangan->id_ruangan }}'; form.ruanganProyektorLabel = '{{ $ruangan->nama_ruangan }}'; open = false">
                                                <span class="block truncate" :class="form.ruanganProyektorId == '{{ $ruangan->id_ruangan }}' ? 'font-semibold text-gray-600' : 'font-normal'">{{ $ruangan->nama_ruangan }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Peminjaman</label>
                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}"
                                class="w-full border-gray-300 py-2 px-3 text-sm border rounded" required>
                            @error('tanggal_pinjam') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="jumlah_peserta" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Peserta</label>
                            <div class="relative rounded-xl shadow-sm">
                                <input type="number" name="jumlah_peserta" id="jumlah_peserta" value="{{ old('jumlah_peserta') }}"
                                    class="w-full border-gray-300 py-2 px-3 text-sm border rounded"
                                    placeholder="0" min="1" required>
                            </div>
                            @error('jumlah_peserta') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Peminjaman</label>
                            <div class="flex items-center space-x-4">
                                <div class="flex-1 relative">
                                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" class="w-full border-gray-300 py-2 px-3 text-sm border rounded text-center">
                                    <span class="absolute left-3 top-3 text-gray-400 text-xs pointer-events-none">Mulai</span>
                                </div>
                                <span class="text-gray-700 font-normal text-sm">s/d</span>
                                <div class="flex-1 relative">
                                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" class="w-full border-gray-300 py-2 px-3 text-sm border rounded text-center">
                                    <span class="absolute left-3 top-3 text-gray-400 text-xs pointer-events-none">Selesai</span>
                                </div>
                            </div>
                            <div class="flex space-x-4 mt-1">
                                <div class="flex-1">@error('jam_mulai') <p class="text-xs text-red-600">{{ $message }}</p> @enderror</div>
                                <div class="flex-1">@error('jam_selesai') <p class="text-xs text-red-600">{{ $message }}</p> @enderror</div>
                            </div>
                        </div>

                        <div class="relative md:col-span-2" x-data="{ open: false }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kegiatan</label>
                            <input type="hidden" name="jenis_kegiatan" x-model="form.kegiatan" class="w-full border-gray-300 py-3 px-3 rounded-xl shadow-sm" required>

                            <button type="button" @click="open = !open" @click.outside="open = false"
                                class="relative w-full border-gray-300 py-2 px-3 text-sm bg-white border rounded pl-4 pr-10 text-left cursor-pointer sm:text-sm shadow-sm hover:border-blue-400 transition-colors">
                                <span class="block truncate" :class="!form.kegiatan ? 'text-gray-400' : 'text-gray-900'" x-text="form.kegiatan || 'Pilih Jenis Kegiatan'"></span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </span>
                            </button>

                            <ul x-show="open" x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                class="absolute z-50 bottom-full mb-1 w-full bg-white shadow-xl max-h-60 rounded-xl py-1 text-base ring-1 sm:text-sm origin-bottom border border-gray-100">

                                @foreach(['Seminar Tugas Akhir', 'Seminar PKL', 'Kelas Materi', 'Kelas Praktikum', 'Rapat Organisasi', 'Lainnya'] as $item)
                                    <li class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50 transition-colors"
                                        @click="form.kegiatan = '{{ $item }}'; open = false">
                                        <span class="block truncate" :class="form.kegiatan == '{{ $item }}' ? 'font-semibold text-[#1180ab]' : 'font-normal'">{{ $item }}</span>

                                        <span x-show="form.kegiatan == '{{ $item }}'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#1180ab]">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            @error('jenis_kegiatan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>

                <div class="px-8 py-6 bg-gray-50 flex items-center justify-end space-x-4 border-t border-gray-100">
                    <a href="{{ route('public.peminjaman.daftarpeminjaman') }}"
                        class="px-5 py-2 bg-white border border-gray-300 rounded-xl text-sm text-gray-700 font-medium hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-8 py-2 bg-gradient-to-r from-[#1180ab] to-indigo-600 hover:from-[#0d7198] hover:to-indigo-700 text-white font-medium text-sm rounded-xl shadow-lg transform transition-all duration-200 hover:-translate-y-0.2">
                        Ajukan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('peminjamanForm', (initialData) => ({
            form: {
                ruanganId: initialData.ruanganId,
                ruanganLabel: initialData.ruanganLabel,
                proyektorId: initialData.proyektorId,
                proyektorLabel: initialData.proyektorLabel,
                lokasiId: initialData.lokasiId,
                lokasiLabel: initialData.lokasiLabel,
                ruanganProyektorId: initialData.ruanganProyektorId,
                ruanganProyektorLabel: initialData.ruanganProyektorLabel,
                kegiatan: initialData.kegiatan
            },

            get isHanyaProyektor() {
                return this.form.proyektorId !== '' && this.form.ruanganId === '';
            },

            selectRuangan(id, label) {
                this.form.ruanganId = id;
                this.form.ruanganLabel = label;

                if (id !== '') {
                    this.form.lokasiId = '';
                    this.form.lokasiLabel = '';
                }
            },

            selectProyektor(id, label) {
                this.form.proyektorId = id;
                this.form.proyektorLabel = label;
            }
        }));
    });
</script>

<style>
    /* Utility class agar elemen tidak berkedip saat loading AlpineJS */
    [x-cloak] { display: none !important; }
</style>
@endsection
