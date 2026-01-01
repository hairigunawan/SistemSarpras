@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.peminjaman.index') }}" class="p-2 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Peminjaman</h1>
                    <p class="text-sm text-gray-500">
                         &bull; Diajukan pada {{ \Carbon\Carbon::parse($mainPeminjaman->created_at)->format('d M Y') }}
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.peminjaman.index') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-gray-50">
                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m0 0l6-6m-6 6l6 6"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="space-y-4 mb-6">
            @if (session('success'))
            <div x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 2000)"
                x-show="show"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="flex p-4 rounded-2xl bg-emerald-50 border border-emerald-100 shadow-sm shadow-emerald-100/50">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-emerald-100 text-emerald-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-bold text-emerald-900 leading-none mb-1">Berhasil!</h3>
                    <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="ml-auto flex-shrink-0 text-emerald-400 hover:text-emerald-600 transition-colors">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </button>
            </div>
            @endif
            @if (session('warning'))
            <div x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 2000)"
                x-show="show"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-6 flex items-center p-4 text-red-700 bg-red-50/80 backdrop-blur-sm border border-red-200 rounded-xl shadow-sm transition-all"
                role="alert">
                    <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-amber-100 text-amber-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-bold text-amber-900 leading-none mb-1">Peringatan</h3>
                    <p class="text-sm text-amber-700 font-medium">{{ session('warning') }}</p>
                </div>
                <button @click="show = false" class="ml-auto flex-shrink-0 text-amber-400 hover:text-amber-600 transition-colors">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </button>
            </div>
            @endif
            @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition
                class="flex p-4 rounded-2xl bg-rose-50 border border-rose-100 shadow-sm shadow-rose-100/50">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-10 w-10 rounded-xl bg-rose-100 text-rose-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-bold text-rose-900 leading-none mb-1">Terjadi Kesalahan</h3>
                    <ul class="list-disc pl-4 text-sm text-rose-700 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="show = false" class="ml-auto flex-shrink-0 text-rose-400 hover:text-rose-600 transition-colors">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </button>
            </div>
            @endif
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-gray-500 text-sm">Status:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($mainPeminjaman->status_peminjaman == 'Menunggu') bg-yellow-100 text-yellow-800
                        @elseif($mainPeminjaman->status_peminjaman == 'Disetujui') bg-green-100 text-green-800
                        @elseif($mainPeminjaman->status_peminjaman == 'Selesai') bg-[#1180ab] text-[#1180ab]
                        @elseif($mainPeminjaman->status_peminjaman == 'Ditolak') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $mainPeminjaman->status_peminjaman == 'Menunggu' ? 'Menunggu Konfirmasi' : $mainPeminjaman->status_peminjaman }}
                    </span>

                    @if($mainPeminjaman->status_peminjaman == 'Ditolak')
                        <span class="text-xs text-red-600 italic">(Alasan: {{ $mainPeminjaman->alasan_penolakan }})</span>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @if ($mainPeminjaman->status_peminjaman == 'Menunggu')
                        @if($mainPeminjaman->tanggal_pinjam == now()->toDateString())
                            <div x-data="{ open: false }">
                            <button type="button" @click="open = true"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-semibold text-xs uppercase">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Setujui
                            </button>
                            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
                                <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full">
                                    <h2 class="text-lg font-bold text-gray-900">Konfirmasi Persetujuan</h2>
                                    <p class="mt-2 text-sm text-gray-600">Apakah Anda yakin ingin menyetujui peminjaman ini?</p>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                            Batal
                                        </button>

                                        <form action="{{ route('peminjaman.approve', $mainPeminjaman->id_peminjaman) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                                                Ya, Setujui
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                            <button type="button" onclick="showErrorMessage('Peminjaman hanya dapat disetujui pada hari peminjaman yang dijadwalkan.')"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Setujui
                            </button>
                        @endif

                        <div x-data="{ open: false, reason: '' }">
                            <button type="button" @click="open = true"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 ">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Tolak
                            </button>

                            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
                                <div class="bg-white p-6 rounded-lg shadow-xl max-w-lg w-full">
                                    <h2 class="text-lg font-bold text-gray-900">Tolak Peminjaman</h2>
                                    <p class="mt-2 text-sm text-gray-600">Silakan masukkan alasan penolakan untuk memberitahu peminjam.</p>

                                    <form action="{{ route('peminjaman.reject', $mainPeminjaman->id_peminjaman) }}" method="POST" class="mt-4">
                                        @csrf
                                        @method('PATCH')
                                        <textarea name="alasan_penolakan" x-model="reason" rows="4" required
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 p-3"
                                            placeholder="Tuliskan alasan penolakan di sini..."></textarea>

                                        <div class="mt-6 flex justify-end space-x-3">
                                            <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                                Batal
                                            </button>
                                            <button type="submit" :disabled="!reason.trim()"
                                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                                Tolak Sekarang
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @elseif ($mainPeminjaman->status_peminjaman == 'Disetujui')
                        <div x-data="{ open: false }">
                            <button type="button" @click="open = true"
                                class="inline-flex items-center px-4 py-2 bg-[#1180ab] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#1180ab] active:bg-[#1180ab] ">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Selesaikan
                            </button>

                            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
                                <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full">
                                    <h2 class="text-lg font-bold text-gray-900">Konfirmasi Penyelesaian</h2>
                                    <p class="mt-2 text-sm text-gray-600">Apakah Anda yakin ingin menyelesaikan peminjaman ini?</p>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                            Batal
                                        </button>

                                        <form action="{{ route('peminjaman.complete', $mainPeminjaman->id_peminjaman) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#1180ab] rounded-md hover:bg-[#0f7299]">
                                                Ya, Selesaikan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <a href="https://wa.me/{{ $mainPeminjaman->user->nomor_telepon ?? '-' }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>

            <div class="border-b border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">

                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Detail</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Rincian lengkap mengenai pengajuan peminjaman.</p>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Nama Peminjam</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">
                            {{ $mainPeminjaman->nama_peminjam ?? ($mainPeminjaman->user->name ?? 'N/A') }}
                            <span class="text-gray-500 font-normal ml-2">({{ $mainPeminjaman->user->email ?? '-' }})</span>
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Jenis Kegiatan / Keterangan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $mainPeminjaman->jenis_kegiatan }}</dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Sarpras yang Dipinjam</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($mainPeminjaman->ruangan && $mainPeminjaman->proyektor)
                                <span class="inline-flex items-center py-0.5 rounded-md text-sm font-medium">
                                    {{ $mainPeminjaman->ruangan->nama_ruangan }}
                                </span>
                                <span class="text-gray-400">+</span>
                                <span class="inline-flex items-center py-0.5 rounded-md text-sm font-medium">
                                    {{ $mainPeminjaman->proyektor->nama_proyektor }}
                                </span>
                            @elseif($mainPeminjaman->ruangan)
                                <span class="inline-flex items-center py-0.5 rounded-md text-sm font-medium">
                                    {{ $mainPeminjaman->ruangan->nama_ruangan }}
                                </span>
                            @elseif($mainPeminjaman->proyektor)
                                <span class="inline-flex items-center py-0.5 rounded-md text-sm font-medium">
                                    {{ $mainPeminjaman->proyektor->nama_proyektor }}
                                </span>
                            @else
                                <span class="text-gray-500 italic">Tidak ada fasilitas spesifik</span>
                            @endif
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if ($mainPeminjaman->id_ruangan_proyektor && $mainPeminjaman->ruanganProyektor)
                                <span>{{ $mainPeminjaman->ruanganProyektor->nama_ruangan ?? '-' }}</span>
                                -
                                <span>{{ $mainPeminjaman->ruanganProyektor->lokasi->nama_lokasi ?? '-' }}</span>
                            @elseif ($mainPeminjaman->id_lokasi && $mainPeminjaman->lokasi)
                                <span>{{ $mainPeminjaman->lokasi->nama_lokasi }}</span>
                            @else
                                <span class="text-gray-500 italic">Tidak ada lokasi</span>
                            @endif
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Jadwal Peminjaman</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex flex-col sm:flex-row sm:gap-8">
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide">Mulai</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($mainPeminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}</span>
                                    <span class="text-gray-600">Pukul {{ date('H:i', strtotime($mainPeminjaman->jam_mulai)) }}</span>
                                </div>
                                <div class="hidden sm:block border-l border-gray-300"></div>
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide">Selesai</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($mainPeminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}</span>
                                    <span class="text-gray-600">Pukul {{ date('H:i', strtotime($mainPeminjaman->jam_selesai)) }}</span>
                                </div>
                            </div>
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Kontak (WhatsApp)</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $mainPeminjaman->user->nomor_telepon ?? '-' }}</dd>
                    </div>

                    @if ($mainPeminjaman->status_peminjaman == 'Disetujui' || $mainPeminjaman->status_peminjaman == 'Selesai')
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                            <dt class="text-sm font-medium text-gray-500">Catatan Admin</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if($mainPeminjaman->catatan_admin)
                                    {{ $mainPeminjaman->catatan_admin }}
                                @else
                                    <span class="text-gray-500 italic">Tidak ada catatan</span>
                                @endif
                            </dd>
                        </div>
                    @endif

                </dl>
            </div>
        </div>

        @if ($mainPeminjaman->status_peminjaman == 'Disetujui')
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Berikan Catatan Admin</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Tambahkan catatan mengenai kondisi peminjaman atau hal lain yang relevan.</p>
                </div>
                <form action="{{ route('peminjaman.add_catatan_admin', $mainPeminjaman->id_peminjaman) }}" method="POST" class="p-6">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="catatan_admin" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="catatan_admin" id="catatan_admin" rows="4"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3"
                            placeholder="Misal: Proyektor berfungsi baik, ada sedikit goresan pada casing, dll.">{{ old('catatan_admin', $mainPeminjaman->catatan_admin) }}</textarea>
                        @error('catatan_admin')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#1180ab] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#1180ab] active:bg-[#1180ab]">
                            Simpan Catatan
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Konflik / Kandidat Lain (Jika Ada) -->
        @if (!empty($candidates ?? []) && count($candidates) > 1)
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-orange-200">
                <div class="px-6 py-5 border-b border-orange-100 bg-orange-50 flex items-center">
                    <div class="flex-shrink-0 bg-orange-100 rounded-full p-2">
                        <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-orange-900">Terdeteksi Konflik Jadwal</h3>
                        <p class="text-sm text-orange-700">Menyetujui peminjaman ini akan otomatis menolak peminjaman lain di bawah ini.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($candidates as $candidate)
                                <tr class="{{ $candidate->id_peminjaman == $mainPeminjaman->id_peminjaman ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $candidate->nama_peminjam ?? $candidate->user->name }}
                                        @if($candidate->id_peminjaman == $mainPeminjaman->id_peminjaman)
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#1180ab] text-[#1180ab]">Sedang Dilihat</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $candidate->ruangan->nama_ruangan ?? '' }} {{ $candidate->proyektor ? '& ' . $candidate->proyektor->nama_proyektor : '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($candidate->tanggal_pinjam)->format('d/m/Y') }} <br>
                                        {{ $candidate->jam_mulai }} - {{ $candidate->jam_selesai }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $candidate->status_peminjaman == 'Menunggu' ? 'bg-yellow-100 text-yellow-800' :
                                               ($candidate->status_peminjaman == 'Disetujui' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ $candidate->status_peminjaman }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($candidate->id_peminjaman != $mainPeminjaman->id_peminjaman)
                                            <a href="{{ route('admin.peminjaman.lihat_peminjaman', $candidate->id_peminjaman) }}" class="text-[#1180ab] hover:text-[#1180ab]">Lihat Detail</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>


@endsection
