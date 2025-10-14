<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMPERSITE')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/kalender.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen flex flex-col">
            <nav class="bg-white border-b py-3">
                <div class="flex mx-10 justify-between items-center">
                    <div class="flex gap-1 items-center">
                        <p class="rotate-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 14l-9 6l-9-6m18-4l-9 6l-9-6l9-6z"/>
                            </svg>
                        </p>
                        <h1 class="text-xl font-semibold text-gray-700">SIMPERSITE.</h1>
                    </div>
                    <div class="flex justify-end px-6 py-3 gap-10 items-center">
                        <ul class="flex space-x-6">
                            <li><a href="{{ route('public.beranda.index') }}" class="hover:text-blue-500 font-normal text-blue-600">Beranda</a></li>
                            <li><a href="{{ route('public.peminjaman.daftarpeminjaman') }}" class="hover:text-blue-500 font-normal">Peminjaman</a></li>
                            <li><a href="{{ route('public.peminjaman.create') }}" class="hover:text-blue-500 font-normal">Sarana & Prasarana</a></li>
                        </ul>
                        <p class="text-xl text-gray-300 font-light">|</p>
                        <div class="flex items-center">
                            @guest
                                <a href="{{ route('login') }}" class="font-semibold text-gray-700 hover:text-gray-300">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 border py-1 px-4 rounded-full border-gray-300 font-semibold text-gray-600 hover:text-gray-300">Register</a>
                                @endif

                            @else
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        Logout
                                    </button>
                                </form>
                            @endguest

                        </div>
                    </div>
                </div>
            </nav>

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="bg-white border-t">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <p class="text-center text-sm text-gray-500">© 2025 SIMPERSITE. All rights reserved.</p>
            </div>
        </footer>
    </div>

    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
</body>
</html>
