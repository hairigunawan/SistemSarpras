@extends('layouts.app')

@section('title', 'Detail Sarpras')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6 pb-4 border-b">
        <div>
            <h2 class="text-xl font-bold">{{ $sarpras->nama_sarpras }}</h2>
            <p class="text-gray-500">{{ $sarpras->jenis_sarpras }}</p>
        </div>
        <a href="{{ route('admin.sarpras.index') }}" class="flex gap-1.5 text-gray-600 hover:text-gray-800"><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m0 0l6 6m-6-6l6-6"/></svg></span>Kembali ke Daftar</a>
    </div>
    <div class="flex gap-5">
        <div class="md:col-span-1">
            @if($sarpras->gambar)
                <img
                    src="{{ asset('storage/' . $sarpras->gambar) }}"
                    alt="{{ $sarpras->nama_sarpras }}"
                    class="w-full h-80 object-cover rounded-lg border"
                >
            @else
                <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-gray-500">Tidak ada gambar</span>
                </div>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-4">
                <div>
                    <h4 class="text-sm font-semibold text-gray-500">Status</h4>
                    <p class="text-lg font-normal">{{ $sarpras->status_sarpras }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500">Lokasi</h4>
                    <p class="text-lg font-normal">{{ $sarpras->lokasi ?? '-' }}</p>
                </div>

                @if($sarpras->jenis_sarpras == 'Ruangan')
                <div>
                    <h4 class="text-sm font-semibold text-gray-500">Kapasitas</h4>
                    <p class="text-lg font-normal">{{ $sarpras->kapasitas_ruangan }} Orang</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500">Kode Ruangan</h4>
                    <p class="text-lg font-normal">{{ $sarpras->kode_ruangan }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Status</h4>
                    <span class="text-xs font-normal inline-block py-1 px-4 uppercase rounded-[5px] {{ $sarpras->status_sarpras == 'Tersedia' ? 'text-green-600 bg-green-200' : 'text-yellow-600 bg-yellow-200' }}">
                    {{ $sarpras->status_sarpras }}
                    </span>
                </div>
                @endif

                @if($sarpras->jenis_sarpras == 'Proyektor')
                <div>
                    <h4 class="text-sm font-semibold text-gray-500">Merk</h4>
                    <p class="text-lg font-normal">{{ $sarpras->merk ?? '-' }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500">kode proyektor</h4>
                    <p class="text-lg font-normal">{{ $sarpras->kode_proyektor}}</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 mb-1">Status</h4>
                    <span class="text-xs font-normal inline-block py-1 px-4 uppercase rounded-[5px] {{ $sarpras->status_sarpras == 'Tersedia' ? 'text-green-600 bg-green-200' : 'text-yellow-600 bg-yellow-200' }}">
                    {{ $sarpras->status_sarpras }}
                    </span>
                </div>
                @endif

                <div class="pt-4 border-t flex gap-2">
                    <a href="{{ route('admin.sarpras.edit_sarpras', $sarpras->id_sarpras) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg font-semibold text-sm hover:bg-blue-600 transition-colors">
                    Edit
                    </a>
                    <form action="{{ route('admin.sarpras.destroy', $sarpras->id_sarpras ?? '-') }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sarpras ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:bg-red-600 transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
