@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto bg-white shadow-sm rounded-xl border border-gray-100">
    <h2 class="text-lg font-semibold mb-6 text-gray-800 border-b pb-3">➕ Tambah Jadwal</h2>

    <form action="{{ route('jadwal.store') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Kode MK -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kode MK <span class="text-red-500">*</span>
            </label>
            <input type="text" name="kode_mk"
                   placeholder="Contoh: IF101"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition"
                   required>
        </div>

        <!-- Nama Kelas -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nama Kelas <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_kelas"
                   placeholder="Contoh: Pemrograman Web"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition"
                   required>
        </div>

        <!-- Kelas Mahasiswa -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kelas Mahasiswa <span class="text-red-500">*</span>
            </label>
            <input type="text" name="kelas_mahasiswa"
                   placeholder="Contoh: TI-3A"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition"
                   required>
        </div>

        <!-- Sebaran Mahasiswa -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Sebaran Mahasiswa <span class="text-red-500">*</span>
            </label>
            <input type="number" name="sebaran_mahasiswa"
                   placeholder="Contoh: 25"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition"
                   required min="1">
        </div>

        <!-- Hari & Ruangan -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Hari <span class="text-red-500">*</span>
                </label>
                <input type="text" name="hari"
                       placeholder="Contoh: Senin"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition"
                       required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Ruangan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="ruangan"
                       placeholder="Contoh: Lab 1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition"
                       required>
            </div>
        </div>

        <!-- Jam Mulai & Jam Selesai -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Jam Mulai <span class="text-red-500">*</span>
                </label>
                <input type="time" name="jam_mulai"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition"
                       required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Jam Selesai <span class="text-red-500">*</span>
                </label>
                <input type="time" name="jam_selesai"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition"
                       required>
            </div>
        </div>

        <!-- Daya Tampung -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Daya Tampung <span class="text-red-500">*</span>
            </label>
            <input type="number" name="daya_tampung"
                   placeholder="Contoh: 30"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition"
                   required min="1">
        </div>

        <!-- Tombol -->
        <div class="flex gap-3 pt-4">
            <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition focus:ring-2 focus:ring-blue-300">
                Simpan
            </button>
            <a href="{{ route('jadwal.index') }}"
               class="px-5 py-2.5 bg-gray-300 text-gray-800 font-medium rounded-lg hover:bg-gray-400 transition focus:ring-2 focus:ring-gray-200">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
