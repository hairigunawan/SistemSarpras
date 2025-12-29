<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Lupa Password</h2>

    @if (session('status'))
        <div class="text-green-600 mb-2">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.sendOtp') }}">
        @csrf

        <label class="block">Email</label>
        <input type="email" name="email" class="border rounded w-full p-2" required>

        @error('email')
        <div class="text-red-600">{{ $message }}</div>
        @enderror

        <button class="bg-[#1180ab] text-white px-4 py-2 rounded mt-4 w-full">
            Kirim Kode OTP
        </button>
    </form>
</div>

</body>
</html>
