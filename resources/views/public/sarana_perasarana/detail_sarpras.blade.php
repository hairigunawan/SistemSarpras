@extends('layouts.guest')

@section('title', 'Detail Sarana & Prasarana')

@section('content')
<div class="bg-gray-50 min-h-screen py-8 font-sans text-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <a href="{{ route('public.sarana_perasarana.halamansarpras') }}"
               class="group inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#179ACE] transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
            </a>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- KOLOM KIRI: KARTU PROFIL (Seperti Gambar "Community Pool") --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    {{-- Gambar --}}
                    <div class="aspect-w-4 aspect-h-3 bg-gray-200 relative h-64">
                        @if($sarpras->gambar)
                            <img src="{{ asset('storage/' . str_replace('public/', '', $sarpras->gambar)) }}"
                                 alt="{{ $sarpras->nama_ruangan ?? $sarpras->nama_proyektor }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 flex-col">
                                <i class="fa-regular fa-image text-4xl mb-2"></i>
                                <span>Tidak Ada Gambar</span>
                            </div>
                        @endif
                        {{-- Badge Tipe --}}
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur text-gray-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm uppercase tracking-wide">
                                {{ $type === 'ruangan' ? 'Ruangan' : 'Proyektor' }}
                            </span>
                        </div>
                    </div>

                    {{-- Info Singkat --}}
                    <div class="p-5">
                        <div class="flex justify-between">
                            <h1 class="text-xl font-bold text-gray-700 mb-1">
                                {{ $sarpras->nama_ruangan ?? $sarpras->nama_proyektor ?? 'Nama Tidak Diketahui' }}
                            </h1>
                            <div>
                                @if(!$mainPeminjaman || $mainPeminjaman->status_peminjaman === 'Tersedia' || $resourceStatus === 'Tersedia')
                                        <a href="{{ route('public.peminjaman.create', ['sarpras_type' => $type, 'sarpras_id' => $sarpras->id_ruangan ?? $sarpras->id_proyektor]) }}"
                                        class="block w-full text-center text-xs px-4 py-2.5 border hover:text-[#179ACE] hover:border-[#179ACE] hover:bg-white text-gray-700 bg-gray-200 font-medium rounded-lg transition-colors">
                                            Ajukan Peminjaman
                                        </a>
                                    @else
                                        <button disabled class="block w-full px-4 py-3 bg-gray-100 text-gray-400 font-semibold rounded-lg cursor-not-allowed border border-gray-200">
                                            Sedang Dipakai
                                        </button>
                                    @endif
                            </div>
                        </div>

                        {{-- Lokasi / Alamat --}}
                        <div class="text-sm text-gray-500 mb-4 flex items-start">
                             <i class="fa-solid fa-location-dot mt-1 text-gray-400"></i>
                             <span>{{ $sarpras->merk }}</span>
                        </div>

                        {{-- Rating Summary (Visual Mockup sesuai gambar) --}}
                        <div class="flex items-center mb-6">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="mx-1.5 text-gray-300">•</span>
                            <span class="text-sm text-gray-500">{{ $feedbacks->count() }} Reviews</span>
                        </div>

                        <hr class="border-gray-100 mb-6">

                        <div class="space-y-3">
                            {{-- Status Badge Besar --}}
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-600">Status:</span>
                                @if($mainPeminjaman)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $mainPeminjaman->status_peminjaman }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $resourceStatus === 'Tersedia' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                        {{ $resourceStatus }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-gray-700 mb-4">Spesifikasi</h3>
                        <div class="space-y-3">
                            @if($type === 'ruangan')
                                <div class="flex justify-between border-b border-gray-50 pb-2 last:border-0">
                                    <span class="text-sm text-gray-500">Kapasitas</span>
                                    <span class="text-sm font-medium text-gray-700">{{ $sarpras->kapasitas ?? '-' }} Orang</span>
                                </div>
                            @else
                                <div class="flex justify-between border-b border-gray-50 pb-2">
                                    <span class="text-sm text-gray-500">Merk</span>
                                    <span class="text-sm font-medium text-gray-700">{{ $sarpras->merk ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-50 pb-2 last:border-0">
                                    <span class="text-sm text-gray-500">Kode Aset</span>
                                    <span class="text-sm font-medium text-gray-700">{{ $sarpras->kode_proyektor ?? '-' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: DESKRIPSI & FEEDBACK (Area Utama) --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Deskripsi --}}
                @if(!empty($sarpras->deskripsi))
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-700 text-lg mb-3">Tentang Fasilitas Ini</h3>
                        <div class="text-gray-600 leading-relaxed text-sm">
                            {{ $sarpras->deskripsi }}
                        </div>
                    </div>
                @endif

                {{-- FEEDBACK SECTION (Didesain ulang sesuai referensi gambar) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                    {{-- Header Feedback --}}
                    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white sticky top-0 z-10">
                        <h3 class="text-xl font-bold text-gray-700">All Feedback</h3>

                        @if(Auth::check())
                            <a href="{{ route('public.feedback.index', ['id_sarpras' => $sarpras->id_ruangan ?? $sarpras->id_proyektor, 'type' => $type]) }}"
                               class="inline-flex items-center px-4 py-2 bg-[#4285F4] hover:bg-[#3367D6] text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <i class="fa-solid fa-plus mr-2"></i> Submit Feedback
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-[#179ACE] hover:underline font-medium">
                                Login untuk review
                            </a>
                        @endif
                    </div>

                    {{-- List Feedback --}}
                    <div class="divide-y divide-gray-100">
                        @if($feedbacks && $feedbacks->count() > 0)
                            @foreach($feedbacks as $feedback)
                                <div class="p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            {{-- Avatar --}}
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm">
                                                {{ strtoupper(substr($feedback->user->nama, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-700 text-sm">{{ $feedback->user->nama }}</h4>
                                                <p class="text-xs text-gray-400">{{ $feedback->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        {{-- Bintang (Visual Mockup - karena di DB mungkin belum ada rating angka) --}}
                                        <div class="flex text-yellow-400 text-xs">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                    </div>

                                    <div class="pl-13 mt-2">
                                        <p class="text-gray-600 text-sm leading-relaxed">
                                            {{ $feedback->isi_feedback }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-center items-center">
                                {{ $feedbacks->onEachSide(1)->links() }}
                            </div>

                        @else
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-regular fa-comment-dots text-gray-300 text-2xl"></i>
                                </div>
                                <h3 class="text-gray-700 font-medium">Belum ada ulasan</h3>
                                @if($type === 'proyektor')
                                    <p class="text-gray-500 text-sm mt-1">Berikan Feedback Untuk Memperbaiki Proyektor Ini.</p>
                                @else
                                    <p class="text-gray-500 text-sm mt-1">Berikan Feedback Untuk Memperbaiki Ruangan Ini.</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
