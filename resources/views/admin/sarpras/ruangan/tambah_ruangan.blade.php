@extends('layouts.app')

@section('title', 'Tambah Ruangan')

@section('content')
<div class="bg-white">
    <div class="bg-white p-6 w-full max-w-full mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Tambah Ruangan Baru</h2>
            <a href="{{ route('admin.sarpras.index') }}" class="flex text-gray-600 hover:text-gray-800">
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
                    <input type="text" text="lowercase first-letter:uppercase" name="nama_ruangan" id="nama_ruangan" placeholder="Lab C" value="{{ old('nama_ruangan') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" onblur="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)">
                    @error('nama_ruangan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Kode Ruangan -->
                <div>
                    <label for="kode_ruangan" class="block text-sm font-medium text-gray-700">Kode Ruangan</label>
                    <input type="text" name="kode_ruangan" id="kode_ruangan" placeholder="C-01" value="{{ old('kode_ruangan') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('kode_ruangan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Kapasitas -->
                <div>
                    <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                    <input type="number" name="kapasitas" id="kapasitas" placeholder="20" value="{{ old('kapasitas') }}" min="1" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @error('kapasitas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Lokasi -->
                <div>
                    <label for="lokasi_id" class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <select name="lokasi_id" id="lokasi_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                        <option value="">Pilih Lokasi</option>
                            @foreach($l as $lokasi)
                            <option value="{{ $lokasi->id_lokasi }}"
                                {{ old('id_lokasi', $defaultLokasiId) == $lokasi->id_lokasi ? 'selected' : '' }}>
                                {{ $lokasi->nama_lokasi }}
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
                        @foreach($s as $status)
                            <option value="{{ $status->id_status }}"
                                {{ old('id_status', $defaultStatusId) == $status->id_status ? 'selected' : '' }}>
                                {{ $status->nama_status }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div x-data="{
                        previewUrl: null,
                        previewFile(event) {
                            const file = event.target.files[0];
                            if (file) {
                                this.previewUrl = URL.createObjectURL(file);
                            }
                        }
                    }">

                    <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar</label>

                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">

                            <!-- Preview Image -->
                            <template x-if="previewUrl">
                                <img :src="previewUrl" class="mx-auto h-40 object-cover rounded-md" />
                            </template>

                            <!-- Default Icon (hilang ketika ada gambar) -->
                            <svg x-show="!previewUrl" class="mx-auto h-12 w-12 text-gray-400"
                                stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                            <!-- Upload Button -->
                            <div class="flex text-sm text-gray-600">
                                <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Upload file</span>
                                    <input id="gambar" name="gambar" type="file" class="sr-only"
                                        accept=".jpeg,.png,.jpg,.webp"
                                        @change="previewFile">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>

                            <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                        </div>
                    </div>

                    @error('gambar')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="text-right">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg font-normal hover:bg-blue-600 transition-colors">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
