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
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden mb-16">
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
                @if ($item->ruangan && $item->proyektor)
                    <div class="flex items-center">
                        <div class="flex items-center">
                            {{ $item->ruangan->nama_ruangan }}
                             -
                            {{ $item->proyektor->nama_proyektor }}
                        </div>
                    </div>
                @elseif($item->ruangan)
                    <div class="flex items-center">
                        {{ $item->ruangan->nama_ruangan }}
                    </div>
                @elseif($item->proyektor)
                    <div class="flex items-center">
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
    <!-- Jadwal Ruangan Terpakai -->
        <div class="mx-20">
            <div class="mb-12">
            <div class="flex items-center mb-6 gap-4">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Jadwal Ruangan Terpakai</h2>
                </div>
                @if(isset($labs) && count($labs) > 0)
                    <span class="bg-gradient-to-r from-red-500 to-red-600 text-white text-[10px] font-medium px-5 py-1 rounded-xl">
                        {{ count($labs) }} ruangan aktif
                    </span>
                @endif
            </div>

            @if(isset($labs) && count($labs) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($labs as $lab)
                        <div class="bg-white rounded-xl hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100">
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-red-500 to-red-600 p-4 text-white">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-lg font-bold">{{ $lab['nama'] }}</h4>
                                    <span class="bg-white/20 backdrop-blur px-2 py-1 text-xs rounded-full font-medium">
                                        Terpakai
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <div>
                                            <span class="text-sm text-gray-500">Kelas</span>
                                            <p class="font-semibold text-gray-800">{{ $lab['kelas'] }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <div>
                                            <span class="text-sm text-gray-500">Mata Kuliah</span>
                                            <p class="font-semibold text-gray-800">{{ $lab['matkul'] }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <span class="text-sm text-gray-500">Waktu</span>
                                            <p class="font-semibold text-gray-800">{{ $lab['waktu'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <button class="w-full bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-sm">
                                        Lihat Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-600 mb-2">Tidak ada ruangan terpakai</h3>
                    <p class="text-gray-400 text-sm">Semua ruangan saat ini tersedia untuk digunakan</p>
                </div>
            @endif
        </div>
  </div>
</div>
@endsection
