@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto bg-white shadow rounded-lg">
    <h2 class="text-lg font-semibold mb-4 text-gray-800">✏️ Edit Jadwal</h2>

    {{-- Form Edit --}}
            <form action="{{ route('jadwal.update', $jadwal) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Kode MK --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Kode Mata Kuliah</label>
                    <input type="text" name="kode_mk" value="{{ old('kode_mk', $jadwal->kode_mk) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                {{-- Nama Kelas --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama Kelas</label>
                    <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $jadwal->nama_kelas) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                {{-- Kelas Mahasiswa --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Kelas Mahasiswa</label>
                    <input type="text" name="kelas_mahasiswa" value="{{ old('kelas_mahasiswa', $jadwal->kelas_mahasiswa) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                {{-- Sebaran Mahasiswa --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Sebaran Mahasiswa</label>
                    <input type="number" name="sebaran_mahasiswa" value="{{ old('sebaran_mahasiswa', $jadwal->sebaran_mahasiswa) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                {{-- Hari --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Hari</label>
                    <select name="hari" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Pilih Hari --</option>
                        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat'] as $hari)
                            <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>
                                {{ $hari }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jam Mulai & Selesai --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                </div>

                {{-- Ruangan --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Ruangan</label>
                    <input type="text" name="ruangan" value="{{ old('ruangan', $jadwal->ruangan) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                {{-- Daya Tampung --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Daya Tampung</label>
                    <input type="number" name="daya_tampung" value="{{ old('daya_tampung', $jadwal->daya_tampung) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                {{-- Tombol --}}
                <div class="flex justify-between mt-8">
                    <a href="{{ route('jadwal.index') }}"
                        class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300 transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
@endsection