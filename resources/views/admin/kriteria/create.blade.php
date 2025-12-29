@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Tambah Kriteria Baru</h1>
                <p class="mt-2 text-sm text-gray-500 font-medium">Lengkapi detail kriteria untuk sistem pendukung keputusan.</p>
            </div>
            <a href="{{ route('admin.kriteria.index') }}" class="border border-gray-300 px-6 py-1.5 rounded-sm font-semibold text-gray-600 hover:text-gray-500 transition-colors">
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                
                {{-- Alert Error Menggunakan Gaya Modern yang Kita Buat Sebelumnya --}}
                @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                    class="mb-6 flex p-4 rounded-xl bg-rose-50 border border-rose-100 shadow-sm shadow-rose-100/50">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-bold text-rose-900 leading-none mb-1">Terjadi Kesalahan</p>
                        <ul class="text-xs text-rose-700 list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <form action="{{ route('admin.kriteria.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="group">
                        <label for="nama_kriteria" class="block text-sm font-bold text-gray-700 mb-2 transition-colors">
                            Nama Kriteria
                        </label>
                        <div class="relative">
                            <input type="text" id="nama_kriteria" name="nama_kriteria" value="{{ old('nama_kriteria') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-1 focus:bg-white transition-all duration-200 placeholder-gray-400"
                                placeholder="Contoh: Jenis Kegiatan, Jumlah Peserta" required>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 italic">Masukkan nama kriteria unik (maksimal 100 karakter).</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="group">
                            <label for="tipe" class="block text-sm font-bold text-gray-700 mb-2">
                                Tipe Kriteria
                            </label>
                            <select id="tipe" name="tipe" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-1 focus:bg-white transition-all duration-200">
                                <option value="" disabled selected>Pilih tipe...</option>
                                <option value="benefit" {{ old('tipe') === 'benefit' ? 'selected' : '' }}>Benefit (+)</option>
                                <option value="cost" {{ old('tipe') === 'cost' ? 'selected' : '' }}>Cost (-)</option>
                            </select>
                        </div>

                        <div class="group">
                            <label for="bobot" class="block text-sm font-bold text-gray-700 mb-2">
                                Bobot Kriteria
                            </label>
                            <div class="relative">
                                <input type="number" id="bobot" name="bobot" value="{{ old('bobot', '0.0000') }}" step="0.0001" min="0" max="1"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-1 focus:bg-white transition-all duration-200"
                                    placeholder="0.0000" required>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4 space-y-3 mt-4">
                        <div class="flex gap-3">
                            <svg class="h-5 w-5 text-[#1180ab] mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-xs text-[#1180ab] leading-relaxed">
                                <p><strong>Benefit:</strong> Semakin tinggi nilai semakin baik. <strong>Cost:</strong> Semakin rendah nilai semakin baik.</p>
                                <p class="mt-1 font-semibold text-[#1180ab] underline">Catatan: Pastikan total semua bobot kriteria berjumlah 1.0000.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.kriteria.index') }}"
                            class="px-6 py-3 text-xs font-semibold border border-gray-300 rounded-lg text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-wider">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-[#1180ab] hover:bg-[#0d7198] text-white text-xs font-semibold rounded-lg transform transition active:scale-95 uppercase tracking-widest flex items-center">
                            <i class="fas fa-save mr-2"></i> Simpan Kriteria
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection