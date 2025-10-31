@extends('layouts.guest')

@section('title', 'Form Peminjaman Sarpras')

@section('content')
<div>
    <div class="text-center py-10">
        <h2 class="text-4xl font-bold mb-2">Peminjaman Ruangan Dan Proyektor</h2>
        <p class="text-gray-600 text-sm mb-10">Selamat datang di portal layanan peminjaman sarana prasarana program studi teknologi informasi</p>
        @if (Auth::user()== null)
            <a href="{{ route('login') }}" class="flex justify-center space-x-4">
                <button class="bg-[#179ACE] text-white px-5 py-2 font-medium rounded-md hover:bg-[#0E7CBA]">Ajukan Peminjaman</button>
            </a>
        @else
        <a href="{{ route('public.peminjaman.create.auth') }}" class="flex justify-center space-x-4">
            <button class="bg-[#179ACE] text-white px-5 py-2 font-medium rounded-md hover:bg-[#0E7CBA]">Ajukan Peminjaman</button>
        </a>
        @endif
    </div>

    <!-- Statistik Ruangan -->
    <div class="container mx-auto px-10 mb-4">
        <h3 class="font-semibold mb-3">Ruangan</h3>
        <div class="flex justify-between px-10 rounded-xl border">
            <div class="flex items-center px-10 py-5 gap-3">
                <p class="text-green-500 text-4xl border p-2 rounded border-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"/>
                            <path d="m9 11l3 3L22 4"/>
                        </g>
                    </svg>
                </p>
                <div class="text-start">
                    <h4 class="text-2xl font-bold mt-2">{{ $RuanganTersedia ?? 0 }}</h4>
                    <p class="text-gray-500 text-sm">Ruangan Tersedia</p>
                </div>
            </div>
            <div class="flex items-center px-10 py-5 gap-3">
                <p class="text-red-500 text-4xl border rounded p-2 border-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <rect width="20" height="5" x="2" y="3" rx="1"/>
                            <path d="M4 8v11a2 2 0 0 0 2 2h2M20 8v11a2 2 0 0 1-2 2h-2m-7-6l3-3l3 3m-3-3v9"/>
                        </g>
                    </svg>
                </p>
                <div class="text-start">
                    <h4 class="text-2xl font-bold mt-2">{{ $RuanganTerpakai ?? 0 }}</h4>
                    <p class="text-gray-500 text-sm">Ruangan Terpakai</p>
                </div>
            </div>
            <div class="flex items-center px-10 py-5 gap-3">
                <p class="text-yellow-500 text-4xl border rounded p-2 border-yellow-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10 15l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M4 16.8v-5.348c0-.534 0-.801.065-1.05c.058-.22.152-.429.28-.617c.145-.213.346-.39.748-.741l4.801-4.202c.746-.652 1.119-.978 1.538-1.102c.37-.11.765-.11 1.135 0c.42.124.794.45 1.54 1.104l4.8 4.2c.403.352.603.528.748.74a2 2 0 0 1 .28.618c.065.248.065.516.065 1.05v5.352c0 1.118 0 1.677-.218 2.105a2 2 0 0 1-.874.873c-.428.218-.986.218-2.104.218H7.197c-1.118 0-1.678 0-2.105-.218a2 2 0 0 1-.874-.873C4 18.48 4 17.92 4 16.8"/>
                    </svg>
                </p>
                <div class="text-start">
                    <h4 class="text-2xl font-bold mt-2">{{ $RuanganPerbaikan ?? 0 }}</h4>
                    <p class="text-gray-500 text-sm">Ruangan Perbaikan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Proyektor -->
    <div class="container mx-auto px-10 mb-8">
        <h3 class="font-semibold mb-3">Proyektor</h3>
        <div class="flex justify-between px-10 rounded-xl border">
            <div class="flex items-center px-10 py-5 gap-3">
                <p class="text-green-500 text-4xl border p-2 rounded border-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"/>
                            <path d="m9 11l3 3L22 4"/>
                        </g>
                    </svg>
                </p>
                <div class="text-start">
                    <h4 class="text-2xl font-bold mt-2">{{ $ProyektorTersedia ?? 0 }}</h4>
                    <p class="text-gray-500 text-sm">Proyektor Tersedia</p>
                </div>
            </div>
            <div class="flex items-center px-10 py-5 gap-3">
                <p class="text-red-500 text-4xl border rounded p-2 border-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <rect width="20" height="5" x="2" y="3" rx="1"/>
                            <path d="M4 8v11a2 2 0 0 0 2 2h2M20 8v11a2 2 0 0 1-2 2h-2m-7-6l3-3l3 3m-3-3v9"/>
                        </g>
                    </svg>
                </p>
                <div class="text-start">
                    <h4 class="text-2xl font-bold mt-2">{{ $ProyektorTerpakai ?? 0 }}</h4>
                    <p class="text-gray-500 text-sm">Proyektor Terpakai</p>
                </div>
            </div>
            <div class="flex items-center px-10 py-5 gap-3">
                <p class="text-yellow-500 text-4xl border rounded p-2 border-yellow-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10 15l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M4 16.8v-5.348c0-.534 0-.801.065-1.05c.058-.22.152-.429.28-.617c.145-.213.346-.39.748-.741l4.801-4.202c.746-.652 1.119-.978 1.538-1.102c.37-.11.765-.11 1.135 0c.42.124.794.45 1.54 1.104l4.8 4.2c.403.352.603.528.748.74a2 2 0 0 1 .28.618c.065.248.065.516.065 1.05v5.352c0 1.118 0 1.677-.218 2.105a2 2 0 0 1-.874.873c-.428.218-.986.218-2.104.218H7.197c-1.118 0-1.678 0-2.105-.218a2 2 0 0 1-.874-.873C4 18.48 4 17.92 4 16.8"/>
                    </svg>
                </p>
                <div class="text-start">
                    <h4 class="text-2xl font-bold mt-2">{{ $ProyektorPerbaikan ?? 0 }}</h4>
                    <p class="text-gray-500 text-sm">Proyektor Perbaikan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal kelas dipakai -->
    <div class="container mx-auto px-6 mb-10">
        <h3 class="text-xl text-gray-700 font-semibold mb-4">Ruangan Terpakai</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($labs ?? [] as $lab)
                <div class="bg-white rounded-xl p-5 border border-gray-200">
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
    </d>
</div>
@endsection
