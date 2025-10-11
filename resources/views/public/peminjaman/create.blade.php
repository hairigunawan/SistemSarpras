@extends('layouts.guest')

@section('title', 'Form Peminjaman Sarpras')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Form Pengajuan Peminjaman Sarpras</h1>
            <p class="text-gray-600 mt-2">Silakan isi form berikut untuk mengajukan peminjaman sarana dan prasarana.</p>
        </div>

        <form action="{{ route('public.peminjaman.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Informasi Peminjam -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Peminjam</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_peminjam" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama_peminjam" id="nama_peminjam" value="{{ old('nama_peminjam') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                        @error('nama_peminjam')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email_peminjam" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email_peminjam" id="email_peminjam" value="{{ old('email_peminjam') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                        @error('email_peminjam')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telepon_peminjam" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" name="telepon_peminjam" id="telepon_peminjam" value="{{ old('telepon_peminjam') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                        @error('telepon_peminjam')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Peminjaman -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Peminjaman</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="id_sarpras" class="block text-sm font-medium text-gray-700">Sarana/Prasarana yang Dipinjam</label>
                        <select name="id_sarpras" id="id_sarpras"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            <option value="">Pilih Sarpras</option>
                            @foreach($sarprasTersedia as $sarpras)
                                <option value="{{ $sarpras->id_sarpras }}" {{ old('id_sarpras') == $sarpras->id_sarpras ? 'selected' : '' }}>
                                    {{ $sarpras->nama_sarpras }} ({{ $sarpras->jenis }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_sarpras')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_pinjam')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" id="tanggal_kembali" value="{{ old('tanggal_kembali') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                        @error('tanggal_kembali')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                        @error('jam_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                        @error('jam_selesai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan/Tujuan Peminjaman</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Jelaskan tujuan peminjaman...">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('public.beranda.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md font-medium transition duration-200">
                    Batal
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition duration-200">
                    Ajukan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Simple validation to ensure tanggal_kembali >= tanggal_pinjam
document.getElementById('tanggal_pinjam').addEventListener('change', function() {
    document.getElementById('tanggal_kembali').min = this.value;
});

document.getElementById('tanggal_kembali').addEventListener('change', function() {
    if (this.value < document.getElementById('tanggal_pinjam').value) {
        this.setCustomValidity('Tanggal kembali harus setelah atau sama dengan tanggal pinjam');
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endsection
