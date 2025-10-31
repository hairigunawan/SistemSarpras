@extends('layouts.guest')

@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="pt-10">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="text-center pt-5">
                <h1 class="text-3xl text-gray-700 font-bold mb-2">Riwayat Peminjaman Saya</h1>
                <p class="text-gray-500 max-w-2xl mx-auto">
                    Lihat semua pengajuan dan status peminjaman sarana dan prasarana Anda.
                </p>
            </div>

            <div class="px-6 py-8 sm:px-10">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-t">
                        <thead>
                            <tr class="text-gray-500 text-sm">
                                <th class="py-3 px-2 font-medium">Nama Sarpras</th>
                                <th class="py-3 px-2 font-medium">Tanggal Pinjam</th>
                                <th class="py-3 px-2 font-medium">Tanggal Kembali</th>
                                <th class="py-3 px-2 font-medium">Status</th>
                                <th class="py-3 px-2 font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjaman as $item)
                            <tr class="border-t">
                                <td class="py-4 px-2 font-medium text-gray-700 text-sm">
                                    @if($item->ruangan)
                                        {{ $item->ruangan->nama_ruangan }}
                                    @elseif($item->proyektor)
                                        {{ $item->proyektor->nama_proyektor }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="py-4 px-2 font-medium text-sm text-gray-700">{{ $item->tanggal_pinjam }}</td>
                                <td class="py-4 px-2 font-medium text-sm text-gray-700">{{ $item->tanggal_kembali }}</td>
                                <td class="py-4 px-2">
                                    @if($item->status_peminjaman == 'Menunggu')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-3 py-1.5 rounded">Menunggu</span>
                                    @elseif($item->status_peminjaman == 'Disetujui')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-3 py-1.5 rounded">Disetujui</span>
                                    @elseif($item->status_peminjaman == 'Ditolak')
                                    <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-3 py-1.5 rounded">Ditolak</span>
                                    @elseif($item->status_peminjaman == 'Selesai')
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-3 py-1.5 rounded">Selesai</span>
                                    @else
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-3 py-1.5 rounded">{{ $item->status_peminjaman }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-2">
                                    <a href="{{ route('public.peminjaman.show', $item->id_peminjaman) }}" class="hover:bg-gray-200 px-4 py-2 rounded-lg font-medium text-sm text-gray-700">View Details</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-4 px-2 text-center text-gray-500">Tidak ada data riwayat peminjaman</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection