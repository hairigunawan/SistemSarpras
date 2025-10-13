@extends('layouts.app')

@section('title', 'Tambah Sarpras Baru')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-2xl mx-auto" x-data="{ jenis: '{{ old('jenis_sarpras', 'Ruangan') }}' }" x-cloak>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Tambah Sarpras Baru</h2>
        <a href="{{ route('admin.sarpras.index') }}" class="flex text-gray-600 hover:text-gray-800"><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m0 0l6 6m-6-6l6-6"/></svg></span>Kembali</a>
    </div>

    <form action="{{ route('admin.sarpras.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">

            <div>
                <label for="jenis_sarpras" class="block text-sm font-medium text-gray-700">Jenis Sarpras</label>
                <select name="jenis_sarpras" id="jenis_sarpras" x-model="jenis" class="mt-1 block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500">
                    <option value="Ruangan">Ruangan</option>
                    <option value="Proyektor">Proyektor</option>
                </select>
            </div>

            <!-- Ruangan Fields -->
            <div x-show="jenis === 'Ruangan'" x-transition class="space-y-4">
                <div>
                    <label for="nama_sarpras_ruangan" class="block text-sm font-medium text-gray-700">Nama Sarpras</label>
                    <input type="text" name="nama_sarpras" id="nama_sarpras_ruangan" value="{{ old('nama_sarpras') }}" x-bind:required="jenis === 'Ruangan'" x-bind:disabled="jenis !== 'Ruangan'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('nama_sarpras') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="kode_ruangan" class="block text-sm font-medium text-gray-700">Kode Ruangan</label>
                    <input type="text" name="kode_ruangan" id="kode_ruangan" value="{{ old('kode_ruangan') }}" x-bind:required="jenis === 'Ruangan'" x-bind:disabled="jenis !== 'Ruangan'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('kode_ruangan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="kapasitas_ruangan" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                    <input type="number" name="kapasitas_ruangan" id="kapasitas_ruangan" value="{{ old('kapasitas_ruangan') }}" x-bind:required="jenis === 'Ruangan'" x-bind:disabled="jenis !== 'Ruangan'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('kapasitas_ruangan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="lokasi_ruangan" class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi_ruangan" value="{{ old('lokasi') }}" x-bind:disabled="jenis !== 'Ruangan'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="status_ruangan" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status_ruangan" x-bind:disabled="jenis !== 'Ruangan'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        <option value="Tersedia" {{ old('status', 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dipinjam" {{ old('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Perbaikan" {{ old('status') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="gambar_ruangan" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" name="gambar" id="gambar_ruangan" x-bind:disabled="jenis !== 'Ruangan'" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    @error('gambar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Proyektor Fields -->
            <div x-show="jenis === 'Proyektor'" x-transition class="space-y-4">
                <div>
                    <label for="nama_sarpras_proyektor" class="block text-sm font-medium text-gray-700">Nama Sarpras</label>
                    <input type="text" name="nama_sarpras" id="nama_sarpras_proyektor" value="{{ old('nama_sarpras') }}" x-bind:required="jenis === 'Proyektor'" x-bind:disabled="jenis !== 'Proyektor'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('nama_sarpras') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="kode_proyektor" class="block text-sm font-medium text-gray-700">Kode Proyektor</label>
                    <input type="text" name="kode_proyektor" id="kode_proyektor" value="{{ old('kode_proyektor') }}" x-bind:required="jenis === 'Proyektor'" x-bind:disabled="jenis !== 'Proyektor'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('kode_proyektor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="merk" class="block text-sm font-medium text-gray-700">Merk Proyektor</label>
                    <input type="text" name="merk" id="merk" value="{{ old('merk') }}" x-bind:required="jenis === 'Proyektor'" x-bind:disabled="jenis !== 'Proyektor'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('merk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="lokasi_proyektor" class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi_proyektor" value="{{ old('lokasi') }}" x-bind:disabled="jenis !== 'Proyektor'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="status_proyektor" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status_proyektor" x-bind:disabled="jenis !== 'Proyektor'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        <option value="Tersedia" {{ old('status', 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dipinjam" {{ old('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Perbaikan" {{ old('status') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="gambar_proyektor" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" name="gambar" id="gambar_proyektor" x-bind:disabled="jenis !== 'Proyektor'" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    @error('gambar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Lainnya Fields -->
            <div x-show="jenis === 'Lainnya'" x-transition class="space-y-4">
                <div>
                    <label for="nama_sarpras_lainnya" class="block text-sm font-medium text-gray-700">Nama Sarpras</label>
                    <input type="text" name="nama_sarpras" id="nama_sarpras_lainnya" value="{{ old('nama_sarpras') }}" x-bind:required="jenis === 'Lainnya'" x-bind:disabled="jenis !== 'Lainnya'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('nama_sarpras') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="lokasi_lainnya" class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi_lainnya" value="{{ old('lokasi') }}" x-bind:disabled="jenis !== 'Lainnya'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="status_lainnya" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status_lainnya" x-bind:disabled="jenis !== 'Lainnya'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        <option value="Tersedia" {{ old('status', 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dipinjam" {{ old('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Perbaikan" {{ old('statuss') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                    </select>
                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="gambar_lainnya" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" name="gambar" id="gambar_lainnya" x-bind:disabled="jenis !== 'Lainnya'" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    @error('gambar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
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
