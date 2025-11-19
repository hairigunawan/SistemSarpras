@extends('layouts.app')

@section('title', 'Tolak Peminjaman')

@section('content')
<div class="max-w-full mx-auto bg-white rounded-xl shadow-md overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.peminjaman.lihat_peminjaman', $peminjaman->id_peminjaman) }}" class="flex gap-2 text-xl items-center text-gray-800 font-semibold mb-2 sm:mb-0 hover:text-indigo-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="text-indigo-600">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 18l-6-6l6-6"/>
                </svg>
                <span>Tolak Peminjaman</span>
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="px-6 py-8">
        <form action="{{ route('peminjaman.reject', $peminjaman->id_peminjaman) }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- Informasi Peminjaman -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Peminjaman</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nama Peminjam</p>
                            <p class="font-medium text-gray-700">{{ $peminjaman->nama_peminjam ?? $peminjaman->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium text-gray-700">{{ $peminjaman->user->email ?? 'Tidak diketahui' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jadwal Pinjam</p>
                            <p class="font-medium text-gray-700">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}<br>
                                {{ date('H:i', strtotime($peminjaman->jam_mulai)) }} - {{ date('H:i', strtotime($peminjaman->jam_selesai)) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Sarana dan Perasarana</p>
                            <p class="font-medium text-gray-700">
                                @if($peminjaman->ruangan)
                                    {{ $peminjaman->ruangan->nama_ruangan }}
                                @elseif($peminjaman->proyektor)
                                    {{ $peminjaman->proyektor->nama_proyektor }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alasan Penolakan -->
            <div class="mb-8">
                <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="alasan_penolakan"
                    name="alasan_penolakan"
                    rows="6"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Masukkan alasan penolakan..."
                    required
                >{{ old('alasan_penolakan') }}</textarea>
                @error('alasan_penolakan')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center">
                <a href="{{ route('admin.peminjaman.lihat_peminjaman', $peminjaman->id_peminjaman) }}"
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" class="inline mr-2">
                        <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
                    </svg>
                    Tolak Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
