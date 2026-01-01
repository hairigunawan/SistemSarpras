@extends('layouts.app')

@section('title', 'Edit Proyektor')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 w-full max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Edit Proyektor</h2>
        <a href="{{ route('admin.sarpras.index') }}" class="flex text-gray-600 hover:text-gray-800">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m0 0l6 6m-6-6l6-6"/>
                </svg>
            </span>
            Kembali
        </a>
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
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"  class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('sarpras.proyektor.update', $p->id_proyektor) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="space-y-4">

            <div>
                <label for="nama_proyektor" class="block text-sm font-medium text-gray-700">Nama Proyektor</label>
                <input type="text" name="nama_proyektor" id="nama_proyektor" value="{{ old('nama_proyektor', $p->nama_proyektor) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                @error('nama_proyektor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="merk" class="block text-sm font-medium text-gray-700">Merk Proyektor</label>
                <input type="text" name="merk" id="merk" value="{{ old('merk', $p->merk) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                @error('merk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="kode_proyektor" class="block text-sm font-medium text-gray-700">Kode Proyektor</label>
                <input type="text" name="kode_proyektor" id="kode_proyektor" value="{{ old('kode_proyektor', $p->kode_proyektor) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                @error('kode_proyektor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="id_status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="id_status" id="id_status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @foreach($s as $status)
                        <option value="{{ $status->id_status }}" {{ old('id_status', $p->id_status) == $status->id_status ? 'selected' : '' }}>
                            {{ $status->nama_status }}
                        </option>
                    @endforeach
                </select>
                @error('id_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div x-data="{ photoName: null, photoPreview: null }">
                <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Ubah Gambar</label>
                
                <!-- Input File Tersembunyi -->
                <input type="file" name="gambar" id="gambar" class="hidden"
                       x-ref="photo"
                       x-on:change="
                            photoName = $refs.photo.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                photoPreview = e.target.result;
                            };
                            reader.readAsDataURL($refs.photo.files[0]);
                       ">

                <div class="flex items-start gap-4">
                    <!-- Gambar Saat Ini -->
                    <div x-show="!photoPreview" class="relative">
                        @if($p->gambar)
                            <img src="{{ Storage::url($p->gambar) }}" alt="{{ $p->nama_proyektor }}" class="h-32 w-auto object-cover rounded-md border border-gray-200 shadow-sm">
                            <p class="text-xs text-gray-500 mt-1 text-center">Gambar Saat Ini</p>
                        @else
                            <div class="h-32 w-32 bg-gray-100 flex items-center justify-center rounded-md border border-gray-200 text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-center">Tidak ada gambar</p>
                        @endif
                    </div>

                    <!-- Preview Gambar Baru -->
                    <div x-show="photoPreview" style="display: none;" class="relative">
                        <img :src="photoPreview" class="h-32 w-auto object-cover rounded-md border border-green-300 shadow-sm ring-2 ring-green-100">
                        <p class="text-xs text-green-600 mt-1 text-center font-medium">Preview Baru</p>
                    </div>
                </div>

                <!-- Tombol Pilih File -->
                <div class="mt-3">
                    <button type="button" x-on:click.prevent="$refs.photo.click()" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1180ab] transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Pilih Gambar Baru
                    </button>
                    <div x-show="photoName" class="mt-2 text-sm text-gray-500">
                        File terpilih: <span x-text="photoName" class="font-medium text-gray-800"></span>
                    </div>
                </div>
                
                @error('gambar') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="text-right">
                <button type="submit" class="bg-[#1180ab] text-white px-6 py-2 rounded-sm text-sm font-normal hover:bg-[#0d7198] transition-colors">
                    Update
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
