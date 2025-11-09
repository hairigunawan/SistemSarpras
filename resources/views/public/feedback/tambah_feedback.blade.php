@extends('layouts.guest')

@section('title', 'Tambah Feedback')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Feedback</h2>
        
        @if ($ruangan)
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">Detail Ruangan</h3>
                <div class="flex items-center gap-4">
                    <img src="{{ asset('storage/' . $ruangan->gambar) }}" alt="{{ $ruangan->nama_ruangan }}" 
                         class="w-24 h-24 rounded-lg object-cover">
                    <div>
                        <p class="font-medium text-gray-800">{{ $ruangan->nama_ruangan }}</p>
                        <p class="text-sm text-gray-600">{{ $ruangan->lokasi->nama_lokasi }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($proyektor)
            <div class="mb-6 p-4 bg-green-50 rounded-lg">
                <h3 class="text-lg font-semibold text-green-800 mb-2">Detail Proyektor</h3>
                <div class="flex items-center gap-4">
                    <img src="{{ asset('storage/' . $proyektor->gambar) }}" alt="{{ $proyektor->nama_proyektor }}" 
                         class="w-24 h-24 rounded-lg object-cover">
                    <div>
                        <p class="font-medium text-gray-800">{{ $proyektor->nama_proyektor }}</p>
                        <p class="text-sm text-gray-600">Merk: {{ $proyektor->merk }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('public.feedback.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <input type="hidden" name="id_sarpras" value="{{ $id_sarpras }}">
            <input type="hidden" name="type" value="{{ $sarpras_type }}">
            <input type="hidden" name="id_peminjaman" value="{{ $id_peminjaman }}">

            <div>
                <label for="isi_feedback" class="block text-sm font-medium text-gray-700 mb-2">
                    Isi Feedback <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="isi_feedback" 
                    name="isi_feedback" 
                    rows="6" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Berikan feedback Anda tentang sumber daya yang dipinjam..."
                    required>{{ old('isi_feedback') }}</textarea>
                @error('isi_feedback')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Minimal 10 karakter, maksimal 1000 karakter</p>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('public.feedback.index', ['id_sarpras' => $id_sarpras, 'type' => $sarpras_type]) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                    Kirim Feedback
                </button>
            </div>
        </form>
    </div>
</div>
@endsection