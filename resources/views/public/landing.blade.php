<div class="absolute top-0 right-0 p-6">
    @auth
        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900">Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900">Log in</a>

        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900">Register</a>
        @endif
    @endauth
</div>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center px-4">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-5xl font-bold text-gray-900 mb-6">
            Sistem Informasi Manajemen<br>
            <span class="text-blue-600">Sarana & Prasarana</span>
        </h1>

        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            Platform modern untuk mengelola dan meminjam sarana serta prasarana kampus dengan mudah dan efisien.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('public.peminjaman.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                🚀 Ajukan Peminjaman
            </a>

            @auth
                <a href="{{ route('admin.dashboard') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition duration-200 shadow-lg hover:shadow-xl">
                    📊 Dashboard Admin
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition duration-200 shadow-lg hover:shadow-xl">
                    🔐 Login Admin
                </a>
            @endauth
        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-3xl mb-4">📚</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Mudah Digunakan</h3>
                <p class="text-gray-600">Interface yang intuitif untuk semua pengguna</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-3xl mb-4">⚡</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Proses Cepat</h3>
                <p class="text-gray-600">Pengajuan dan approval dalam hitungan hari</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-3xl mb-4">📱</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Akses 24/7</h3>
                <p class="text-gray-600">Ajukan peminjaman kapan saja dari mana saja</p>
            </div>
        </div>
    </div>
</div>
