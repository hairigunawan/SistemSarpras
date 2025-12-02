@extends('layouts.app')

@section('title', 'Edit Bobot')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 py-10 bg-white">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Edit Bobot</h1>
        <a href="{{ route('admin.prioritas.bobot.index') }}"
           class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
            Kembali
        </a>
    </div>

    <!-- Card Form -->
    <div class="rounded-xl shadow-sm p-6">

        {{-- Error --}}
        @if($errors->any())
            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-600 rounded-lg text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.prioritas.bobot.update', $bobot->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Bobot -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Nama Bobot
                </label>
                <input type="text" name="nama" required
                    value="{{ old('nama', $bobot->nama) }}"
                    class="w-full px-4 rounded-lg border border-gray-300 py-2.5
                    text-sm"
                    placeholder="Masukkan nama kriteria">
            </div>

            <!-- Nilai Bobot -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase">
                    Nilai Bobot (0 - 1)
                </label>
                <input type="number" name="nilai"
                    step="0.01" min="0" max="1" required
                    value="{{ old('nilai', $bobot->nilai) }}"
                    class="w-full px-4 rounded-lg border border-gray-300 py-2.5 text-sm"
                    placeholder="0.00">
                <p class="text-xs text-gray-500 mt-1">Pastikan nilai total bobot tidak melebihi 1.00</p>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase">
                    Keterangan
                </label>
                <textarea name="keterangan_bobot" rows="4"
                    class="w-full px-4 rounded-lg border border-gray-300 py-2.5
                    text-sm"
                    placeholder="Berikan keterangan (opsional)">{{ old('keterangan_bobot', $bobot->keterangan_bobot) }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.prioritas.bobot.index') }}"
                    class="px-5 py-2.5 text-sm font-semibold rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                    Update
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
