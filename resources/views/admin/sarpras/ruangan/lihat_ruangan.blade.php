@extends('layouts.app')

@section('title', 'Detail Ruangan')

@section('content')
<section class="min-h-screen bg-gray-50 py-10">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">{{ $ruangan->nama_ruangan }}</h1>
                <p class="mt-1 text-sm text-gray-500">Ruangan</p>
            </div>
            <a href="{{ route('admin.sarpras.index') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-gray-50 active:bg-gray-100">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m0 0l6-6m-6 6l6 6"/>
                </svg>
                Kembali
            </a>
        </div>

        {{-- Card --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-gray-200">
            <div class="grid gap-6 p-6 sm:grid-cols-3 lg:grid-cols-4 lg:p-8">

                {{-- Gambar --}}
                <div class="sm:col-span-1 lg:col-span-2">
                    @if($ruangan->gambar)
                        <img src="{{ asset('storage/' . $ruangan->gambar) }}"
                             alt="{{ $ruangan->nama_ruangan }}"
                             class="aspect-video w-full rounded-xl object-cover shadow-md">
                    @else
                        <div class="flex aspect-video w-full items-center justify-center rounded-xl bg-gray-100">
                            <span class="text-gray-400">Tidak ada gambar</span>
                        </div>
                    @endif
                </div>

                {{-- Detail --}}
                <div class="sm:col-span-2 lg:col-span-2">
                    <div class="space-y-5">

                        {{-- Status badge --}}
                        <div>
                            @php
                                $color = $ruangan->status->nama_status === 'Tersedia'
                                    ? 'bg-green-100 text-green-700'
                                    : ($ruangan->status->nama_status === 'Dipinjam'
                                        ? 'bg-yellow-100 text-yellow-700'
                                        : 'bg-red-100 text-red-700');
                            @endphp
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $color }}">
                                {{ $ruangan->status->nama_status }}
                            </span>
                        </div>

                        {{-- Field-field dinamis --}}
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-xs font-medium text-gray-500">Lokasi</p>
                                <p class="mt-1 text-gray-900">{{ $ruangan->lokasi->nama_lokasi ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium text-gray-500">Kapasitas</p>
                                <p class="mt-1 text-gray-900">{{ $ruangan->kapasitas }} Orang</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium text-gray-500">Kode Ruangan</p>
                                <p class="mt-1 font-mono text-sm text-gray-900">{{ $ruangan->kode_ruangan }}</p>
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="flex items-center gap-3 pt-4">
                            <a href="{{ route('sarpras.ruangan.edit_ruangan', $ruangan->id_ruangan) }}"
                               class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 active:bg-indigo-700">
                                Edit
                            </a>

                            <form action="{{ route('sarpras.ruangan.destroy', $ruangan->id_ruangan) }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-red-500 active:bg-red-700">
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
