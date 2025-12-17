<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIMPERSITE</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="min-h-screen flex flex-col justify-between bg-gradient-to-br from-blue-50 to-white">

    <!-- ================= MAIN CARD ================= -->
    <div class="flex-grow w-full flex items-center justify-center p-4">
        <div class="w-full max-w-5xl bg-white rounded-2xl border border-gray-200 grid grid-cols-1 md:grid-cols-2 overflow-hidden shadow-sm">

            <!-- ================= LEFT SIDE ================= -->
            <div class="relative h-72 md:h-auto flex items-center justify-center text-white">

                <img src="<?php echo e(asset('storage/images/GKT.jpg')); ?>"
                     class="absolute inset-0 w-full h-full object-cover opacity-90">

                <div class="absolute inset-0 bg-black/40"></div>

                <div class="relative z-10 text-center px-6">
                    <h1 class="text-4xl font-bold mb-3">SIMPERSITE</h1>
                    <p class="text-blue-100 text-sm leading-relaxed">
                        Sistem Peminjaman Sarana & Prasarana Kampus<br>
                        untuk Prodi Teknologi Informasi
                    </p>
                </div>
            </div>

            <!-- ================= RIGHT SIDE (FORM) ================= -->
            <div class="p-10">

                <h2 class="text-3xl font-bold text-gray-900 mb-2">Buat Akun Baru</h2>
                <p class="text-gray-600 mb-6">Gunakan email kampus untuk mendaftar sistem</p>

                <!-- Google -->
                <a href="<?php echo e(route('auth.google')); ?>"
                   class="w-full flex items-center justify-center border rounded-xl py-3 text-gray-700 font-medium shadow-sm hover:bg-gray-50 transition">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5">
                    <span class="ml-3">Daftar dengan Google</span>
                </a>

                <!-- Divider -->
                <div class="flex items-center my-6">
                    <div class="flex-grow border-t"></div>
                    <span class="mx-3 text-gray-500 text-sm">atau daftar dengan Email</span>
                    <div class="flex-grow border-t"></div>
                </div>

                <!-- FORM -->
                <form method="POST" action="<?php echo e(route('register')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-4">
                        <label class="font-medium text-gray-700 text-sm">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?php echo e(old('nama')); ?>"
                            class="w-full border rounded-xl px-4 py-3 mt-1 text-sm shadow-sm focus:ring-1 focus:ring-blue-400 outline-none"
                            placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="mb-4">
                        <label class="font-medium text-gray-700 text-sm">Email</label>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>"
                            class="w-full border rounded-xl px-4 py-3 mt-1 text-sm shadow-sm focus:ring-1 focus:ring-blue-400 outline-none"
                            placeholder="example@email.com" required>
                    </div>

                    <div class="mb-4">
                        <label class="font-medium text-gray-700 text-sm">Nomor WhatsApp</label>
                        <input type="tel" name="nomor_telepon" value="<?php echo e(old('nomor_telepon')); ?>"
                            class="w-full border rounded-xl px-4 py-3 mt-1 text-sm shadow-sm focus:ring-1 focus:ring-blue-400 outline-none"
                            placeholder="081234567890" required>
                    </div>

                    <div class="mb-4">
                        <label class="font-medium text-gray-700 text-sm">Daftar Sebagai</label>
                        <select name="role"
                                class="w-full border rounded-xl px-4 py-3 mt-1 text-sm shadow-sm focus:ring-1 focus:ring-blue-400 outline-none"
                                required>
                            <option value="">Pilih Role</option>
                            <option value="Dosen">Dosen</option>
                            <option value="Mahasiswa">Mahasiswa</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="font-medium text-gray-700 text-sm">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="w-full border rounded-xl px-4 py-3 mt-1 text-sm shadow-sm focus:ring-1 focus:ring-blue-400 outline-none pr-12"
                                placeholder="Minimal 8 Karakter" required>
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                                <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eye-slash-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="font-medium text-gray-700 text-sm">Konfirmasi Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full border rounded-xl px-4 py-3 mt-1 text-sm shadow-sm focus:ring-1 focus:ring-blue-400 outline-none pr-12"
                                placeholder="Ulangi Password" required>
                            <button type="button" id="toggleConfirmPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                                <svg id="eye-confirm-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eye-confirm-slash-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 text-sm hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-md transition">
                        Daftar
                    </button>
                </form>

                <p class="text-gray-600 mt-6 text-center text-sm">
                    Sudah punya akun?
                    <a href="<?php echo e(route('login')); ?>" class="text-blue-600 font-semibold hover:underline">
                        Masuk Sekarang
                    </a>
                </p>

            </div>
        </div>
    </div>

    <p class="text-center text-gray-500 text-sm pb-4">
        © 2025 SIMPERSITE. All rights reserved.
    </p>

    <?php echo app('Illuminate\Foundation\Vite')('resources/js/hidenPassword.js'); ?>
</body>
</html>
<?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/auth/register.blade.php ENDPATH**/ ?>