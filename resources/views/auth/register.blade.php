<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIMPERSITE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
    <body class="min-h-screen flex flex-col m-2 p-10 items-center justify-center">

    <div class="w-full max-w-5xl bg-white/90 backdrop-blur-lg rounded-2xl border border-gray-200 grid grid-cols-1 md:grid-cols-2 overflow-hidden animate-fadeIn">

        <div class="relative flex flex-col justify-center items-center text-white p-10 md:p-12 bg-gradient-to-br from-[#1180ab] to-indigo-600">

        <img src="{{ url('public/images/gedung TI1.jpeg') }}"
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

        <div class="flex-1 flex items-center justify-center p-4 sm:p-8 bg-gray-50">
            <div class="max-w-md w-full bg-white p-8 sm:p-10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">

                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                        Buat Akun Baru
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Silahkan lengkapi data diri Anda untuk mendaftar.
                    </p>
                </div>

                 @if(session('email_exists') && !session('email_verified'))
                 <div class="rounded-lg bg-blue-50 p-4 text-sm text-[#1180ab] border border-blue-100">
                     Email ini sudah terdaftar tapi belum diverifikasi.
                     <a href="{{ route('verification.form') }}?email={{ session('email_exists') }}" class="font-medium underline hover:text-[#0f7299]">
                         Kirim ulang kode.
                     </a>
                 </div>
                 @endif

                <form class="mt-8 space-y-5" method="POST" action="{{ route('register') }}">
                    @csrf

                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input id="nama" name="nama" type="text" required value="{{ old('nama') }}"
                            class="block w-full rounded-lg border-gray-300 py-2 px-4 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-1 sm:text-sm transition outline-none border"
                            placeholder="Contoh: Budi Santoso">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Kampus</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                            class="block w-full rounded-lg @error('email') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500/20 @else border-gray-300 focus:border-blue-500 focus:ring-[#0d7198]/20 @enderror py-2 px-4 text-gray-900 sm:text-sm transition outline-none border"
                            placeholder="nama@mhs.politala.ac.id">

                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-gray-500 sm:text-sm">+62</span>
                            </div>
                            <input type="tel" name="nomor_telepon" id="nomor_telepon" required value="{{ old('nomor_telepon') }}"
                                class="block w-full rounded-lg border-gray-300 py-2 pl-14 px-4 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-[#0d7198]/20 sm:text-sm transition outline-none border"
                                placeholder="81234567890">
                        </div>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Daftar Sebagai</label>
                        <select id="role" name="role" required
                            class="block w-full rounded-lg border-gray-300 py-2 px-4 text-gray-900 focus:border-blue-500 focus:ring-1 focus:ring-[#0d7198]/20 sm:text-sm transition outline-none border bg-white appearance-none">
                            <option value="">Pilih Peran</option>
                            <option value="Dosen" {{ old('role') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="Mahasiswa" {{ old('role') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        </select>
                        <p class="mt-2 text-xs text-gray-500">
                            Mahasiswa wajib menggunakan email <span class="font-medium font-mono">@mhs.politala.ac.id</span>.
                        </p>
                    </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input id="password" name="password" type="password" required
                                    class="block w-full rounded-lg border-gray-300 py-2 px-4 pr-10 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-[#0d7198]/20 sm:text-sm transition outline-none border"
                                    placeholder="Min. 8 karakter">
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-[#0f7299] focus:outline-none">
                                    <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg id="eye-slash-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi</label>
                                <div class="relative">
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                    class="block w-full rounded-lg border-gray-300 py-2 px-4 pr-10 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-[#0d7198]/20 sm:text-sm transition outline-none border"
                                    placeholder="Ulangi password">
                                <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-[#0f7299] focus:outline-none">
                                    <svg id="eye-confirm-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg id="eye-confirm-slash-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </button>
                            </div>
                        </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg border border-transparent bg-[#1180ab] py-2.5 px-4 text-sm font-bold text-white shadow-sm hover:bg-[#0d7198] focus:outline-none focus:ring-1 focus:ring-[#0d7198] transition active:scale-[0.98]">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                 <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="bg-white px-3 text-gray-500 font-medium">Atau daftar dengan</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('auth.google') }}"
                            class="flex w-full items-center justify-center gap-3 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-1 focus:ring-[#0d7198]/20 transition">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5" alt="Google">
                            Google
                        </a>
                    </div>
                </div>

                <p class="mt-8 text-center text-sm text-gray-500">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-semibold text-[#1180ab] hover:text-[#0f7299] hover:underline">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </div>
    </div>

    <p class="text-gray-400 mt-6">© 2025 SIMPERSITE. All rights reserved.</p>

    @vite('resources/js/hidenPassword.js')
</body>
</html>
