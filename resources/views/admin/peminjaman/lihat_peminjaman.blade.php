
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
                        @method('PATCH')
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm bg-green-100 text-green-700 font-medium rounded-lg hover:bg-green-200 transition border border-green-200">
                           <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                               <path fill="currentColor" d="m9.55 18l-5.7-5.7l1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4L9.55 18Z"/>
                           </svg>
                           <span>Setujui</span>
                        </button>
                    </form>
                    <button type="button" class="flex items-center gap-2 px-4 py-2 text-sm bg-red-100 text-red-700 font-medium rounded-lg hover:bg-red-200 transition border border-red-200" onclick="openRejectModal({{ $mainPeminjaman->id_peminjaman }})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
                        </svg>
                        <span>Tolak</span>
                    </button>
                @elseif ($mainPeminjaman->status_peminjaman == 'Disetujui')
                    <form action="{{ route('peminjaman.complete', $mainPeminjaman->id_peminjaman) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm bg-indigo-100 text-indigo-700 font-medium rounded-lg hover:bg-indigo-200 transition border border-indigo-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 11l3 3L22 4m-2 10v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9" />
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
                @if($mainPeminjaman->status == 'Menunggu')
                    bg-yellow-100 text-yellow-800
                @elseif($mainPeminjaman->status == 'Disetujui')
                    bg-green-100 text-green-800
                @elseif($mainPeminjaman->status == 'Selesai')
                    bg-blue-100 text-blue-800
                @elseif($mainPeminjaman->status == 'Ditolak')
                    bg-red-100 text-red-800
                @else
                    bg-gray-100 text-gray-800
                @endif">
                {{ $mainPeminjaman->status == 'Menunggu' ? 'Menunggu Konfirmasi' : $mainPeminjaman->status }}
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

    <!-- Peminjaman Bentrok -->
    @if (!empty($rankedPeminjaman ?? []) && count($rankedPeminjaman) > 0)
    <div class="px-6 py-4 border-t border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Peminjaman Bentrok</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($rankedPeminjaman as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item['peminjaman']->nama_peminjam ?? $item['peminjaman']->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($item['peminjaman']->tanggal_pinjam)->format('d M Y') }}<br>
                                {{ $item['peminjaman']->jam_mulai }} - {{ $item['peminjaman']->jam_selesai }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($item['peminjaman']->status == 'Menunggu')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($item['peminjaman']->status == 'Disetujui')
                                        bg-green-100 text-green-800
                                    @elseif($item['peminjaman']->status == 'Selesai')
                                        bg-blue-100 text-blue-800
                                    @elseif($item['peminjaman']->status == 'Ditolak')
                                        bg-red-100 text-red-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif
                                    ">
                                    {{ $item['peminjaman']->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<!-- Modal Tolak -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Alasan Penolakan</h3>
                <button id="closeRejectModal" type="button" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="rejectForm" action="#" method="POST" data-action-template="{{ route('peminjaman.reject', ['id' => ':peminjaman_id']) }}">
                @csrf
                @method('PATCH')
                <div class="mt-2 px-2 py-3">
                    <textarea name="alasan_penolakan" class="w-full h-32 p-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan alasan penolakan..." required></textarea>
                </div>
                <div class="flex items-center justify-end space-x-3 px-2 py-3">
                    <button id="cancelRejectBtn" type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">
                        Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const rejectModal = document.getElementById('rejectModal');
    const rejectForm = document.getElementById('rejectForm');
    const cancelRejectBtn = document.getElementById('cancelRejectBtn');
    const closeRejectModal = document.getElementById('closeRejectModal');

    // Fungsi untuk membuka modal
    window.openRejectModal = function(id) {
        if (id && rejectForm) {
            const actionTemplate = rejectForm.getAttribute('data-action-template');
            if (actionTemplate) {
                const actionUrl = actionTemplate.replace(':peminjaman_id', id);
                rejectForm.setAttribute('action', actionUrl);
                rejectModal.classList.remove('hidden');
            }
        }
    }

    // Fungsi untuk menutup modal
    function closeRejectModalFunc() {
        rejectModal.classList.add('hidden');
        rejectForm.setAttribute('action', '#');
        rejectForm.reset();
    }

    // Event listeners untuk tombol
    if (cancelRejectBtn) {
        cancelRejectBtn.addEventListener('click', closeRejectModalFunc);
    }

    if (closeRejectModal) {
        closeRejectModal.addEventListener('click', closeRejectModalFunc);
    }

    if (rejectModal) {
        rejectModal.addEventListener('click', function(event) {
            if (event.target === rejectModal) {
                closeRejectModalFunc();
            }
        });
    }
});
</script>
@endsection
