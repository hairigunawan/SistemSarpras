@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-500 text-sm font-medium">Total Akun</h3>
            <p class="text-3xl font-semibold">{{ $jumlah_akun ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-500 text-sm font-medium">Total Sarpras</h3>
            <p class="text-3xl font-semibold">{{ $jumlah_sarpras ?? 0 }}</p>
        </div>
         <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-500 text-sm font-medium">Peminjaman Menunggu</h3>
            <p class="text-3xl font-semibold">{{ $peminjaman_menunggu ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-500 text-sm font-medium">Peminjaman Disetujui</h3>
            <p class="text-3xl font-semibold">{{ $peminjaman_disetujui ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
       <h3 class="font-semibold mb-4">Aktivitas Terbaru</h3>
       <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
           <p class="text-gray-400">Grafik atau Tabel Aktivitas</p>
       </div>
    </div>
@endsection
