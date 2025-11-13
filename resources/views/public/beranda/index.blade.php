@extends('layouts.guest')

@section('title', 'Form Peminjaman Sarpras')

@section('content')
<div class="bg-gray-50 min-h-screen">

    {{-- Header Utama --}}
    <div class="text-center py-10">
        <h2 class="text-4xl font-bold mb-2">Peminjaman Ruangan dan Proyektor</h2>
        <p class="text-gray-600 text-sm mb-10">
            Selamat datang di portal layanan peminjaman sarana prasarana<br>
            Program Studi Teknologi Informasi Universitas XYZ.
        </p>
        @if (Auth::user() == null)
            <a href="{{ route('login') }}" class="inline-block">
                <button class="bg-[#179ACE] text-white px-6 py-2 font-medium rounded-md hover:bg-[#0E7CBA] transition">
                    Ajukan Peminjaman
                </button>
            </a>
        @else
            <a href="{{ route('public.peminjaman.create.auth') }}" class="inline-block">
                <button class="bg-[#179ACE] text-white px-6 py-2 font-medium rounded-md hover:bg-[#0E7CBA] transition">
                    Ajukan Peminjaman
                </button>
            </a>
        @endif
    </div>

    {{-- Section Identitas --}}
    <div class="flex items-center justify-center py-16 px-4">
        <div class="bg-white shadow-lg rounded-2xl p-10 text-center max-w-lg w-full">
            <img src="{{ asset('storage/images/GKT.jpg') }}" alt="Logo" class="mx-auto w-48 h-auto mb-5 rounded-xl">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Sistem Peminjaman Sarpras</h1>
            <p class="text-gray-500 mb-6 leading-relaxed">
                Program Studi Teknologi Informasi<br>
                Universitas XYZ
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}"
                   class="bg-[#179ACE] text-white px-6 py-2 rounded-md hover:bg-[#0E7CBA] transition font-medium">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 transition font-medium">
                    Daftar
                </a>
            </div>
        </div>
    </div>

    {{-- Statistik Ruangan --}}
    <div class="container mx-auto px-6 mb-10">
        <h3 class="font-semibold mb-4 text-lg">Statistik Ruangan</h3>
        <div class="flex flex-wrap justify-between gap-6 border rounded-xl p-6 bg-white shadow-sm">
            {{-- Ruangan Tersedia --}}
            <div class="flex items-center gap-3">
                <div class="text-green-500 border border-green-600 p-2 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"/>
                            <path d="m9 11l3 3L22 4"/>
                        </g>
                    </svg>
                </div>
                <div>
                    <h4 class="text-2xl font-bold">{{ $RuanganTersedia ?? 0 }}</h4>
                    <p class="text-gray-500 text-sm">Ruangan Tersedia</p>
                </div>
            </div>

            {{-- Ruangan Terpakai --}}
            <div class="flex items-center gap-3">
                <div class="text-red-500 border border-red-600 p-2 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <rect width="20" height="5" x="2" y="3" rx="1"/>
                            <path d="M4 8v11a2 2 0 0 0 2 2h2M20 8v11a2 2 0 0 1-2 2h-2m-7-6l3-3l3 3m-3-3v9"/>
                        </g>
                    </svg>
                </div>
                <div>
                    <h4 class="text-2xl font-bold">{{ $RuanganTerpakai ?? 0 }}</h4>
                    <p class="text-gray-500 text-sm">Ruangan Terpakai</p>
                </div>
            </div>

            {{-- Ruangan Perbaikan --}}
            <div class="flex items-center gap-3">
                <div class="text-yellow-500 border border-yellow-600 p-2 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m10 15l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M4 16.8v-5.348c0-.534 0-.801.065-1.05c.058-.22.152-.429.28-.617c.145-.213.346-.39.748-.741l4.801-4.202c.746-.652 1.119-.978 1.538-1.102c.37-.11.765-.11 1.135 0c.42.124.794.45 1.54 1.104l4.8 4.2c.403.352.603.528.748.74a2 2 0 0 1 .28.618c.065.248.065.516.065 1.05v5.352c0 1.118 0 1.677-.218 2.105a2 2 0 0 1-.874.873c-.428.218-.986.218-2.104.218H7.197c-1.118 0-1.678 0-2.105-.218a2 2 0 0 1-.874-.873C4 18.48 4 17.92 4 16.8"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-2xl font-bold">{{ $RuanganPerbaikan ?? 0 }}</h4>
                    <p class="text-gray-500 text-sm">Ruangan Perbaikan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Proyektor --}}
    <div class="container mx-auto px-6 mb-10">
        <h3 class="font-semibold mb-4 text-lg">Statistik Proyektor</h3>
        <div class="flex flex-wrap justify-between gap-6 border rounded-xl p-6 bg-white shadow-sm">
            <div class="flex items-center gap-3">
                <div class="text-green-500 border border-green-600 p-2 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"/>
                            <path d="m9 11l3 3L22 4"/>
                        </g>
                    </svg>
                </div>
                <div>
                    <h4 class="text-2xl font-bold">{{ $ProyektorTersedia ?? 0 }}</h4>
                    <p class="text-gray-500 text-sm">Proyektor Tersedia</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-red-500 border border-red-600 p-2 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <rect width="20" height="5" x="2" y="3" rx="1"/>
                            <path d="M4 8v11a2 2 0 0 0 2 2h2M20 8v11a2 2 0 0 1-2 2h-2m-7-6l3-3l3 3m-3-3v9"/>
                        </g>
                    </svg>
                </div>
                <div>
                    <h4 class="text-2xl font-bold">{{ $ProyektorTerpakai ?? 0 }}</h4>
                    <p class="text-gray-500 text-sm">Proyektor Terpakai</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-yellow-500 border border-yellow-600 p-2 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m10 15l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M4 16.8v-5.348c0-.534 0-.801.065-1.05c.058-.22.152-.429.28-.617c.145-.213.346-.39.748-.741l4.801-4.202c.746-.652 1.119-.978 1.538-1.102c.37-.11.765-.11 1.135 0c.42.124.794.45 1.54 1.104l4.8 4.2c.403.352.603.528.748.74a2 2 0 0 1 .28.618c.065.248.065.516.065 1.05v5.352c0 1.118 0 1.677-.218 2.105a2 2 0 0 1-.874.873c-.428.218-.986.218-2.104.218H7.197c-1.118 0-1.678 0-2.105-.218a2 2 0 0 1-.874-.873C4 18.48 4 17.92 4 16.8"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-2xl font-bold">{{ $ProyektorPerbaikan ?? 0 }}</h4>
                    <p class="text-gray-500 text-sm">Proyektor Perbaikan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Jadwal Ruangan Terpakai --}}
    <div class="container mx-auto px-6 pb-16">
        <h3 class="text-xl text-gray-700 font-semibold mb-4">Ruangan Terpakai</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($labs ?? [] as $lab)
                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="text-lg text-gray-700 font-bold">{{ $lab['nama'] }}</h4>
                        <span class="bg-red-100 text-red-600 px-3 py-1 text-sm rounded-full font-medium">Terpakai</span>
                    </div>
                    <p><span class="font-semibold">Kelas:</span> {{ $lab['kelas'] }}</p>
                    <p><span class="font-semibold">Mata Kuliah:</span> {{ $lab['matkul'] }}</p>
                    <p><span class="font-semibold">Waktu:</span> {{ $lab['waktu'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
