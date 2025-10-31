@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="bg-white rounded-lg p-6">
    <div class="flex justify-between">
        <div>
            <h2 class="text-xl text-gray-700 font-semibold mb-1">Peminjaman</h2>
            <p class="text-gray-500 mb-6">Kelola data peminjaman sarpras</p>
        </div>
        <form method="GET" action="{{ route('admin.peminjaman.index') }}" class="flex items-center space-x-2">
            @if(request('jenis'))
                <input type="hidden" name="nama" value="{{ request('nama') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sarpras..." class="w-full md:w-64 px-4 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-300">
        </form>
    </div>

    <div class="flex justify-between">
        <div class="border-b w-70 py-2 mb-4">
            <a href="{{ route('admin.peminjaman.index', ['status' => 'all']) }}" class="py-2 text-sm px-3 ml-1 {{ $status == 'all' ? 'border-b border-#179ACE font-semibold text-[#179ACE]' : 'text-gray-500 hover:text-[#179ACE]' }}">Semua</a>
            <a href="{{ route('admin.peminjaman.index', ['status' => 'Menunggu']) }}" class="py-2 text-sm px-3 {{ $status == 'Menunggu' ? 'border-b border-#179ACE font-semibold text-[#179ACE]' : 'text-gray-500 hover:text-[#179ACE]' }}">Menunggu</a>
            <a href="{{ route('admin.peminjaman.index', ['status' => 'Disetujui']) }}" class="py-2 text-sm px-3 {{ $status == 'Disetujui' ? 'border-b border-#179ACE font-semibold text-[#179ACE]' : 'text-gray-500 hover:text-[#179ACE]' }}">Disetujui</a>
            <a href="{{ route('admin.peminjaman.index', ['status' => 'Ditolak']) }}" class="py-2 text-sm px-3 {{ $status == 'Ditolak' ? 'border-b border-#179ACE font-semibold text-[#179ACE]' : 'text-gray-500 hover:text-[#179ACE]' }}">Ditolak</a>
            <a href="{{ route('admin.peminjaman.index', ['status' => 'Selesai']) }}" class="py-2 text-sm px-3 mr-1 {{ $status == 'Selesai' ? 'border-b border-#179ACE font-semibold text-[#179ACE]' : 'text-gray-500 hover:text-[#179ACE]' }}">Selesai</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-t">
            <thead>
                <tr class="text-gray-500 text-sm">
                    <th class="py-3 px-2 font-medium">No</th>
                    <th class="py-3 px-2 font-medium">Role</th>
                    <th class="py-3 px-2 font-medium">Peminjam</th>
                    <th class="py-3 px-2 font-medium">Nama Sarpras</th>
                    <th class="py-3 px-2 font-medium">Hari Pengajuan</th>
                    <th class="py-3 px-2 font-medium">Status</th>
                    <th class="flex justify-center py-3 px-2 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $item)
                <tr class="border-t">
                    <td class="py-4 px-2">{{ $loop->iteration }}</td>
                    <td class="py-4 px-2 forn-medium text-sm text-gray-700">{{ $item->user->userRole->nama_role }}</td>
                    <td class="py-4 px-2 font-medium text-gray-700 text-sm">{{ $item->nama_peminjam ?? $item->user->name ?? 'N/A' }}</td>
                    <td class="py-4 px-2 forn-medium text-sm text-gray-700">
                        @if($item->ruangan)
                            {{ $item->ruangan->nama_ruangan }}
                        @elseif($item->proyektor)
                            {{ $item->proyektor->nama_proyektor }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="py-4 px-2 forn-medium text-sm text-gray-700">
                        {{ $item->tanggal_pinjam }}
                    </td>
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

                    @if($item->status_peminjaman == 'Menunggu')
                    <td class="grid justify-center py-4 px-2">
                        <a href="{{ route('admin.peminjaman.lihat_peminjaman', $item->id_peminjaman) }}" class="hover:bg-gray-200 px-4 py-2 rounded-lg forn-medium text-sm text-gray-700">View Details</a>
                    </td>
                    @else
                    <td class="py-4 px-2 items-center">
                        <a href="{{ route('admin.peminjaman.lihat_peminjaman', $item->id_peminjaman) }}" class="hover:bg-gray-200 px-4 py-2 rounded-lg font-medium text-sm text-gray-700">View Details</a>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-4 px-2 text-center text-gray-500">Tidak ada data peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
