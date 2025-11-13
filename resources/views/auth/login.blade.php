<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SIMPERSITE</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 via-white to-blue-100">

  <div class="w-full max-w-5xl bg-white/90 backdrop-blur-lg rounded-2xl border border-gray-200 grid grid-cols-1 md:grid-cols-2 overflow-hidden animate-fadeIn">

    <div class="relative flex flex-col justify-center items-center text-white p-10 md:p-12 bg-gradient-to-br from-blue-600 to-indigo-600">

    <img src="{{ asset('storage/images/GKT.jpg') }}"
        alt="Gedung Kampus"
        class="absolute inset-0 w-full h-full object-cover opacity-90">

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative z-10 text-center">
        <h1 class="text-4xl font-bold mb-3">SIMPERSITE</h1>
        <p class="text-blue-100 text-sm">
        Sistem Peminjaman Sarana & Prasarana Kampus<br>
        untuk Prodi Teknologi Informasi
        </p>
    </div>
</div>

    <div class="p-8 md:p-10">
      <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Masuk ke Akun Anda</h2>
      <p class="text-gray-500 text-sm text-center mb-6">Gunakan email kampus untuk masuk ke sistem</p>

      @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
          <strong class="font-semibold">Error!</strong>
          <span class="block text-sm">{{ session('error') }}</span>
        </div>
      @endif

      <a href="{{ route('auth.google') }}"
         class="w-full flex justify-center items-center gap-2 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-600 hover:bg-gray-50 mb-5 transition">
        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
        Masuk dengan Google
      </a>

      <div class="flex items-center mb-5">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="mx-3 text-gray-400 text-xs">atau dengan Email</span>
        <div class="flex-grow border-t border-gray-300"></div>
      </div>

      <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input id="email" name="email" type="email" placeholder="Email" value="{{ old('email') }}" required
            class="w-full border rounded-lg py-2.5 text-sm px-3 shadow-sm focus:bg-blue-50  transition">
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input id="password" name="password" placeholder="Password" type="password"  required
            class="w-full border rounded-lg py-2.5 text-sm px-3 shadow-sm focus:bg-blue-50  transition">
        </div>

        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center text-gray-600">
            <input type="checkbox" class="mr-2 rounded border-gray-300">
            Ingat saya
          </label>
          <a href="#" class="text-blue-600 hover:underline">Lupa password?</a>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg shadow-md focus:ring-4 focus:ring-blue-300 transition">
          Masuk
        </button>

        <p class="text-center text-sm text-gray-600 mt-5">
          Belum punya akun?
          <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:text-blue-800 transition">Daftar Sekarang</a>
        </p>
      </form>
    </div>
  </div>

  <p class="text-gray-400 text-xs mt-6">© 2025 SIMPERSITE. All rights reserved.</p>

  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
      animation: fadeIn 0.6s ease-out;
    }
  </style>

</body>
</html>
