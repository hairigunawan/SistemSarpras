@extends('layouts.app')

@section('title', 'Inventory')

{{-- Tombol tambah hanya untuk admin --}}
@if(Auth::user()->role == 'admin')
<a href="{{ route('inventory.create') }}" class="btn btn-primary mb-3">+ Tambah Barang</a>
@endif

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-1">Inventory</h2>
    <p class="text-gray-500 mb-6">Kelola data barang dan stok berdasarkan kategori.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('sarpras.index', ['jenis' => 'Ruangan']) }}" class="block rounded-lg overflow-hidden shadow-lg transform hover:-translate-y-1 transition-transform duration-300">
            <div class="relative h-56 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1594411448292-8051a373b970?q=80&w=2070');">
                <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-between p-4">
                    <div>
                        <h3 class="text-white text-2xl font-bold">Ruangan</h3>
                        <p class="text-gray-200 text-sm">Ruang Kelas & Laboratorium</p>
                    </div>
                    <span class="bg-white text-gray-800 text-lg font-bold w-12 h-12 flex items-center justify-center rounded-full self-end">{{ $jumlahRuangan }}</span>
                </div>
            </div>
            <div class="bg-white p-4 text-center font-semibold text-gray-700 hover:bg-gray-50">
                Lihat Daftar Ruangan
            </div>
        </a>

        <a href="{{ route('sarpras.index', ['jenis' => 'Proyektor']) }}" class="block rounded-lg overflow-hidden shadow-lg transform hover:-translate-y-1 transition-transform duration-300">
             <div class="relative h-56 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1599228232540-64de6258a1f8?q=80&w=1932');">
                <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-between p-4">
                    <div>
                        <h3 class="text-white text-2xl font-bold">Proyektor</h3>
                        <p class="text-gray-200 text-sm">Proyektor & Alat Pendukung</p>
                    </div>
                    <span class="bg-white text-gray-800 text-lg font-bold w-12 h-12 flex items-center justify-center rounded-full self-end">{{ $jumlahProyektor }}</span>
                </div>
            </div>
            <div class="bg-white p-4 text-center font-semibold text-gray-700 hover:bg-gray-50">
                Lihat Daftar Proyektor
            </div>
        </a>
    </div>
</div>
@endsection
