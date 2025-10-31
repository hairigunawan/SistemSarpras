<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMPERSITE')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body class="bg-gray-100 text-gray-800 ">
    <div class="min-h-screen flex flex-col overflow-hidden">

        @include('layouts.partials.NavbarPublic')

        <main class="flex-1">
            @yield('content')
        </main>

        <footer class="bg-white border-t">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <p class="text-center text-sm text-gray-500">© 2025 SIMPERSITE. All rights reserved.</p>
            </div>
        </footer>
    </div>

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
