@extends('layouts.app')

@section('title', 'Tambah Sarpras Baru')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-2xl mx-auto" x-data="{ jenis: '{{ old('jenis_sarpras', 'Ruangan') }}' }">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Tambah Sarpras Baru</h2>
        <a href="{{ route('sarpras.index') }}" class="text-gray-600 hover:text-gray-800">&larr; Kembali</a>
    </div>

    <form action="{{ route('sarpras.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">

            <div>
                <label for="jenis_sarpras" class="block text-sm font-medium text-gray-700">Jenis Sarpras</label>
                <select name="jenis_sarpras" id="jenis_sarpras" x-model="jenis" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                    <option value="Ruangan">Ruangan</option>
                    <option value="Proyektor">Proyektor</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label for="nama_sarpras" class="block text-sm font-medium text-gray-700">Nama Sarpras</label>
                <input type="text" name="nama_sarpras" id="nama_sarpras" value="{{ old('nama_sarpras') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                @error('nama_sarpras') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div x-show="jenis === 'Ruangan'" x-transition class="space-y-4">
                <div>
                    <label for="kode_ruangan" class="block text-sm font-medium text-gray-700">Kode Ruangan</label>
                    <input type="text" name="kode_ruangan" id="kode_ruangan" value="{{ old('kode_ruangan') }}" x-bind:required="jenis === 'Ruangan'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('kode_ruangan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas (Orang)</label>
                    <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas') }}" x-bind:required="jenis === 'Ruangan'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('kapasitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div x-show="jenis === 'Proyektor'" x-transition class="space-y-4">
                <div>
                    <label for="kode_proyektor" class="block text-sm font-medium text-gray-700">Kode Proyektor</label>
                    <input type="text" name="kode_proyektor" id="kode_proyektor" value="{{ old('kode_proyektor') }}" x-bind:required="jenis === 'Proyektor'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('kode_proyektor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="merk" class="block text-sm font-medium text-gray-700">Merk Proyektor</label>
                    <input type="text" name="merk" id="merk" value="{{ old('merk') }}" x-bind:required="jenis === 'Proyektor'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('merk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar</label>
                <input type="file" name="gambar" id="gambar" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                @error('gambar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="text-right">
                <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-600 transition-colors">
                    Simpan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
