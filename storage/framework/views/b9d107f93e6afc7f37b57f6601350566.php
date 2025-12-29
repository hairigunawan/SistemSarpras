<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SIMPERSITE</title>
  <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen flex flex-col mt-5 items-center justify-center bg-gradient-to-br from-blue-50 via-white to-blue-100">

  <div class="w-full max-w-5xl bg-white/90 backdrop-blur-lg rounded-2xl border border-gray-200 grid grid-cols-1 md:grid-cols-2 overflow-hidden animate-fadeIn">

    <div class="relative flex flex-col justify-center items-center text-white p-10 md:p-12 bg-gradient-to-br from-blue-600 to-indigo-600">

    <img src="<?php echo e(asset('storage/images/GKT.jpg')); ?>"
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

      <?php if(session('error')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
          <strong class="font-semibold">Error!</strong>
          <span class="block text-sm"><?php echo e(session('error')); ?></span>
        </div>
      <?php endif; ?>

      <?php if(session('success')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
          <strong class="font-semibold">Sukses!</strong>
          <span class="block text-sm"><?php echo e(session('success')); ?></span>
        </div>
      <?php endif; ?>

      <a href="<?php echo e(route('auth.google')); ?>"
         class="w-full flex justify-center items-center gap-2 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-600 hover:bg-gray-50 mb-5 transition">
        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
        Masuk dengan Google
      </a>

      <div class="flex items-center mb-5">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="mx-3 text-gray-400 text-xs">atau dengan Email</span>
        <div class="flex-grow border-t border-gray-300"></div>
      </div>

      <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-5">
        <?php echo csrf_field(); ?>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input id="email" name="email" type="email" placeholder="Email" value="<?php echo e(old('email')); ?>" required
            class="w-full border rounded-lg py-2.5 text-sm px-3 shadow-sm focus:bg-blue-50  transition">
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <div class="relative">
            <input id="password" name="password" placeholder="Password" type="password" required
              class="w-full border rounded-lg py-2.5 text-sm px-3 shadow-sm focus:bg-blue-50  transition pr-10">
            <button type="button" id="toggleLoginPassword"
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
              <svg id="login-eye-icon" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
              <svg id="login-eye-slash-icon" class="h-4 w-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
              </svg>
            </button>
          </div>
        </div>

        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center text-gray-600">
            <input type="checkbox" class="mr-2 rounded border-gray-300">
            Ingat saya
          </label>
          <a href="<?php echo e(route('password.forgot')); ?>" class="text-blue-600 hover:underline">Lupa password?</a>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg shadow-md focus:ring-1 focus:ring-blue-300 transition">
          Masuk
        </button>

        <p class="text-center text-sm text-gray-600 mt-5">
          Belum punya akun?
          <a href="<?php echo e(route('register')); ?>" class="text-blue-600 font-semibold hover:text-blue-800 transition">Daftar Sekarang</a>
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
<script>
    const loginPasswordInput = document.getElementById('password');
    const toggleLoginPassword = document.getElementById('toggleLoginPassword');
    const loginEyeIcon = document.getElementById('login-eye-icon');
    const loginEyeSlashIcon = document.getElementById('login-eye-slash-icon');

toggleLoginPassword.addEventListener('click', function() {
    const type = loginPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    loginPasswordInput.setAttribute('type', type);

    // Toggle eye icons
    if (type === 'text') {
        loginEyeIcon.classList.add('hidden');
        loginEyeSlashIcon.classList.remove('hidden');
    } else {
        loginEyeIcon.classList.remove('hidden');
        loginEyeSlashIcon.classList.add('hidden');
    }
});
</script>
</html><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/auth/login.blade.php ENDPATH**/ ?>