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
        @forelse ($sarpras as $item)
            <div class="border rounded-lg overflow-hidden shadow-sm flex flex-col">
                <img src="{{ $item->gambar ? asset('storage/' . str_replace('public/', '', $item->gambar)) : 'https://via.placeholder.com/400x250' }}" alt="{{ $item->nama_sarpras }}" class="w-full h-48 object-cover">
                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="font-bold text-lg">{{ $item->nama_sarpras }}</h3>
                    <p class="text-sm text-gray-500 mb-3">{{ $item->lokasi }}</p>
                    <div class="mb-4">
                        <span class="text-xs font-semibold inline-block py-1 px-4 uppercase rounded-[5px] {{ $item->status == 'Tersedia' ? 'text-green-600 bg-green-200' : 'text-yellow-600 bg-yellow-200' }}">
                            {{ $item->status }}
                        </span>
                    </div>
                    <div class="mt-auto flex space-x-2">
                        <a href="{{ route('admin.sarpras.lihat_sarpras', $item->id_sarpras) }}" class="w-full text-center bg-[#179ACE] text-white px-3 py-2 rounded hover:bg-[#0F6A8F] text-xs font-semibold">Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-3 text-center text-gray-500 py-8">Data sarpras tidak ditemukan.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $sarpras->links() }}
    </div>
</div>
@endsection
