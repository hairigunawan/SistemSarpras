
@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="max-w-full mx-auto bg-white rounded-xl shadow-md overflow-hidden">
    <!-- Header with Actions -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex flex-wrap justify-between items-center">
            <a href="{{ route('admin.peminjaman.index') }}" class="flex gap-2 text-xl items-center text-gray-800 font-semibold mb-2 sm:mb-0 hover:text-indigo-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="text-indigo-600">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 18l-6-6l6-6"/>
                </svg>
                <span>Detail Peminjaman</span>
            </a>
            <div class="flex flex-wrap items-center gap-2">
                    @if ($mainPeminjaman->status_peminjaman == 'Menunggu')
                    <form action="{{ route('peminjaman.approve', $mainPeminjaman->id_peminjaman) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini?')"
                            class="flex items-center gap-2 px-4 py-2 text-sm bg-green-100 text-green-700 font-medium rounded-lg hover:bg-green-200 transition border border-green-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                                <path fill="currentColor" d="m9.55 18l-5.7-5.7l1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4L9.55 18Z"/>
                            </svg>
                            <span>Setujui</span>
                        </button>
                    </form>
                    <a href="{{ route('peminjaman.reject.form', $mainPeminjaman->id_peminjaman) }}"
                       class="flex items-center gap-2 px-4 py-2 text-sm bg-red-100 text-red-700 font-medium rounded-lg hover:bg-red-200 transition border border-red-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
                        </svg>
                        <span>Tolak</span>
                    </a>
                @elseif ($mainPeminjaman->status_peminjaman == 'Disetujui')
                    <form action="{{ route('peminjaman.complete', $mainPeminjaman->id_peminjaman) }}" method="POST">
                        @csrf
                        <button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin menyelesaikan peminjaman ini?')"
                            class="flex items-center gap-2 px-4 py-2 text-sm bg-indigo-100 text-indigo-700 font-medium rounded-lg hover:bg-indigo-200 transition border border-indigo-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m9 11l3 3L22 4m-2 10v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9" />
                            </svg>
                            <span>Selesaikan</span>
                        </button>
                    </form>
                @endif

                <a href="https://wa.me/{{ $mainPeminjaman->user->telepon ?? '' }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white font-medium text-sm rounded-lg hover:bg-green-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19.05 4.95A9.9 9.9 0 0 0 12 2a9.9 9.9 0 0 0-7.05 2.95a9.9 9.9 0 0 0-2.95 7.05A9.9 9.9 0 0 0 12 22a9.9 9.9 0 0 0 7.05-2.95A9.9 9.9 0 0 0 22 12a9.9 9.9 0 0 0-2.95-7.05M16.5 15.3c-.25.5-.85.95-1.4 1.1s-1.1.2-1.7-.15s-1.2-.8-1.7-1.35c-.5-.55-1-1.15-1.35-1.7s-.4-1.15-.15-1.7s.6-1.15 1.1-1.4s.95-.2 1.45 0l.6.35l.2.35c.25.65.15 1.4-.3 1.85l-.5.55c-.1.1-.1.25 0 .35s.2.2.35.35l1.05 1.05c.1.1.25.1.35 0l.55-.5c.45-.45 1.2-.55 1.85-.3l.35.2l.35.6c.2.5 0 1.05-.15 1.3Z" />
                    </svg>
                    <span>Hubungi</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $mainPeminjaman->nama_peminjam ?? $mainPeminjaman->user->name ?? 'N/A' }}</h2>
                <p class="text-gray-600">{{ $mainPeminjaman->user->email ?? 'Tidak diketahui' }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-medium
                @if($mainPeminjaman->status_peminjaman == 'Menunggu')
                    bg-yellow-100 text-yellow-800
                @elseif($mainPeminjaman->status_peminjaman == 'Disetujui')
                    bg-green-100 text-green-800
                @elseif($mainPeminjaman->status_peminjaman == 'Selesai')
                    bg-blue-100 text-blue-800
                @elseif($mainPeminjaman->status_peminjaman == 'Ditolak')
                    bg-red-100 text-red-800
                @else
                    bg-gray-100 text-gray-800
                @endif">
                {{ $mainPeminjaman->status_peminjaman == 'Menunggu' ? 'Menunggu Konfirmasi' : $mainPeminjaman->status_peminjaman }}
            </span>
        </div>
    </div>

    <!-- Informasi Peminjaman -->
    <div class="px-6 py-2">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Peminjaman</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-500">Tanggal Pengajuan</span>
                    <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($mainPeminjaman->created_at)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-500">Jadwal Pinjam</span>
                    <span class="font-medium text-gray-700 text-right">
                        {{ \Carbon\Carbon::parse($mainPeminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}<br>
                        {{ date('H:i', strtotime($mainPeminjaman->jam_mulai)) }}
                    </span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-500">Jadwal Kembali</span>
                    <span class="font-medium text-gray-700 text-right">
                        {{ \Carbon\Carbon::parse($mainPeminjaman->tanggal_kembali)->translatedFormat('d F Y') }}<br>
                        {{ date('H:i', strtotime($mainPeminjaman->jam_selesai)) }}
                    </span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-start pb-3 border-b border-gray-100">
                    <span class="text-gray-500">Keterangan</span>
                    <span class="font-medium text-gray-700 text-right max-w-xs">
                        {{ $mainPeminjaman->jenis_kegiatan }}
                    </span>
                </div>
                <div class="flex justify-between items-start pb-3 border-b border-gray-100">
                    <span class="text-gray-500">Sarana dan Perasarana</span>
                    <span class="font-medium text-gray-700 text-right max-w-xs">
                        @if($mainPeminjaman->ruangan && $mainPeminjaman->proyektor)
                            {{ $mainPeminjaman->ruangan->nama_ruangan }} & {{ $mainPeminjaman->proyektor->nama_proyektor }}
                        @elseif($mainPeminjaman->ruangan)
                            {{ $mainPeminjaman->ruangan->nama_ruangan }}
                        @elseif($mainPeminjaman->proyektor)
                            {{ $mainPeminjaman->proyektor->nama_proyektor }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-start pb-3 border-b border-gray-100">
                    <span class="text-gray-500">Lokasi</span>
                    <span class="font-medium text-gray-700 text-right max-w-xs">
                        {{ ($mainPeminjaman->ruangan->lokasi->nama_lokasi ?? '-' ) }}
                    </span>
                </div>
                <div class="flex justify-between items-start pb-3 border-b border-gray-100">
                    <span class="text-gray-500">Alasan Penolakan</span>
                    <span class="font-medium text-gray-700 text-right max-w-xs">
                        {{ $mainPeminjaman->alasan_penolakan ?? '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Peminjam -->
    <div class="px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Peminjam</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-500">Nama Lengkap</span>
                    <span class="font-medium text-gray-700">{{ $mainPeminjaman->nama_peminjam ?? $mainPeminjaman->user->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-500">Email</span>
                    <span class="font-medium text-gray-700">{{ $mainPeminjaman->user->email ?? 'Tidak diketahui' }}</span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-500">Nomor WhatsApp</span>
                    <span class="font-medium text-gray-700">{{ $mainPeminjaman->nomor_whatsapp }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Peminjaman Konflik -->
    @if (!empty($candidates ?? []) && count($candidates) > 1)
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Peminjaman Konfik Lainnya</h3>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-orange-800">
                        <strong>Informasi:</strong> Saat menyetujui peminjaman ini, sistem akan secara otomatis menolak peminjaman lain yang konflik dengan sumber daya dan waktu yang sama.
                    </p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber Daya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($candidates as $index => $candidate)
                        <tr class="hover:bg-gray-50 {{ $candidate->id_peminjaman == $mainPeminjaman->id_peminjaman ? 'bg-blue-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $candidate->nama_peminjam ?? $candidate->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($candidate->ruangan && $candidate->proyektor)
                                    {{ $candidate->ruangan->nama_ruangan }} & {{ $candidate->proyektor->nama_proyektor }}
                                @elseif($candidate->ruangan)
                                    {{ $candidate->ruangan->nama_ruangan }}
                                @elseif($candidate->proyektor)
                                    {{ $candidate->proyektor->nama_proyektor }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($candidate->tanggal_pinjam)->format('d M Y') }}<br>
                                {{ $candidate->jam_mulai }} - {{ $candidate->jam_selesai }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($candidate->status_peminjaman == 'Menunggu')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($candidate->status_peminjaman == 'Disetujui')
                                        bg-green-100 text-green-800
                                    @elseif($candidate->status_peminjaman == 'Selesai')
                                        bg-blue-100 text-blue-800
                                    @elseif($candidate->status_peminjaman == 'Ditolak')
                                        bg-red-100 text-red-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif
                                    ">
                                    {{ $candidate->status_peminjaman == 'Menunggu' ? 'Menunggu Konfirmasi' : $candidate->status_peminjaman }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($candidate->status_peminjaman == 'Menunggu' && $candidate->id_peminjaman != $mainPeminjaman->id_peminjaman)
                                    <a href="{{ route('admin.peminjaman.lihat_peminjaman', $candidate->id_peminjaman) }}"
                                       class="text-indigo-600 hover:text-indigo-900">Lihat</a>
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

@endsection
