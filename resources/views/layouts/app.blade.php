<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMPERSITE')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="flex h-screen">
        @include('layouts.partials.sidebar')

        <div class="flex-1 flex flex-col">
            <header class="bg-white pr-10 p-6 flex justify-end items-center">
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="font-semibold text-sm">{{ Auth::user()->nama ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'admin@gmail.com' }}</p>
                    </div>
                    <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama ?? 'Admin') }}&background=random" alt="User Avatar">
                </div>
            </header>

            <main class="flex-1 p-8 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
