S<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarana & Prasarana</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white border-b py-3">
        <div class="flex mx-10 justify-between items-center">
            <div class="flex gap-1 items-center">
                <p class="rotate-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m21 14l-9 6l-9-6m18-4l-9 6l-9-6l9-6z"/>
                    </svg>
                </p>
                <h1 class="text-xl font-semibold text-gray-700">SIMPERSITE.</h1>
            </div>
            <div class="flex justify-end px-6 py-3 gap-10 items-center">
                <ul class="flex space-x-6">
                    <li><a href="/" class="hover:text-blue-500 font-normal">Beranda</a></li>
                    <li><a href="{{ route('public.peminjaman.daftarpeminjaman') }}" class="hover:text-blue-500 font-normal">Peminjaman</a></li>
                    <li><a href="{{ route('public.sarana.index') }}" class="hover:text-blue-500 font-normal text-blue-600">Sarana & Prasarana</a></li>
                </ul>
                <p class="text-xl text-gray-300 font-light">|</p>
                <div class="flex items-center">
                    @auth
                        <a href="{{ route('login') }}" class="font-semibold text-gray-700 hover:text-gray-300">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 border py-1 px-4 rounded-full border-gray-300 font-semibold text-gray-600 hover:text-gray-300">Register</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="text-center py-10">
        <h2 class="text-4xl font-bold mb-2">Sarana & Prasarana</h2>
        <p class="text-gray-600 text-sm mb-10">Informasi ketersediaan Ruangan dan Proyektor di Program Studi Teknologi Informasi</p>
        <a href="{{ route('public.peminjaman.create') }}" class="flex justify-center space-x-4">
            <button class="bg-blue-600 text-white px-5 py-2 font-medium rounded-md hover:bg-blue-700">Ajukan Peminjaman</button>
        </a>
    </section>

    <!-- Ruangan -->
    <section class="container mx-auto px-6 mb-4">
        <h3 class="text-xl font-semibold mb-3">Ruangan</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 rounded-xl border">
            <x-card-stat warna="green" label="Ruangan Tersedia" :jumlah="$RuanganTersedia" />
            <x-card-stat warna="red" label="Ruangan Terpakai" :jumlah="$RuanganTerpakai" />
            <x-card-stat warna="yellow" label="Ruangan Perbaikan" :jumlah="$RuanganPerbaikan" />
        </div>
    </section>

    <!-- Proyektor -->
    <section class="container mx-auto px-6 mb-8">
        <h3 class="text-xl font-semibold mb-3">Proyektor</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 rounded-xl border">
            <x-card-stat warna="green" label="Proyektor Tersedia" :jumlah="$ProyektorTersedia" />
            <x-card-stat warna="red" label="Proyektor Terpakai" :jumlah="$ProyektorTerpakai" />
            <x-card-stat warna="yellow" label="Proyektor Perbaikan" :jumlah="$ProyektorPerbaikan" />
        </div>
    </section>

    <!-- Laboratorium Terpakai -->
    <section class="container mx-auto px-6 mb-10">
        <h3 class="text-xl text-gray-700 font-semibold mb-4">Laboratorium Terpakai</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($labs as $lab)
            <div class="bg-white rounded-xl p-5 border border-gray-200">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="text-lg  text-gray-700 font-bold">{{ $lab['nama'] }}</h4>
                    <span class="bg-red-100 text-red-600 px-3 py-1 text-sm rounded-full font-medium">Terpakai</span>
                </div>
                <p><span class="font-semibold">Kelas:</span> {{ $lab['kelas'] }}</p>
                <p><span class="font-semibold">Mata Kuliah:</span> {{ $lab['matkul'] }}</p>
                <p><span class="font-semibold">Waktu:</span> {{ $lab['waktu'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

</body>
</html>
