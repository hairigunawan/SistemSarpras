@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-semibold mb-4">Tambah Kriteria Baru</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.prioritas.storeKriteria') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Kriteria</label>
            <input type="text" name="nama_kriteria" required
                class="mt-1 block w-full border border-gray-300 rounded-md p-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tipe</label>
            <select name="tipe" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                <option value="">-- Pilih --</option>
                <option value="benefit">Benefit</option>
                <option value="cost">Cost</option>
            </select>
        </div>

        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Simpan
        </button>
    </form>
</div>
@endsection
