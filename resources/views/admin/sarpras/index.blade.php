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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sarpras as $item)
  <div class="col-md-4">
    <div class="card inventory-card shadow-sm border-0">
      <div class="card-header text-center">
        {{ $item->nama_sarpras }}
      </div>
      <div class="card-body text-center">
        @if($item->gambar)
          <img src="{{ asset('storage/' . str_replace('public/', '', $item->gambar)) }}" 
               class="img-fluid mb-3 rounded" 
               alt="{{ $item->nama_sarpras }}">
        @else
          <img src="https://via.placeholder.com/300x200?text=No+Image" 
               class="img-fluid mb-3 rounded" 
               alt="No Image">
        @endif

        <p class="text-sm text-gray-500">{{ $item->lokasi }}</p>

        <span class="text-xs font-semibold inline-block py-1 px-4 rounded-[5px] 
          {{ $item->status == 'Tersedia' ? 'text-green-600 bg-green-200' : 'text-yellow-600 bg-yellow-200' }}">
          {{ $item->status }}
        </span>
      </div>
    </div>
  </div>
@empty
  <p class="text-center text-muted">Belum ada data inventaris tersedia.</p>
@endforelse
    </div>

    <div class="mt-6">
        {{ $sarpras->links() }}
    </div>
</div>
@endsection
