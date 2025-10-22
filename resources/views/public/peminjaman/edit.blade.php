@extends('layouts.guest')

@section('title', 'Form Edit Peminjaman Sarpras')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl w-full">
        <div class="backdrop-blur-xl bg-white/70 border border-gray-200 shadow-2xl rounded-3xl overflow-hidden transition-all hover:shadow-3xl">
            <div class="p-8 sm:p-10">
                {{-- Header --}}
                <div class="text-center mb-10">
                    <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                        Formulir Edit Peminjaman Sarpras
                    </h2>
                    <p class="mt-3 text-base text-gray-600">
                        Perbarui detail peminjaman fasilitas ini.
                    </p>
                </div>

                {{-- Form --}}
                <form action="{{ route('public.peminjaman.update', $peminjaman->id_peminjaman) }}" method="POST" class="space-y-10">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_sarpras" value="{{ $peminjaman->id_sarpras }}">

                    {{-- Informasi Peminjam --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Informasi Peminjam</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" id="nama"
                                       value="{{ $peminjaman->nama_peminjam ?? '-' }}"
                                       class="mt-2 w-full rounded-xl bg-gray-100 border border-gray-200 px-4 py-3 shadow-sm focus:ring-0 cursor-not-allowed text-gray-700"
                                       readonly>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                                <input type="email" id="email"
                                       value="{{ $peminjaman->email_peminjam ?? '-'}}"
                                       class="mt-2 w-full rounded-xl bg-gray-100 border border-gray-200 px-4 py-3 shadow-sm focus:ring-0 cursor-not-allowed text-gray-700"
                                       readonly>
                            </div>
                            <div class="md:col-span-2">
                                <label for="nomor_whatsapp" class="block text-sm font-medium text-gray-700">Nomor Telepon (WhatsApp)</label>
                                <input type="text" name="nomor_whatsapp" id="nomor_whatsapp"
                                       value="{{ old('nomor_whatsapp', $peminjaman->nomor_whatsapp) }}"
                                       class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Contoh: 081234567890" required>
                                @error('nomor_whatsapp') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Detail Peminjaman --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Detail Peminjaman</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="id_sarpras" class="block text-sm font-medium text-gray-600">Sarana/Prasarana yang Dipinjam</label>
                                <select name="id_sarpras" id="id_sarpras" class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-blue-500  focus:ring-blue-500"
                                        required>
                                    <option value="">Pilih Sarpras</option>
                                    @foreach($sarprasTersedia as $sarpras)
                                        <option value="{{ $sarpras->id_sarpras }}" {{ (old('id_sarpras', $peminjaman->id_sarpras) == $sarpras->id_sarpras) ? 'selected' : '' }}>
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
                                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                                       value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam) }}"
                                       class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       min="{{ date('Y-m-d') }}" required>
                                @error('tanggal_pinjam') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                            <div>
                                <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700">Tanggal Kembali</label>
                                <input type="date" name="tanggal_kembali" id="tanggal_kembali"
                                       value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali) }}"
                                       class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       min="{{ date('Y-m-d') }}" required>
                                @error('tanggal_kembali') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                            <div>
                                <label for="jam_mulai" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai"
                                       value="{{ old('jam_mulai', $peminjaman->jam_mulai) }}"
                                       class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                @error('jam_mulai') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                            <div>
                                <label for="jam_selesai" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="jam_selesai"
                                       value="{{ old('jam_selesai', $peminjaman->jam_selesai) }}"
                                       class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                @error('jam_selesai') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="jumlah_peserta" class="block text-sm font-medium text-gray-700">Jumlah Peserta</label>
                                <input type="number" name="jumlah_peserta" id="jumlah_peserta"
                                       value="{{ old('jumlah_peserta', $peminjaman->jumlah_peserta) }}"
                                       class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Masukkan estimasi jumlah peserta" required>
                                @error('jumlah_peserta') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan / Keperluan</label>
                                <textarea name="keterangan" id="keterangan" rows="4"
                                          class="mt-2 w-full rounded-xl border-gray-300 px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Contoh: Digunakan untuk kelas Pemrograman Web Lanjutan" required>{{ old('keterangan', $peminjaman->keterangan) }}</textarea>
                                @error('keterangan') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex justify-end pt-6 border-t border-gray-200 space-x-4">
                        <a href="{{ url()->previous() }}"
                           class="px-6 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold transition duration-200 shadow-sm">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md transition-transform duration-200 hover:scale-[1.02]">
                            Perbarui Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
