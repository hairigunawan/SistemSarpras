@extends('layouts.app')

@section('content')
<div class="max-w-2xl p-8 mx-auto my-8 bg-white border border-gray-100 shadow-md rounded-xl">
    <h2 class="mb-6 text-xl font-semibold tracking-tight text-gray-900">✏️ Edit Jadwal</h2>

    <form action="{{ route('admin.jadwal.update', $jadwal) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Kode MK --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Kode Mata Kuliah</label>
            <input type="text"
                   name="kode_mk"
                   value="{{ old('kode_mk', $jadwal->kode_mk) }}"
                   placeholder="Masukkan kode mata kuliah"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                   required>
        </div>

        {{-- Nama Kelas --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Nama Kelas</label>
            <input type="text"
                   name="nama_kelas"
                   value="{{ old('nama_kelas', $jadwal->nama_kelas) }}"
                   placeholder="Masukkan nama kelas"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                   required>
        </div>

        {{-- Kelas Mahasiswa --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Kelas Mahasiswa</label>
            <input type="text"
                   name="kelas_mahasiswa"
                   value="{{ old('kelas_mahasiswa', $jadwal->kelas_mahasiswa) }}"
                   placeholder="Masukkan kelas mahasiswa"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                   required>
        </div>

        {{-- Sebaran Mahasiswa --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Sebaran Mahasiswa</label>
            <input type="number"
                   name="sebaran_mahasiswa"
                   value="{{ old('sebaran_mahasiswa', $jadwal->sebaran_mahasiswa) }}"
                   placeholder="Jumlah sebaran"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                   required>
        </div>

        {{-- Hari --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Hari</label>
            <select name="hari"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-700"
                    required>
                <option value="" class="text-gray-400">-- Pilih Hari --</option>
                @foreach (['Senin','Selasa','Rabu','Kamis','Jumat'] as $hari)
                    <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                @endforeach
            </select>
        </div>

        {{-- Jam Mulai & Selesai --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-600">Jam Mulai</label>
                <input type="time"
                       name="jam_mulai"
                       value="{{ old('jam_mulai', $jadwal->jam_mulai) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                       required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-600">Jam Selesai</label>
                <input type="time"
                       name="jam_selesai"
                       value="{{ old('jam_selesai', $jadwal->jam_selesai) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                       required>
            </div>
        </div>

        {{-- Ruangan --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Ruangan</label>
            <input type="text"
                   name="ruangan"
                   value="{{ old('ruangan', $jadwal->ruangan) }}"
                   placeholder="Nama ruangan"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                   required>
        </div>

        {{-- Daya Tampung --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Daya Tampung</label>
            <input type="number"
                   name="daya_tampung"
                   value="{{ old('daya_tampung', $jadwal->daya_tampung) }}"
                   placeholder="Jumlah daya tampung"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                   required>
        </div>

        {{-- Tombol --}}
        <div class="flex items-center justify-between mt-10">
            <a href="{{ route('admin.jadwal.index') }}"
               class="px-6 py-2 text-gray-500 transition bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200">Batal</a>
            <button type="submit"
                class="px-7 py-2.5 bg-blue-600 text-white rounded-lg font-semibold shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-200 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
