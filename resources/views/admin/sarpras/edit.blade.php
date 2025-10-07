@extends('layouts.app')

@section('title', 'Edit Sarpras')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-2xl mx-auto" x-data="{ jenis: '{{ old('jenis_sarpras', $sarpra->jenis_sarpras) }}' }">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Edit Sarpras</h2>
        <a href="{{ route('sarpras.index') }}" class="flex text-gray-600 hover:text-gray-800"><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m0 0l6 6m-6-6l6-6"/></svg></span>Kembali</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('sarpras.update', $sarpra->id_sarpras) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="space-y-4">

            <div>
                <label for="jenis_sarpras" class="block text-sm font-medium text-gray-700">Jenis Sarpras</label>
                <select name="jenis_sarpras" id="jenis_sarpras" x-model="jenis" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="Ruangan" @selected(old('jenis_sarpras', $sarpra->jenis_sarpras) == 'Ruangan')>Ruangan</option>
                    <option value="Proyektor" @selected(old('jenis_sarpras', $sarpra->jenis_sarpras) == 'Proyektor')>Proyektor</option>
                    <option value="Lainnya" @selected(old('jenis_sarpras', $sarpra->jenis_sarpras) == 'Lainnya')>Lainnya</option>
                </select>
            </div>

            <div>
                <label for="nama_sarpras" class="block text-sm font-medium text-gray-700">Nama Sarpras</label>
                <input type="text" name="nama_sarpras" id="nama_sarpras" value="{{ old('nama_sarpras', $sarpra->nama_sarpras) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>

            <div x-show="jenis === 'Ruangan'" x-transition class="space-y-4">
                <div>
                    <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas (Orang)</label>
                    <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas', $sarpra->kapasitas) }}" x-bind:required="jenis === 'Ruangan'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="kode_ruangan" class="block text-sm font-medium text-gray-700">Kode Ruangan</label>
                    <input type="text" name="kode_ruangan" id="kode_ruangan" value="{{ old('kode_ruangan', $sarpra->kode_ruangan) }}" x-bind:required="jenis === 'Ruangan'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            <div x-show="jenis === 'Proyektor'" x-transition class="space-y-4">
                <div>
                    <label for="merk" class="block text-sm font-medium text-gray-700">Merk Proyektor</label>
                    <input type="text" name="merk" id="merk" value="{{ old('merk', $sarpra->merk) }}" x-bind:required="jenis === 'Proyektor'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="kode_proyektor" class="block text-sm font-medium text-gray-700">Kode Proyektor</label>
                    <input type="text" name="kode_proyektor" id="kode_proyektor" value="{{ old('kode_proyektor', $sarpra->kode_proyektor) }}" x-bind:required="jenis === 'Proyektor'" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                </div>
            </div>

            <div>
                <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $sarpra->lokasi) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="Tersedia" @selected(old('status', $sarpra->status) == 'Tersedia')>Tersedia</option>
                    <option value="Dipinjam" @selected(old('status', $sarpra->status) == 'Dipinjam')>Dipinjam</option>
                    <option value="Penuh" @selected(old('status', $sarpra->status) == 'Penuh')>Penuh</option>
                    <option value="Perbaikan" @selected(old('status', $sarpra->status) == 'Perbaikan')>Perbaikan</option>
                </select>
            </div>
            <div>
                <label for="gambar" class="block text-sm font-medium text-gray-700">Ubah Gambar</label>
                @if($sarpra->gambar)
                    <img src="{{ asset('storage/' . $sarpra->gambar) }}" alt="{{ $sarpra->nama_sarpras }}" class="my-2 h-32 w-auto rounded">
                @endif
                <input type="file" name="gambar" id="gambar" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                <small class="text-gray-500">Kosongkan jika tidak ingin mengubah gambar.</small>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-600 transition-colors">
                    Update
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
