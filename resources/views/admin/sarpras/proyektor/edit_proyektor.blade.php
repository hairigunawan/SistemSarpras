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
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('sarpras.proyektor.update', $proyektor->id_proyektor) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="space-y-4">

            <div>
                <label for="nama_proyektor" class="block text-sm font-medium text-gray-700">Nama Proyektor</label>
                <input type="text" name="nama_proyektor" id="nama_proyektor" value="{{ old('nama_proyektor', $proyektor->nama_proyektor) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                @error('nama_proyektor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="merk" class="block text-sm font-medium text-gray-700">Merk Proyektor</label>
                <input type="text" name="merk" id="merk" value="{{ old('merk', $proyektor->merk) }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                @error('merk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="kode_proyektor" class="block text-sm font-medium text-gray-700">Kode Proyektor</label>
                <input type="text" name="kode_proyektor" id="kode_proyektor" value="{{ old('kode_proyektor', $proyektor->kode_proyektor) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                @error('kode_proyektor') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="id_status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="id_status" id="id_status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    @foreach($statuses as $status)
                        <option value="{{ $status->id_status }}" {{ old('id_status', $proyektor->id_status) == $status->id_status ? 'selected' : '' }}>
                            {{ $status->nama_status }}
                        </option>
                    @endforeach
                </select>
                @error('id_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="gambar" class="block text-sm font-medium text-gray-700">Ubah Gambar</label>
                <div class="rounded border border-gray-500 border-dashed p-2">
                    @if($proyektor->gambar)
                        <img src="{{ asset('storage/' . $proyektor->gambar) }}" alt="{{ $proyektor->nama_proyektor }}" class="my-2 h-32 w-auto">
                    @endif
                    <input type="file" name="gambar" id="gambar" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-6 file:rounded-xl file:border-0 file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <small class="text-gray-500">Kosongkan jika tidak ingin mengubah gambar.</small>
                @error('gambar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg font-normal hover:bg-blue-600 transition-colors">
                    Update
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
