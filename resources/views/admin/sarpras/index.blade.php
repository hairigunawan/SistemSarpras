@extends('layouts.app')

@section('title', 'Daftar Sarpras')

@section('content')
<div class="bg-white p-6 rounded-lg">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h2 class="text-xl text-gray-700 font-semibold mb-4 md:mb-0">Daftar {{ request('jenis', 'Semua Sarpras') }}</h2>
        <div class="flex items-center space-x-4 w-full md:w-auto">
            <form method="GET" action="{{ route('admin.sarpras.index') }}" class="flex items-center space-x-2">
                @if(request('jenis'))
                    <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sarpras..." class="w-full md:w-64 px-4 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-gray-300">
            </form>
            <a href="{{ route('admin.sarpras.tambah_sarpras') }}" class="bg-[#179ACE] text-white px-4 py-1.5 rounded font-semibold text-sm hover:bg-[#0F6A8F] transition-colors whitespace-nowrap">
                + Tambah Sarpras
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($sarpras as $item)
          <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
            <div class="h-48 w-full">
              @if($item->gambar)
                <img src="{{ asset('storage/' . str_replace('public/', '', $item->gambar)) }}" 
                     alt="{{ $item->nama_sarpras }}" 
                     class="w-full h-full object-cover">
              @else
                <img src="https://via.placeholder.com/400x250?text=Tidak+Ada+Gambar" 
                     class="w-full h-full object-cover">
              @endif
            </div>

            <div class="p-5">
              <h2 class="text-lg font-bold text-gray-800">{{ $item->nama_sarpras }}</h2>
              <p class="text-sm text-gray-500 mb-3">{{ $item->lokasi ?? 'Lokasi tidak diketahui' }}</p>

              <div class="flex justify-between items-center mb-4">
                <span class="text-sm font-medium {{ $item->status == 'Tersedia' ? 'text-green-600' : 'text-yellow-600' }}">
                  {{ $item->status }}
                </span>
                <span class="text-sm text-gray-500 italic">{{ $item->jenis_sarpras ?? '-' }}</span>
              </div>

              <a href="{{ route('admin.sarpras.lihat_sarpras', $item->id_sarpras) }}" 
                 class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                Lihat Detail
              </a>
            </div>
          </div>
        @empty
          <p class="text-center text-muted col-span-full">Belum ada data inventaris tersedia.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $sarpras->links() }}
    </div>
</div>
@endsection
