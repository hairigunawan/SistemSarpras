@extends('layouts.guest')

@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Riwayat Peminjaman</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Kelola dan pantau status pengajuan peminjaman fasilitas Anda.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">

                <!-- Tombol Download Dropdown -->
                <div x-data="{ open: false }" class="relative inline-block text-left">
                    <div>
                        <button type="button" @click="open = !open" class="inline-flex items-center justify-center rounded-lg bg-white border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-600 transition-all" id="menu-button" aria-expanded="true" aria-haspopup="true">
                            <svg class="-ml-0.5 mr-2 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Riwayat
                            <svg class="-mr-1 h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" style="display: none;">
                        <div class="py-1" role="none">
                            <a href="{{ route('public.peminjaman.download_pdf') }}" class="text-slate-700 block px-4 py-2 text-sm hover:bg-slate-100" role="menuitem">Download PDF</a>
                            <a href="{{ route('public.peminjaman.export_excel') }}" class="text-slate-700 block px-4 py-2 text-sm hover:bg-slate-100" role="menuitem">Download Excel</a>
                        </div>
                    </div>
                </div>

                <!-- Tombol Print -->
                <a href="{{ route('public.peminjaman.print') }}" target="_blank"
                   class="inline-flex items-center justify-center rounded-lg bg-white border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-600 transition-all">
                    <svg class="-ml-0.5 mr-2 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Print Riwayat
                </a>

                <!-- Tombol Ajukan -->
                <a href="{{ route('public.peminjaman.create') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Ajukan Peminjaman
                </a>
            </div>
        </div>

        <!-- Info Count (Diluar Loop) -->
        @if($peminjaman->count() > 0)
        <div class="mb-4">
            <p class="text-xs text-slate-400">
                Menampilkan {{ count($peminjaman) }} data riwayat peminjaman Anda.
            </p>
        </div>
        @endif

        <!-- Table Container (Diluar Loop) -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Data Peminjam</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Sarana / Prasarana</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Peminjaman</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keperluan</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">

                        <!-- Loop Hanya Pada Baris (TR) -->
                        @forelse($peminjaman as $item)
                        <tr class="hover:bg-slate-50 transition-colors duration-200 group">

                            <!-- No -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $loop->iteration }}
                            </td>

                            <!-- Data Peminjam -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-normal text-slate-900">
                                        {{ $item->nama_peminjam }}
                                    </span>
                                    <span class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                        {{ $item->user->email ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Sarana / Prasarana -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <div class="font-medium text-slate-900 text-sm">
                                            {{ $item->ruangan->nama_ruangan ?? $item->proyektor->nama_proyektor ?? '-' }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $item->proyektor->merk ?? $item->ruangan->lokasi->nama_lokasi ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Waktu -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <div class="text-sm font-medium text-slate-700">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }} WIB
                                    </div>
                                </div>
                            </td>

                            <!-- Keperluan -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600 max-w-xs truncate" title="{{ $item->jenis_kegiatan }}">
                                    {{ $item->jenis_kegiatan }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusConfig = [
                                        'Disetujui' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'ring' => 'ring-emerald-600/20'],
                                        'Selesai'   => ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'ring' => 'ring-blue-700/10'],
                                        'Ditolak'   => ['bg' => 'bg-red-50',     'text' => 'text-red-700',     'ring' => 'ring-red-600/10'],
                                        'Menunggu'  => ['bg' => 'bg-yellow-50',  'text' => 'text-yellow-700',  'ring' => 'ring-yellow-600/10'],
                                    ];
                                    $config = $statusConfig[$item->status_peminjaman] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'ring' => 'ring-slate-500/10'];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} ring-1 ring-inset {{ $config['ring'] }}">
                                    {{ $item->status_peminjaman }}
                                </span>
                            </td>
                        </tr>

                        <!-- Empty State (Jika Data Kosong) -->
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 mb-4">
                                    <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-slate-900">Belum ada riwayat</h3>
                                <p class="mt-1 text-sm text-slate-500">Anda belum memiliki riwayat peminjaman sarana atau prasarana.</p>
                                <div class="mt-6">
                                    <a href="{{ route('public.peminjaman.create') }}" class="text-blue-600 hover:text-blue-500 text-sm font-semibold hover:underline">
                                        Buat Pengajuan Baru &rarr;
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End Table Container -->

    </div>
</div>
@endsection
