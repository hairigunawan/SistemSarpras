@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-700 p-6 rounded-lg">
            <h3 class="text-gray-50 text-sm font-medium">Total Akun</h3>
            <p class="text-3xl text-gray-50 font-semibold">{{ $jumlah_akun ?? 0 }}</p>
        </div>
        <div class="bg-gray-700 p-6 rounded-lg">
            <h3 class="text-gray-50 text-sm font-medium">Total Sarpras</h3>
            <p class="text-3xl text-gray-50 font-semibold">{{ $jumlah_sarpras ?? 0 }}</p>
        </div>
        <a href="{{ route('admin.peminjaman.index', ['status' => 'Menunggu']) }}" class="bg-gray-700 p-6 rounded-lg">
            <h3 class="text-gray-50 text-sm font-medium">Peminjaman Menunggu</h3>
            <p class="text-3xl text-gray-50 font-semibold">{{ $peminjaman_menunggu ?? 0 }}</p>
        </a>
        <a href="{{ route('admin.peminjaman.index', ['status' => 'disetujui']) }}" class="bg-gray-700 p-6 rounded-lg">
            <h3 class="text-gray-50 text-sm font-medium">Peminjaman Disetujui</h3>
            <p class="text-3xl text-gray-50 font-semibold">{{ $peminjaman_disetujui ?? 0 }}</p>
        </a>
    </div>
    <div class="bg-white p-6 rounded-lg mb-4">
        <h3 class="font-semibold mb-4">Statistik</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-700 p-2 rounded-lg">
            <a href="{{ route('sarpras.ruangan.tambah_ruangan') }}" class="bg-gray-700 p-6 rounded-lg">
                <h3 class="text-gray-50 text-sm font-medium">Tambah Ruangan</h3>
            </a>
        </div>
        <div class="bg-gray-700 p-2 rounded-lg">
            <a href="{{ route('sarpras.proyektor.tambah_proyektor') }}" class="bg-gray-700 p-6 rounded-lg">
                <h3 class="text-gray-50 text-sm font-medium">Tambah Proyektor</h3>
            </a>
        </div>
        <div class="bg-gray-700 p-2 rounded-lg">
            <a href="{{ route('laporan.pdf') }}" class="bg-gray-700 p-6 rounded-lg">
                <h3 class="text-gray-50 text-sm font-medium">Laporan PDF</h3>
            </a>
        </div>
        <div class="bg-gray-700 p-2 rounded-lg">
            <a href="{{ route('laporan.excel') }}" class="bg-gray-700 p-6 rounded-lg">
                <h3 class="text-gray-50 text-sm font-medium">Laporan Excel</h3>
            </a>
        </div>
    </div>
</div>
@endsection
