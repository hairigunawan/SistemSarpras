@extends('layouts.guest')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="bg-gray-50 text-gray-800">
  <div class="container mx-auto py-10 px-4">
    <!-- Hero -->
    <div class="text-center mb-8">
      <h2 class="text-2xl font-bold mb-2">Daftar Peminjaman Sarana dan Prasarana</h2>
      <p class="text-gray-600 text-sm mb-6">
        Berikut adalah daftar semua pengajuan peminjaman yang telah diajukan
      </p>

      <div class="flex justify-center">
        <a href="{{ route('public.peminjaman.create') }}" class="flex justify-center space-x-4">
            <button class="bg-[#179ACE] text-white px-5 py-2 font-medium rounded-md hover:bg-[#0E7CBA]">Ajukan Peminjaman</button>
        </a>
      </div>
    </div>

    <!-- Tabel -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-blue-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">No</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Nama Peminjam</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Sarana/Prasarana</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Kegiatan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Tanggal Peminjaman</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Tanggal Pengembalian</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Jam</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Status</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($peminjaman as $index => $item)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->nama_peminjam }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                @if($item->ruangan)
                  <div class="flex items-center">
                    {{ $item->ruangan->nama_ruangan }}
                  </div>
                @elseif($item->proyektor)
                  <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    {{ $item->proyektor->nama_proyektor }}
                  </div>
                @else
                  <span class="text-gray-500">N/A</span>
                @endif
              </td>
              <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $item->jenis_kegiatan }}">{{ $item->jenis_kegiatan }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                {{ $item->jam_mulai ?? '-' }} - {{ $item->jam_selesai ?? '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($item->status_peminjaman == 'Menunggu')
                  <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                    Menunggu
                  </span>
                @elseif($item->status_peminjaman == 'Disetujui')
                  <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                    Disetujui
                  </span>
                @elseif($item->status_peminjaman == 'Ditolak')
                  <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                    Ditolak
                  </span>
                @elseif($item->status_peminjaman == 'Selesai')
                  <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                    Selesai
                  </span>
                @else
                  <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $item->status_peminjaman }}</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                  <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <h3 class="text-lg font-medium text-gray-500 mb-1">Tidak ada data peminjaman</h3>
                  <p class="text-gray-400">Belum ada pengajuan peminjaman yang diajukan</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
