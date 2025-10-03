@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6 pb-4 border-b">
        <div>
            <h2 class="text-xl font-bold">Detail Peminjaman</h2>
            <p class="text-gray-500">ID Peminjaman: {{ $peminjaman->id_peminjaman }}</p>
        </div>
        <a href="{{ route('peminjaman.index') }}" class="text-gray-600 hover:text-gray-800">&larr; Kembali ke Daftar</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-4">
            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Peminjam</h4>
                <p class="text-lg font-medium">{{ $peminjaman->nama_peminjam ?? $peminjaman->user->name ?? 'N/A' }}</p>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Email</h4>
                <p class="text-lg font-medium">{{ $peminjaman->email_peminjam ?? $peminjaman->user->email ?? 'N/A' }}</p>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Telepon</h4>
                <p class="text-lg font-medium">{{ $peminjaman->telepon_peminjam ?? 'N/A' }}</p>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Sarpras</h4>
                <p class="text-lg font-medium">{{ $peminjaman->sarpras->nama_sarpras ?? 'N/A' }}</p>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Jenis Sarpras</h4>
                <p class="text-lg font-medium">{{ $peminjaman->sarpras->jenis_sarpras ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Tanggal Pinjam</h4>
                <p class="text-lg font-medium">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</p>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Tanggal Kembali</h4>
                <p class="text-lg font-medium">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</p>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Jam Mulai</h4>
                <p class="text-lg font-medium">{{ $peminjaman->jam_mulai }}</p>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Jam Selesai</h4>
                <p class="text-lg font-medium">{{ $peminjaman->jam_selesai }}</p>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Status</h4>
                <p class="text-lg font-medium">
                    @if($peminjaman->status == 'Menunggu')
                        <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold mr-2 px-2.5 py-1 rounded-full">Menunggu</span>
                    @elseif($peminjaman->status == 'Disetujui')
                        <span class="bg-green-100 text-green-800 text-sm font-semibold mr-2 px-2.5 py-1 rounded-full">Disetujui</span>
                    @elseif($peminjaman->status == 'Ditolak')
                        <span class="bg-red-100 text-red-800 text-sm font-semibold mr-2 px-2.5 py-1 rounded-full">Ditolak</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 text-sm font-semibold mr-2 px-2.5 py-1 rounded-full">{{ $peminjaman->status }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <h4 class="text-sm font-semibold text-gray-500 uppercase">Keterangan</h4>
        <p class="text-lg font-medium">{{ $peminjaman->keterangan ?? 'Tidak ada keterangan' }}</p>
    </div>

    @if($peminjaman->status == 'Menunggu')
    <div class="pt-6 border-t mt-6 flex space-x-4">
        <form action="{{ route('peminjaman.approve', $peminjaman->id_peminjaman) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-green-600 transition-colors">
                Setujui Peminjaman
            </button>
        </form>

        <form action="{{ route('peminjaman.reject', $peminjaman->id_peminjaman) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition-colors">
                Tolak Peminjaman
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
