<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - SIMPERSITE</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white border-b py-3">
        <div class="flex mx-10 justify-between items-center">
            <div class="flex gap-1 items-center">
                <p class="rotate-6"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 14l-9 6l-9-6m18-4l-9 6l-9-6l9-6z"/></svg>
                </p>
                <h1 class="text-xl font-semibold text-gray-700">SIMPERSITE.</h1>
            </div>
            <div class="flex justify-end px-6 py-3 gap-10 items-center">
                <ul class="flex space-x-6">
                    <li><a href="{{ route('landing') }}" class="hover:text-blue-500 font-normal">Beranda</a></li>
                    <li><a href="{{ route('public.peminjaman.create') }}" class="hover:text-blue-500 font-normal">Peminjaman</a></li>
                    <li><a href="#" class="hover:text-blue-500 font-normal">Sarana & Prasarana</a></li>
                </ul>
                <p class="text-xl text-gray-300 font-light">|</p>
                <div class="flex items-center">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 font-semibold text-gray-700 hover:text-blue-600">
                            <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&background=random" alt="User Avatar">
                            <span>{{ Auth::user()->nama }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 border border-gray-200">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-blue-600 bg-blue-50 hover:bg-blue-100">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </a>
                            <a href="{{ route('public.peminjaman.riwayat') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Riwayat Peminjaman
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success Message -->
    @if(session('success'))
        <div class="container mx-auto px-6 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Profile Content -->
    <section class="container mx-auto px-6 py-10">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6">Profile Pengguna</h2>

            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center mb-8">
                    <img class="h-24 w-24 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&background=random&size=200" alt="User Avatar">
                    <div class="ml-6">
                        <h3 class="text-2xl font-semibold text-gray-800">{{ Auth::user()->nama }}</h3>
                        <p class="text-gray-600">{{ Auth::user()->role->nama_role }}</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold mb-4">Informasi Akun</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <div class="bg-gray-50 px-4 py-3 rounded-md border border-gray-200">
                                {{ Auth::user()->nama }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <div class="bg-gray-50 px-4 py-3 rounded-md border border-gray-200">
                                {{ Auth::user()->email }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <div class="bg-gray-50 px-4 py-3 rounded-md border border-gray-200">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if(Auth::user()->role->nama_role === 'Admin') bg-purple-100 text-purple-800
                                    @elseif(Auth::user()->role->nama_role === 'Dosen') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ Auth::user()->role->nama_role }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bergabung Sejak</label>
                            <div class="bg-gray-50 px-4 py-3 rounded-md border border-gray-200">
                                {{ Auth::user()->created_at->format('d F Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t mt-8 pt-6">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('landing') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            ← Kembali ke Beranda
                        </a>
                        <a href="{{ route('public.peminjaman.riwayat') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 font-medium">
                            Lihat Riwayat Peminjaman
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
