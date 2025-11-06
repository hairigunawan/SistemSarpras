@extends('layouts.guest')

@section('title', 'Form Peminjaman Sarpras')

@vite(['resources/js/app.js'])

@section('content')
<div>
<div class="pt-10">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class=" text-gray-700">
                <div class="text-center pt-5">
                    <h1 class="text-3xl text-gray-700 font-bold mb-2">Formulir Peminjaman Sarpras</h1>
                    <p class=" text-gray-500 max-w-2xl mx-auto">
                        Lengkapi detail berikut untuk mengajukan peminjaman fasilitas sarana dan prasarana.
                    </p>
                </div>
            </div>

            {{-- Form --}}
            <div class="px-6 py-8 sm:px-10">
                <form action="{{ route('public.peminjaman.store') }}" method="POST" id="peminjamanForm">
                    @csrf
                    {{-- Informasi Peminjam --}}
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Informasi Peminjam</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nama Lengkap --}}
                            <div class="md:col-span-2">
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap
                                </label>
                                <div class="relative">
                                    <input type="text" id="nama"
                                        value="{{ Auth::check() ? (Auth::user()->name ?? Auth::user()->nama) : '' }}"
                                        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700 cursor-not-allowed focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition"
                                        readonly>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </div>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat Email
                                </label>
                                <div class="relative">
                                    <input type="email" id="email"
                                        value="{{ Auth::check() ? Auth::user()->email : '' }}"
                                        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700 cursor-not-allowed focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition"
                                        readonly>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Nomor WhatsApp --}}
                            <div>
                                <label for="nomor_whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon (WhatsApp)
                                </label>
                                <div class="relative">
                                    <input type="text" name="nomor_whatsapp" id="nomor_whatsapp"
                                        value="{{ old('nomor_whatsapp', Auth::check() ? Auth::user()->telepon : '') }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                                        placeholder="Contoh: 081234567890" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('nomor_whatsapp')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Detail Peminjaman --}}
                    <div class="mb-10">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Detail Peminjaman</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Sarana/Prasarana
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="id_ruangan" class="block text-sm font-medium text-gray-700 mb-2">
                                            Ruangan
                                        </label>
                                        <div class="relative">
                                            <select name="id_ruangan" id="id_ruangan"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                                                <option value="">Pilih Ruangan</option>
                                                @foreach($ruanganTersedia as $ruangan)
                                                    <option value="{{ $ruangan->id_ruangan }}" {{ (old('id_ruangan') == $ruangan->id_ruangan || ($selectedSarprasType == 'ruangan' && $selectedSarprasId == $ruangan->id_ruangan)) ? 'selected' : '' }}>
                                                        {{ $ruangan->nama_ruangan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('id_ruangan')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="id_proyektor" class="block text-sm font-medium text-gray-700 mb-2">
                                            Proyektor
                                        </label>
                                        <div class="relative">
                                            <select name="id_proyektor" id="id_proyektor"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                                                <option value="">Pilih Proyektor</option>
                                                @foreach($proyektorTersedia as $proyektor)
                                                    <option value="{{ $proyektor->id_proyektor }}" {{ (old('id_proyektor') == $proyektor->id_proyektor || ($selectedSarprasType == 'proyektor' && $selectedSarprasId == $proyektor->id_proyektor)) ? 'selected' : '' }}>
                                                        {{ $proyektor->nama_proyektor }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('id_proyektor')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        
                            <div>
                                <label for="jumlah_peserta" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Peserta
                                </label>
                                <div class="relative">
                                    <input type="number" name="jumlah_peserta" id="jumlah_peserta"
                                        value="{{ old('jumlah_peserta') }}"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                                        placeholder="Masukkan estimasi jumlah peserta" min="1" required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('jumlah_peserta')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Lokasi Peminjaman Proyektor (hanya ditampilkan jika proyektor dipilih) --}}
                            <div id="lokasi_proyektor_container" style="display: none;">
                                <label for="lokasi_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Lokasi Peminjaman Proyektor
                                </label>
                                <div>
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
                                @error('lokasi_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tanggal Pinjam & Kembali --}}
                            <div class="md:col-span-2">
                                <label for="tanggal_pinjam" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Periode Peminjaman
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Tanggal Pinjam -->
                                    <div>
                                        <div class="relative">
                                            <input
                                                type="date"
                                                name="tanggal_pinjam"
                                                id="tanggal_pinjam"
                                                value="{{ old('tanggal_pinjam') }}"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                                                required
                                            >
                                        </div>
                                        @error('tanggal_pinjam')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Kembali -->
                                    <div>
                                        <div class="relative">
                                            <input
                                                type="date"
                                                name="tanggal_kembali"
                                                id="tanggal_kembali"
                                                value="{{ old('tanggal_kembali') }}"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                                                required
                                            >
                                        </div>
                                        @error('tanggal_kembali')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Jam Mulai & Selesai --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Penggunaan
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="relative">
                                            <input type="time" name="jam_mulai" id="jam_mulai"
                                                value="{{ old('jam_mulai') }}"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                                                required>
                                        </div>
                                        @error('jam_mulai')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <div class="relative">
                                            <input type="time" name="jam_selesai" id="jam_selesai"
                                                value="{{ old('jam_selesai') }}"
                                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                                                required>
                                        </div>
                                        @error('jam_selesai')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Peminjam (akan diisi oleh JavaScript) --}}
                    <div id="peminjamInfo" class="mb-4"></div>

                    <div>
                        <label for="jenis_kegiatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kegiatan / Keperluan
                        </label>
                        <div class="relative">
                            <select name="jenis_kegiatan" id="jenis_kegiatan" aria-placeholder="Pilih Kegiatan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Pilih Jenis Kegiatan</option>
                                <option value="Seminar Tugas Akhir">Seminar Tugas Akhir</option>
                                <option value="Seminar PKL">Seminar PKL</option>
                                <option value="Kelas Materi">Kelas Materi</option>
                                <option value="Kelas Praktikum">Kelas Praktikum</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        @error('jenis_kegiatan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3 pt-6">
                        <a href="{{ route('public.beranda.index.auth') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200 focus:ring-offset-2 transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const proyektorSelect = document.getElementById('id_proyektor');
        const lokasiProyektorContainer = document.getElementById('lokasi_proyektor_container');

        function toggleLokasiProyektor() {
            if (proyektorSelect.value) {
                lokasiProyektorContainer.style.display = 'block';
            } else {
                lokasiProyektorContainer.style.display = 'none';
            }
        }

        proyektorSelect.addEventListener('change', toggleLokasiProyektor);

        // Initial check on page load
        toggleLokasiProyektor();
    });
</script>
@endpush
