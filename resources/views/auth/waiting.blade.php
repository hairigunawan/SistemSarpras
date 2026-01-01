<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - SIMPERSITE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full flex items-center justify-center p-4 antialiased text-gray-900">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-10">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gray-50 mb-4 border border-gray-100">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Verifikasi Email Anda</h2>
            <p class="mt-2 text-sm text-gray-500">
                Kami telah mengirimkan kode verifikasi 6-digit ke email:
            </p>
            <p class="font-semibold text-gray-800 mt-1">{{ $user->email }}</p>
        </div>

        <div class="py-4 border-y border-gray-50 mb-8">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3 text-center">Petunjuk</p>
            <ul class="text-sm text-gray-600 space-y-2 leading-relaxed">
                <li class="flex items-start">
                    <span class="mr-2 text-gray-300">•</span>
                    Cek folder inbox atau spam secara berkala.
                </li>
                <li class="flex items-start">
                    <span class="mr-2 text-gray-300">•</span>
                    Kode hanya berlaku selama 24 jam ke depan.
                </li>
            </ul>
        </div>

        <div class="space-y-3">
            <a href="{{ route('verification.form') }}"
               class="w-full flex items-center justify-center bg-[#1180ab] py-3 rounded-lg text-sm font-semibold text-white shadow-sm hover:bg-[#0d7198] transition active:scale-[0.98]">
                Masukkan Kode Verifikasi
            </a>

            <div x-data="{ open: false }">
                <button type="button" @click="open = true"
                        class="w-full flex items-center justify-center bg-white border border-gray-200 py-3 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition active:scale-[0.98]">
                    Kirim Ulang Kode
                </button>

                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
                    <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full text-left">
                        <h2 class="text-lg font-bold text-gray-900">Konfirmasi Kirim Ulang</h2>
                        <p class="mt-2 text-sm text-gray-600">Apakah Anda yakin ingin mengirim ulang kode verifikasi ke <span class="font-semibold">{{ $user->email }}</span>?</p>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                Batal
                            </button>
                            <button @click="document.getElementById('resendForm').submit()" class="px-4 py-2 text-sm font-medium text-white bg-[#1180ab] rounded-md hover:bg-[#0f7299]">
                                Ya, Kirim Ulang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(request()->has('email') && request('email') !== $user->email)
            <p class="mt-6 text-xs text-center text-gray-400 leading-relaxed">
                Teks tambahan: Anda terdeteksi menggunakan email <span class="font-medium italic">{{ request('email') }}</span>.
                Gunakan email yang benar jika ini salah.
            </p>
        @endif

        <div class="mt-10 text-center">
            <p class="text-sm text-gray-500">
                Salah memasukkan email?
                <a href="{{ route('login') }}" class="font-semibold text-[#1180ab] hover:text-[#0f7299] transition">
                    Kembali
                </a>
            </p>
        </div>
    </div>

    <form id="resendForm" method="POST" action="{{ route('verification.resend') }}" class="hidden">
        @csrf
        <input type="hidden" name="email" value="{{ $user->email }}">
    </form>

    <script>
        setTimeout(() => { window.location.reload(); }, 60000);
    </script>
</body>
</html>
