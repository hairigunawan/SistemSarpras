<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - SIMPERSITE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col justify-between bg-gradient-to-br from-blue-50 to-white">
    <div class="flex-grow w-full flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">

            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Verifikasi Email</h2>
                <p class="text-gray-600">Masukkan kode verifikasi yang telah dikirim ke email Anda</p>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"  class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800 text-sm">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"  class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800 text-sm">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify') }}">
                @csrf

                <div class="mb-4">
                    <label class="font-medium text-gray-700 text-sm">Email</label>
                    <input type="email" name="email" value="{{ old('email') ?? $user->email ?? request('email') }}"
                        class="w-full border rounded-xl px-4 py-3 mt-1 text-sm shadow-sm focus:ring-1 focus:ring-[#0d7198] outline-none"
                        placeholder="email yang anda gunakan" required>
                </div>

                <div class="mb-6">
                    <label class="font-medium text-gray-700 text-sm">Kode Verifikasi</label>
                    <input type="text" name="verification_code" maxlength="6"
                        class="w-full border rounded-xl px-4 py-3 mt-1 text-sm shadow-sm focus:ring-1 focus:ring-[#0d7198] outline-none text-center text-2xl tracking-widest"
                        placeholder="123456" required>
                    <p class="text-xs text-gray-500 mt-2">Kode 6 digit yang telah dikirim ke email Anda</p>
                </div>

                <button type="submit"
                        class="w-full bg-[#1180ab] text-white font-semibold py-3 rounded-xl shadow-md hover:bg-[#0d7198] transition mb-4">
                    Verifikasi Email
                </button>

                <div class="text-center" x-data="{ openError: false }">
                    <p class="text-sm text-gray-600">
                        Tidak menerima kode?
                        <button type="button" @click="
                            let emailVal = document.querySelector('input[name=\'email\']').value;
                            if (!emailVal) {
                                openError = true;
                            } else {
                                let hiddenInput = document.querySelector('#resendForm input[name=\'email\']');
                                if(hiddenInput) hiddenInput.value = emailVal;
                                document.getElementById('resendForm').submit();
                            }
                        " class="text-[#1180ab] font-semibold hover:underline">
                            Kirim Ulang
                        </button>
                    </p>

                    <div x-show="openError" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
                        <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full text-left">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Email Diperlukan</h3>
                            <p class="text-sm text-gray-600">Silakan masukkan email terlebih dahulu.</p>
                            <div class="mt-4 flex justify-end">
                                <button @click="openError = false" class="px-4 py-2 text-sm font-medium text-white bg-[#1180ab] rounded-md hover:bg-[#0f7299]">
                                    Oke
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="resendForm" method="POST" action="{{ route('verification.resend') }}" style="display: none;">
                @csrf
                <input type="hidden" name="email" value="{{ old('email') ?? $email ?? request('email') }}">
            </form>
        </div>
    </div>

    <p class="text-center text-gray-500 text-sm pb-4">
        © 2025 SIMPERSITE. All rights reserved.
    </p>

    <script>
        // Hanya izinkan angka dalam input kode verifikasi
        document.querySelector('input[name="verification_code"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
