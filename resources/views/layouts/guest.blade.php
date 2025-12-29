<!DOCTYPE html>
<html lang="id">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMPERSITE')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body class="bg-gray-100 text-gray-800 scroll-smooth">
    <div class="min-h-screen flex  flex-col overflow-hidden">
        @include('layouts.partials.NavbarPublic')

        <main class="pt-20">
            @yield('content')
        </main>

        <footer class="bg-white border-t border-gray-200 py-8 mt-auto">
            <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} SIMPERSITE - Jurusan Teknologi Informasi Politeknik Negeri Tanah Laut. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full sm:max-w-md">
        @if($errors->any())
            <div class="notification notification-enter bg-red-500 text-white p-4 rounded-lg shadow-lg flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-sm mb-1">Error Validasi</h4>
                    <ul class="text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.remove()" class="flex-shrink-0 ml-2 hover:bg-red-600 rounded p-1 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"  class="notification notification-enter bg-red-500 text-white p-4 rounded-lg shadow-lg flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-sm mb-1">Error</h4>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="flex-shrink-0 ml-2 hover:bg-red-600 rounded p-1 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"  class="notification notification-enter bg-green-500 text-white p-4 rounded-lg shadow-lg flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-sm mb-1">Berhasil</h4>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="flex-shrink-0 ml-2 hover:bg-green-600 rounded p-1 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="notification notification-enter bg-yellow-500 text-white p-4 rounded-lg shadow-lg flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-sm mb-1">Peringatan</h4>
                    <p class="text-sm">{{ session('warning') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="flex-shrink-0 ml-2 hover:bg-yellow-600 rounded p-1 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div class="notification notification-enter bg-blue-500 text-white p-4 rounded-lg shadow-lg flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-sm mb-1">Informasi</h4>
                    <p class="text-sm">{{ session('info') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="flex-shrink-0 ml-2 hover:bg-blue-600 rounded p-1 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');

            notifications.forEach((notification, index) => {
                // Auto-hide setelah 3 detik
                setTimeout(() => {
                    notification.classList.remove('notification-enter');
                    notification.classList.add('notification-exit');

                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000 + (index * 200)); // Delay untuk multiple notifications
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
