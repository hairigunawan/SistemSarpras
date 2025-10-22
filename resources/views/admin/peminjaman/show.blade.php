@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md w-full max-w-4xl mx-auto">
    <!-- Header with Actions -->
    <div class="flex flex-wrap justify-between items-center mb-6 pb-4">
        <a href="{{ route('admin.peminjaman.index') }}" class="flex gap-2 text-xl items-center text-gray-800 font-semibold mb-4 sm:mb-0 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 18l-6-6l6-6"/></svg>
            <span>Detail Peminjam</span>
        </a>
        <div class="flex items-center space-x-3">
            {{-- Actions based on status --}}
            @if ($mainPeminjaman->status == 'Menunggu')
                <form action="{{ route('peminjaman.approve', $mainPeminjaman->id_peminjaman) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 font-semibold rounded-lg hover:bg-blue-200 transition border border-blue-200">
                       <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"><path fill="currentColor" d="m9.55 18l-5.7-5.7l1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4L9.55 18Z"/></svg>
                       <span>Seleksian</span>
                    </button>
                </form>
            @elseif ($mainPeminjaman->status == 'Disetujui')
                <form action="{{ route('peminjaman.complete', $mainPeminjaman->id_peminjaman) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-indigo-100 text-indigo-700 font-semibold rounded-lg hover:bg-indigo-200 transition border border-indigo-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 11l3 3L22 4m-2 10v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9" /></svg>
                        <span>Selesaikan</span>
                    </button>
                </form>
            @endif

            <a href="https://wa.me/{{ $mainPeminjaman->user->telepon ?? '' }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 font-semibold rounded-lg hover:bg-green-200 transition border border-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"><path fill="currentColor" d="M19.05 4.95A9.9 9.9 0 0 0 12 2a9.9 9.9 0 0 0-7.05 2.95a9.9 9.9 0 0 0-2.95 7.05A9.9 9.9 0 0 0 12 22a9.9 9.9 0 0 0 7.05-2.95A9.9 9.9 0 0 0 22 12a9.9 9.9 0 0 0-2.95-7.05M16.5 15.3c-.25.5-.85.95-1.4 1.1s-1.1.2-1.7-.15s-1.2-.8-1.7-1.35c-.5-.55-1-1.15-1.35-1.7s-.4-1.15-.15-1.7s.6-1.15 1.1-1.4s.95-.2 1.45 0l.6.35l.2.35c.25.65.15 1.4-.3 1.85l-.5.55c-.1.1-.1.25 0 .35s.2.2.35.35l1.05 1.05c.1.1.25.1.35 0l.55-.5c.45-.45 1.2-.55 1.85-.3l.35.2l.35.6c.2.5 0 1.05-.15 1.3Z" /></svg>
                <span>Hubungi Peminjam</span>
            </a>
        </div>
    </div>

    <!-- Informasi Peminjaman -->
    <div class="space-y-2">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi peminjaman</h3>
        <div class="flex justify-between items-center py-4 border-b">
            <span class="text-gray-500">Status Peminjam</span>
            <div class="flex items-center gap-4">
                <span class="font-medium text-gray-800">{{ $mainPeminjaman->status == 'Menunggu' ? 'Menunggu Konfirmasi' : $mainPeminjaman->status }}</span>
                @if($mainPeminjaman->status == 'Menunggu')
                    <form action="{{ route('peminjaman.approve', $mainPeminjaman->id_peminjaman) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-1.5 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 text-sm">Disetujui</button>
                    </form>
                @endif
            </div>
        </div>
        <div class="flex justify-between items-center py-4 border-b">
            <span class="text-gray-500">Tanggal Pengajuan</span>
            <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($mainPeminjaman->created_at)->translatedFormat('d F Y') }}</span>
        </div>
        <div class="flex justify-between items-center py-4 border-b">
            <span class="text-gray-500">Jadwal Pinjam</span>
            <span class="font-medium text-gray-800 text-right">{{ \Carbon\Carbon::parse($mainPeminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}, {{ date('H:i', strtotime($mainPeminjaman->jam_mulai)) }} - {{ date('H:i', strtotime($mainPeminjaman->jam_selesai)) }}</span>
        </div>
        <div class="flex justify-between items-center py-4 border-b">
            <span class="text-gray-500">Tanggal Pengembalian</span>
            <span class="font-medium text-gray-800">{{ $mainPeminjaman->tanggal_kembali ? \Carbon\Carbon::parse($mainPeminjaman->tanggal_kembali)->translatedFormat('d F Y') : 'Menunggu Konfirmasi' }}</span>
        </div>
        <div class="flex justify-between items-center py-4 border-b">
            <span class="text-gray-500">Keterangan</span>
            <span class="font-medium text-gray-800 text-right">{{ $mainPeminjaman->keterangan ?? 'Menunggu Konfirmasi' }}</span>
        </div>
    </div>

    <!-- Informasi Peminjam -->
    <div class="space-y-2 mt-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi peminjam</h3>
        <div class="flex justify-between items-center py-4 border-b">
            <span class="text-gray-500">Nama Lengkap</span>
            <span class="font-medium text-gray-800">{{ $mainPeminjaman->user->name ?? 'Tidak diketahui' }}</span>
        </div>
        <div class="flex justify-between items-center py-4 border-b">
            <span class="text-gray-500">Email</span>
            <span class="font-medium text-gray-800">{{ $mainPeminjaman->user->email ?? 'Tidak diketahui' }}</span>
        </div>
        <div class="flex justify-between items-center py-4">
            <span class="text-gray-500">Nomor WhatsApp</span>
            <span class="font-medium text-gray-800">{{ $mainPeminjaman->nomor_whatsapp ?? '-' }}</span>
        </div>
         @if ($mainPeminjaman->status == 'Menunggu')
            <div class="flex justify-end pt-4">
                 <button onclick="openRejectModal({{ $mainPeminjaman->id_peminjaman }})" type="button" class="text-sm text-red-600 hover:text-red-800 font-semibold">Tolak Pengajuan</button>
            </div>
        @endif
    </div>
</div>

<!-- Modal Tolak -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-4">Alasan Penolakan</h3>
            <form id="rejectForm" action="" method="POST">
                @csrf
                @method('PATCH')
                <div class="mt-2 px-7 py-3">
                    <textarea name="alasan_penolakan" class="w-full h-24 p-2 border rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan alasan penolakan..." required></textarea>
                </div>
                <div class="flex items-center justify-center space-x-4 px-4 py-3">
                    <button id="cancelRejectBtn" type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg w-full">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg w-full">
                        Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rejectModal = document.getElementById('rejectModal');
        const rejectForm = document.getElementById('rejectForm');
        const cancelRejectBtn = document.getElementById('cancelRejectBtn');

        window.openRejectModal = function(id) {
            if(id) {
                // FIX: Remove the '/admin' prefix to match the route in web.php
                rejectForm.action = `{{ url('peminjaman') }}/${id}/reject`;
                rejectModal.classList.remove('hidden');
            }
        }

        function closeRejectModal() {
            rejectModal.classList.add('hidden');
        }

        if (cancelRejectBtn) {
            cancelRejectBtn.addEventListener('click', closeRejectModal);
        }

        window.addEventListener('click', function(event) {
            if (event.target == rejectModal) {
                closeRejectModal();
            }
        });
    });
</script>
@endpush

