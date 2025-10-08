@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="bg-white p-6 rounded-lg">
    <div class="flex justify-between">
        <div>
            <h2 class="text-xl text-gray-700 font-semibold mb-1">Peminjaman</h2>
            <p class="text-gray-500 mb-6">Kelola data peminjaman sarpras</p>
        </div>
        <form method="GET" action="{{ route('peminjaman.index') }}" class="flex items-center space-x-2">
                @if(request('jenis'))
                    <input type="hidden" name="nama" value="{{ request('nama') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sarpras..." class="w-full md:w-64 px-4 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-300">
            </form>
    </div>

    <div class="flex justify-between">
        <div class="border w-70 py-2 rounded-[7px] mb-4">
            <a href="{{ route('peminjaman.index', ['status' => 'all']) }}" class="py-2 text-sm px-3 ml-1 rounded-[7px] {{ $status == 'all' ? 'border border-blue-500 font-semibold text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">Semua</a>
            <a href="{{ route('peminjaman.index', ['status' => 'Menunggu']) }}" class="py-2 text-sm px-3 rounded-[7px] {{ $status == 'Menunggu' ? 'border border-blue-500 font-semibold text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">Menunggu</a>
            <a href="{{ route('peminjaman.index', ['status' => 'Disetujui']) }}" class="py-2 text-sm px-3 rounded-[7px] {{ $status == 'Disetujui' ? 'border border-blue-500 font-semibold text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">Disetujui</a>
            <a href="{{ route('peminjaman.index', ['status' => 'Ditolak']) }}" class="py-2 text-sm px-3 rounded-[7px] {{ $status == 'Ditolak' ? 'border border-blue-500 font-semibold text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">Ditolak</a>
            <a href="{{ route('peminjaman.index', ['status' => 'Selesai']) }}" class="py-2 text-sm px-3 mr-1 rounded-[7px] {{ $status == 'Selesai' ? 'border border-blue-500 font-semibold text-blue-600' : 'text-gray-500 hover:text-blue-600' }}">Selesai</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-t">
            <thead>
                <tr class="text-gray-500 text-sm">
                    <th class="py-3 px-2 font-medium">Sarpras ID</th>
                    <th class="py-3 px-2 font-medium">Peminjam</th>
                    <th class="py-3 px-2 font-medium">Nama Sarpras</th>
                    <th class="py-3 px-2 font-medium">Status</th>
                    <th class="flex justify-center py-3 px-2 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $item)
                <tr class="border-t">
                    <td class="py-4 px-2">{{ $item->id_sarpras }}</td>
                    <td class="py-4 px-2 font-semibold">{{ $item->nama_peminjam ?? $item->user->name ?? 'N/A' }}</td>
                    <td class="py-4 px-2">{{ $item->sarpras->nama_sarpras ?? 'N/A' }}</td>
                    <td class="py-4 px-2">
                        @if($item->status == 'Menunggu')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold mr-2 px-3 py-1.5 rounded">Menunggu</span>
                        @elseif($item->status == 'Disetujui')
                            <span class="bg-green-100 text-green-800 text-xs font-semibold mr-2 px-3 py-1.5 rounded">Disetujui</span>
                        @elseif($item->status == 'Ditolak')
                            <span class="bg-red-100 text-red-800 text-xs font-semibold mr-2 px-3 py-1.5 rounded">Ditolak</span>
                        @elseif($item->status == 'Selesai')
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-3 py-1.5 rounded">Selesai</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-3 py-1.5 rounded">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td class="flex justify-center py-4 px-2">
                        <a href="{{ route('peminjaman.show', $item->id_peminjaman) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold">View Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 px-2 text-center text-gray-500">Tidak ada data peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
