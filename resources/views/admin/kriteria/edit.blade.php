@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto my-10 px-4">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Kriteria</h1>
            <p class="text-sm text-gray-500">Perbarui detail dan bobot kriteria penilaian.</p>
        </div>
        <a href="{{ route('admin.kriteria.index') }}" class="text-sm text-gray-600 hover:text-[#0f7299] flex items-center gap-1 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white border border-gray-200 shadow-lg rounded-2xl overflow-hidden">

        <form action="{{ route('admin.kriteria.update', $kriteria) }}" method="POST" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            {{-- Error Message (Global) --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">Terjadi kesalahan pada input data. Mohon periksa kembali.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-6 w-full">
                <div class="w-full md:w-1/2">
                    <label for="nama_kriteria" class="block mb-2 text-sm font-bold text-gray-700">Nama Kriteria</label>
                    <div class="relative">
                        <input type="text"
                            id="nama_kriteria"
                            name="nama_kriteria"
                            value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}"
                            class="w-full pl-4 pr-4 py-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#0d7198] focus:border-blue-500 block transition-all @error('nama_kriteria') border-red-500 bg-red-50 @enderror"
                            placeholder="Contoh: IPK, Penghasilan Orang Tua" required>
                    </div>
                    @error('nama_kriteria')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full md:w-1/2">
                    <label for="bobot" class="block mb-2 text-sm font-bold text-gray-700">Bobot Kriteria</label>
                    <div class="relative">
                        <input type="number"
                            id="bobot"
                            name="bobot"
                            value="{{ old('bobot', $kriteria->bobot) }}"
                            step="0.0001" min="0" max="1"
                            class="w-full pl-4 pr-4 py-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#0d7198] focus:border-blue-500 block transition-all"
                            placeholder="0.0000" required>
                    </div>
                    @error('bobot')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div x-data="{
                    open: false,
                    selected: '{{ old('tipe', $kriteria->tipe) }}',
                    getLabel() {
                        if (this.selected === 'benefit') return 'Benefit (Semakin tinggi semakin baik)';
                        if (this.selected === 'cost') return 'Cost (Semakin rendah semakin baik)';
                        return 'Pilih Tipe Kriteria';
                    },
                    getColor() {
                        if (this.selected === 'benefit') return 'text-[#1180ab] bg-gray-50 border-gray-200';
                        if (this.selected === 'cost') return 'text-yellow-700 bg-gray-50 border-gray-200';
                        return 'text-gray-700 bg-gray-50 border-gray-300';
                    }
                }"
                class="relative">

                <label class="block mb-2 text-sm font-bold text-gray-700">Tipe Kriteria</label>
                <input type="hidden" name="tipe" x-model="selected">
                <button type="button"
                        @click="open = !open"
                        @click.outside="open = false"
                        :class="getColor()"
                        class="relative w-full py-2 pl-10 pr-10 text-left border rounded-lg focus:outline-none focus:ring-0.5 focus:ring-[#0d7198] transition-all duration-200">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        {{-- Ikon berubah sesuai pilihan --}}
                        <template x-if="selected === 'benefit'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#1180ab]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </template>
                        <template x-if="selected === 'cost'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                        </template>
                        <template x-if="!selected">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </template>
                    </div>
                    <span class="block truncate text-sm font-medium" x-text="getLabel()"></span>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 transition-transform duration-200"
                            :class="open ? 'transform rotate-180' : ''"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>

                <div x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-10 w-full mt-1 bg-white shadow-xl max-h-60 rounded-lg py-1 text-base ring-0.5 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                    style="display: none;">

                    {{-- Opsi: Benefit --}}
                    <div @click="selected = 'benefit'; open = false"
                        class="cursor-pointer select-none relative py-2 pl-10 pr-4 hover:bg-gray-50 transition-colors group border-b border-gray-50">
                        <span class="font-normal block truncate group-hover:text-gray-800"
                            :class="selected === 'benefit' ? 'font-semibold text-gray-700' : 'text-gray-900'">
                            Benefit (Semakin tinggi semakin baik)
                        </span>
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </span>
                        <span x-show="selected === 'benefit'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-600">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>

                    {{-- Opsi: Cost --}}
                    <div @click="selected = 'cost'; open = false"
                        class="cursor-pointer select-none relative py-2 pl-10 pr-4 hover:bg-gray-50 transition-colors group">
                        <span class="font-normal block truncate group-hover:text-gray-800"
                            :class="selected === 'cost' ? 'font-semibold text-gray-700' : 'text-gray-900'">
                            Cost (Semakin rendah semakin baik)
                        </span>
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                        </span>
                        <span x-show="selected === 'cost'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-600">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>

                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.kriteria.index') }}"
                   class="px-5 py-2.5 text-xs uppercase tracking-widest font-medium text-gray-700 bg-white border  border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-1 focus:outline-none focus:ring-gray-200 transition-all">
                   Batal
                </a>
                <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-xs uppercase tracking-widest font-medium text-white bg-[#1180ab] rounded-lg hover:bg-[#0d7198] focus:ring-1 focus:outline-none focus:ring-[#0d7198] shadow-lg shadow-blue-500/30 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
