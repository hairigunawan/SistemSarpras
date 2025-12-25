<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 ">
    <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Verifikasi OTP</h2>

        @if (session('status'))
            <div class="text-green-600 mb-2">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.verifyOtp') }}">
            @csrf

            <label class="block">Kode OTP</label>
            <input type="text" name="otp" class="border rounded w-full p-2" required>

            @error('otp')
            <div class="text-red-600">{{ $message }}</div>
            @enderror

            <button class="bg-purple-600 text-white px-4 py-2 rounded mt-4 w-full">
                Verifikasi
            </button>
        </form>
    </div>
</body>
</html>
