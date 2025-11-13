@extends('layouts.app')

@section('title', 'Tambah Ruangan')

@section('content')
<<<<<<< HEAD
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
                    <input type="text" name="nama_ruangan" id="nama_ruangan" placeholder="Lab C" value="{{ old('nama_ruangan') }}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
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
                            <option value="{{ $status->id_status }}" {{ old('id_status', $defaultStatusId) == $status->id_status ? 'selected' : '' }}>
                                {{ $status->nama_status }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>


                <!-- Gambar -->
                <div>
                    <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" name="gambar" accept=".jpeg,.png,.jpg,.webp" id="gambar" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('gambar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
=======
    <div class="container mx-auto max-w-2xl">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Tambah Ruangan Baru</h1>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">Terjadi beberapa kesalahan dengan inputan Anda.</span>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sarpras.ruangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="nama_ruangan" class="block text-sm font-medium text-gray-700">Nama Ruangan</label>
                    <input type="text" name="nama_ruangan" id="nama_ruangan" value="{{ old('nama_ruangan') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <div class="mb-4">
                    <label for="kapasitas" class="block text-sm font-medium text-gray-700">Kapasitas</label>
                    <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </div>

                <div class="mb-4">
                    <label for="kode_ruangan" class="block text-sm font-medium text-gray-700">Kode Ruangan</label>
                    <input type="text" name="kode_ruangan" id="kode_ruangan" value="{{ old('kode_ruangan') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="lokasi_id" class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <select name="lokasi_id" id="lokasi_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        @foreach($lokasiList as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="id_status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="id_status" id="id_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id_status }}" @if($status->id_status == $defaultStatusId) selected @endif>{{ $status->nama_status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" name="gambar" id="gambar"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('sarpras.index') }}"
                       class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</a>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
>>>>>>> 875957528fdde76bf6bb75bff6e572a9a03689b2
