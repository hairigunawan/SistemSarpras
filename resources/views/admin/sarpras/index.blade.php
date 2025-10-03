@extends('layouts.app')

@section('title', 'Daftar Sarpras')

@section('content')
<div class="bg-white p-6 rounded-lg">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h2 class="text-xl text-gray-700 font-semibold mb-4 md:mb-0">Daftar {{ request('jenis', 'Semua Sarpras') }}</h2>
        <div class="flex items-center space-x-4 w-full md:w-auto">
            <input type="text" placeholder="Cari sarpras..." class="w-full md:w-64 px-4 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            <a href="{{ route('sarpras.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-green-600 transition-colors whitespace-nowrap">
                + Tambah Sarpras
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
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
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full {{ $item->status == 'Tersedia' ? 'text-green-600 bg-green-200' : 'text-yellow-600 bg-yellow-200' }}">
                            {{ $item->status }}
                        </span>
                    </div>
                    <div class="mt-auto flex space-x-2">
                        <a href="{{ route('sarpras.show', $item->id_sarpras) }}" class="w-full text-center bg-emerald-400 text-white px-3 py-2 rounded hover:bg-green-600 text-xs font-semibold">Detail</a>
                        <a href="{{ route('sarpras.edit', $item->id_sarpras) }}" class="w-full text-center bg-blue-400 text-white px-3 py-2 rounded hover:bg-blue-600 text-xs font-semibold">Edit</a>
                        <form class="flex-1" action="{{ route('sarpras.destroy', $item->id_sarpras) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-center bg-red-400 text-white px-3 py-2 rounded hover:bg-red-600 text-xs font-semibold">Hapus</button>
                        </form>
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
