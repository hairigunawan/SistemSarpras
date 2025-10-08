@extends('layouts.app')

@section('title', 'Detail Peminjam')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <a href="{{ route('peminjaman.index') }}" class="flex gap-2 hover:text-gray-500 text-xl items-center text-gray-700 font-semibold"><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m0 0l6 6m-6-6l6-6"/></svg></span>Detail Peminjam</a>
        <div class="flex space-x-2">
            <form action="{{ route('peminjaman.complete', $peminjaman->id_peminjaman) }}" method="POST">
                @csrf
                @method('PATCH')
                @if ($peminjaman->status == 'Disetujui')
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 font-normal rounded-lg hover:bg-blue-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="m9 11l3 3L22 4m-2 10v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9" />
                    </svg>
                    Selesikan
                </button>
                @endif
            </form>

            <a href="https://wa.me/{{ $peminjaman->telepon_peminjam }}" target="_blank"
                class="flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 font-normal rounded-lg hover:bg-green-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M12.04 2C6.55 2 2.09 6.46 2.09 11.95c0 2.06.54 4.08 1.57 5.85L2 22l4.33-1.61a9.86 9.86 0 0 0 5.71 1.75h.01c5.49 0 9.95-4.46 9.95-9.95C22 6.46 17.54 2 12.04 2m5.66 13.69c-.24.68-1.37 1.29-1.9 1.37c-.49.07-1.11.1-1.8-.11c-.42-.14-.95-.31-1.63-.61c-2.87-1.24-4.74-4.15-4.88-4.35c-.14-.19-1.17-1.55-1.17-2.95c0-1.4.74-2.08 1.01-2.37c.27-.29.59-.36.79-.36c.19 0 .39 0 .56.01c.18.01.42-.07.65.49c.24.56.82 1.93.89 2.07c.07.14.12.3.02.49c-.09.19-.14.31-.29.48c-.14.17-.31.38-.44.51c-.14.14-.28.29-.12.57c.17.29.76 1.25 1.63 2.02c1.12.99 2.07 1.29 2.36 1.44c.29.14.46.12.64-.07c.19-.19.74-.83.93-1.12c.19-.29.39-.24.65-.14c.26.1 1.67.79 1.96.93c.28.14.46.21.52.32c.07.12.07.7-.17 1.38" />
                </svg>
                Hubungi peminjam
            </a>
        </div>
    </div>

    <!-- Informasi Peminjaman -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi peminjaman</h3>
        <div class="divide-y divide-gray-200">
            <div class="flex justify-between py-3">
                <span class="text-gray-600">Status Peminjam</span>
                @if($peminjaman->status == 'Menunggu')
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold mr-2 px-3 py-1.5 rounded">Menunggu</span>
                    <form action="{{ route('peminjaman.approve', $peminjaman->id_peminjaman) }}" method="POST" class="inline"> @csrf @method('PATCH') <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg font-normal hover:bg-green-600 transition-colors"> Setujui Peminjaman </button> </form>
                @elseif($peminjaman->status == 'Disetujui')
                    <span class="bg-green-100 text-green-800 font-normal mr-2 px-3 py-1.5 rounded">Disetujui</span>
                @elseif($peminjaman->status == 'Ditolak')
                    <span class="bg-red-100 text-red-800 font-normal mr-2 px-3 py-1.5 rounded">Ditolak</span>
                @elseif($peminjaman->status == 'Selesai')
                    <span class="bg-blue-100 text-blue-800 font-normal mr-2 px-3 py-1.5 rounded">Selesai</span>
                @else
                    <span class="font-normal text-gray-800">{{ $peminjaman->status }}</span>
                @endif
            </div>
            <div class="flex justify-between py-3">
                <span class="text-gray-600">Alasan Ditolak</span>
                <span>{{ $peminjaman->alasan_penolakan ?? '-' }}</span>
            </div>
            <div class="flex justify-between py-3">
                <span class="text-gray-600">Tanggal Pengajuan</span>
                <span>{{ \Carbon\Carbon::parse($peminjaman->created_at)->format('d F Y') }}</span>
            </div>

            <div class="flex justify-between py-3">
                <span class="text-gray-600">Jadwal Pinjam</span>
                <span>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') }},
                    {{ $peminjaman->jam_mulai }} - {{ $peminjaman->jam_selesai }}</span>
            </div>

            <div class="flex justify-between py-3">
                <span class="text-gray-600">Tanggal Pengembalian</span>
                <span>{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d F Y') : 'Menunggu Konfirmasi' }}</span>
            </div>

            <div class="flex justify-between py-3">
                <span class="text-gray-600">Keterangan</span>
                @if ($peminjaman->keterangan && $peminjaman->status == 'Menunggu')
                    <span>Menunggu Konfirmasi</span>
                @elseif ($peminjaman->keterangan && $peminjaman->status == 'Disetujui')
                    <span>Pengajuan Disetujui</span>
                @else ($peminjaman->keterangan && $peminjaman->status == 'Ditolak')
                    <span>Pengajuan Ditolak</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Informasi Peminjam -->
    <div>
        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informasi peminjam</h3>
        <div class="divide-y divide-gray-200">
            <div class="flex justify-between py-3">
                <span class="text-gray-600">Nama Lengkap</span>
                <span class="font-normal text-gray-800">{{ $akun->name ?? 'Tidak diketahui' }}</span>
            </div>
            <div class="flex justify-between py-3">
                <span class="text-gray-600">Email</span>
                <span class="font-normal text-gray-800">{{ $akun->email ?? 'Tidak diketahui' }}</span>
            </div>
            <div class="flex justify-between py-3">
                <span class="text-gray-600">Nomor WhatsApp</span>
                <span class="font-normal text-gray-800">{{ $peminjaman->telepon_peminjam ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
