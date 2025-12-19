@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                Tambah Jadwal Perkuliahan
            </h2>
            <p class="mt-1 text-sm text-gray-500">Isi formulir berikut untuk menambahkan jadwal mata kuliah baru ke dalam sistem.</p>
        </div>

        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <!-- Section 1: Informasi Mata Kuliah -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-blue-600 uppercase tracking-wide border-b border-blue-100 pb-2 mb-4">Informasi Mata Kuliah</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode MK -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode MK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_mk"
                               placeholder="Contoh: IF101"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition placeholder-gray-400"
                               required>
                    </div>

                    <!-- Sistem Kuliah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Sistem Kuliah <span class="text-red-500">*</span>
                        </label>
                        <select name="sistem_kuliah"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition bg-white"
                                required>
                            <option value="" disabled selected>Pilih Sistem Kuliah</option>
                            <option value="Reguler">Reguler</option>
                            <option value="Non Reguler">Non Reguler</option>
                        </select>
                    </div>

                    <!-- Nama Kelas -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Mata Kuliah / Kelas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kelas"
                               placeholder="Contoh: Pemrograman Web Lanjut"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition placeholder-gray-400"
                               required>
                    </div>
                </div>
            </div>

            <!-- Section 2: Detail Peserta -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-blue-600 uppercase tracking-wide border-b border-blue-100 pb-2 mb-4">Detail Peserta & Kelas</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Kelas Mahasiswa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kelas Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kelas_mahasiswa"
                               placeholder="Contoh: TI-3A"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition placeholder-gray-400"
                               required>
                    </div>

                    <!-- Sebaran Kelas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Sebaran Kelas<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="sebaran_kelas"
                               placeholder="Contoh: Kelas 3B"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition placeholder-gray-400"
                               required>
                    </div>

                    <!-- Daya Tampung -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kuota / Daya Tampung <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="daya_tampung"
                               placeholder="Contoh: 30"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition placeholder-gray-400"
                               required min="1">
                    </div>
                </div>
            </div>

            <!-- Section 3: Waktu & Lokasi -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-blue-600 uppercase tracking-wide border-b border-blue-100 pb-2 mb-4">Waktu & Lokasi</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Hari & Ruangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Hari <span class="text-red-500">*</span>
                        </label>
                        <select name="hari"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition bg-white"
                                required>
                            <option value="" disabled selected>Pilih Hari</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ruangan <span class="text-red-500">*</span>
                        </label>
                        <select name="ruangan"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition bg-white"
                                required>
                            <option value="" disabled selected>Pilih Ruangan</option>
                            @foreach($ruangans as $ruangan)
                                <option value="{{ $ruangan->nama_ruangan }}">{{ $ruangan->nama_ruangan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jam Mulai & Selesai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="jam_mulai"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="jam_selesai"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition"
                               required>
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 mt-6">
                <a href="{{ route('admin.jadwal.index') }}"
                   class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-gray-200 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500 shadow-sm shadow-blue-200 transition">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
