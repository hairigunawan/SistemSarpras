@extends('layouts.app')

@section('title', 'Detail Sarpras')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6 pb-4 border-b">
        <div>
            <h2 class="text-xl font-bold">{{ $sarpra->nama_sarpras }}</h2>
            <p class="text-gray-500">{{ $sarpra->jenis_sarpras }}</p>
        </div>
        <a href="{{ route('sarpras.index') }}" class="text-gray-600 hover:text-gray-800">&larr; Kembali ke Daftar</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-1">
            @if($sarpra->gambar)
                <img src="{{ asset('storage/' . $sarpra->gambar) }}" alt="{{ $sarpra->nama_sarpras }}" class="w-full h-auto rounded-lg shadow-md">
            @else
                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-gray-500">Tidak ada gambar</span>
                </div>
            @endif
        </div>

        <div class="md:col-span-2 space-y-4">
            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Status</h4>
                <p class="text-lg font-medium">{{ $sarpra->status }}</p>
            </div>
             <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Lokasi</h4>
                <p class="text-lg font-medium">{{ $sarpra->lokasi ?? '-' }}</p>
            </div>

            @if($sarpra->jenis_sarpras == 'Ruangan')
            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Kapasitas</h4>
                <p class="text-lg font-medium">{{ $sarpra->kapasitas }} Orang</p>
            </div>
            @endif

            @if($sarpra->jenis_sarpras == 'Proyektor')
            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Merk</h4>
                <p class="text-lg font-medium">{{ $sarpra->merk ?? '-' }}</p>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase">Keterangan Lain</h4>
                <p class="text-lg font-medium">{{ $sarpra->keterangan_lain ?? '-' }}</p>
            </div>
            @endif

            <div class="pt-4 border-t">
                 <a href="{{ route('sarpras.edit', $sarpra->id_sarpras) }}" class="bg-blue-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-blue-600 transition-colors">
                    Edit Data
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
