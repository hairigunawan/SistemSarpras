@extends('layouts.app')

@section('title', 'Tambah Ruangan Baru')

@section('content')
<div class="bg-white rounded-lg shadow-md w-full max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Tambah Ruangan Baru</h2>
        <a href="{{ route('sarpras.index') }}" class="flex text-gray-600 hover:text-gray-800">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m0 0l6 6m-6-6l6-6"/>
                </svg>
            </span>
            Kembali
        </a>
    </div>

    <form action="{{ route('sarpras.ruangan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">

            <!-- Nama Ruangan -->
            <div>
                <label for="nama_ruangan" class="block text-sm font-medium text-gray-700">Nama Ruangan</label>
                <input type="text" name="nama_ruangan" id="nama_ruangan" value="{{ old('nama_ruangan') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                @error('nama_ruangan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Kode Ruangan -->
            <div>
                <label for="kode_ruangan" class="block text-sm font-medium text-gray-700">Kode Ruangan</label>
                <input type="text" name="kode_ruangan" id="kode_ruangan" value="{{ old('kode_ruangan') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                @error('kode_ruangan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Kapasitas -->
            <div>
                <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas') }}" min="1" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                @error('kapasitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Lokasi -->
            <div>
                <label for="lokasi_id" class="block text-sm font-medium text-gray-700">Lokasi</label>
                <select name="lokasi_id" id="lokasi_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="">Pilih Lokasi</option>
                    @foreach($lokasiList as $id => $lokasi)
                        <option value="{{ $id }}" {{ old('lokasi_id') == $id ? 'selected' : '' }}>
                            {{ $lokasi }}
                        </option>
                    @endforeach
                </select>
                @error('lokasi_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="id_status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="id_status" id="id_status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="">Pilih Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id_status }}" {{ old('id_status') == $status->id_status ? 'selected' : '' }}>
                            {{ $status->nama_status }}
                        </option>
                    @endforeach
                </select>
                @error('id_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>


            <!-- Gambar -->
            <div>
                <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar</label>
                <input type="file" name="gambar" accept=".jpeg,.png,.jpg,.webp" id="gambar" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                @error('gambar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="text-right">
                <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg font-normal hover:bg-green-600 transition-colors">
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
