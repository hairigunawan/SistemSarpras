<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SIMPERSITE</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 relative">

  {{-- Card Login --}}
  <div class="bg-white shadow-xl rounded-2xl p-8 w-[90%] max-w-md">
    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800 mb-1">Login</h1>
      <p class="text-gray-500 text-sm">Masuk untuk mengelola sarana & prasarana</p>
    </div>

    {{-- Tombol Google --}}
    <a href="{{ route('auth.google') }}"
       class="w-full flex justify-center items-center gap-2 py-2.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-600 hover:bg-gray-50 mb-5">
      <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
      Masuk dengan Google
    </a>

    {{-- Divider --}}
    <div class="flex items-center mb-5">
      <div class="flex-grow border-t border-gray-300"></div>
      <span class="mx-3 text-gray-400 text-xs">atau dengan Email</span>
      <div class="flex-grow border-t border-gray-300"></div>
    </div>

    {{-- Form Login --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
      @csrf
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" required
          class="w-full border rounded-md py-2 px-3 mt-1 focus:ring-1 focus:ring-blue-500 focus:outline-none">
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input id="password" name="password" type="password" required
          class="w-full border rounded-md py-2 px-3 mt-1 focus:ring-1 focus:ring-blue-500 focus:outline-none">
      </div>

      <div class="flex items-center justify-between text-sm">
        <label class="flex items-center text-gray-600">
          <input type="checkbox" class="mr-2 rounded border-gray-300">
          Ingat saya
        </label>
        <a href="#" class="text-blue-600 hover:underline">Lupa password?</a>
      </div>

      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition">
        Masuk
      </button>

      <p class="text-center text-sm text-gray-500 mt-4">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">Daftar Sekarang</a>
      </p>
    </form>
  </div>

  {{-- Footer kecil --}}
  <p class="text-gray-400 text-xs mt-6">© 2025 SIMPERSITE. All rights reserved.</p>

</body>
</html>
