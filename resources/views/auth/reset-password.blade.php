<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Reset Password</h2>

    <form method="POST" action="{{ route('password.resetPassword') }}">
        @csrf

        <label class="block">Password Baru</label>
        <input type="password" name="password" class="border rounded w-full p-2" required>

        <label class="block mt-3">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="border rounded w-full p-2" required>

        <button class="bg-green-600 text-white px-4 py-2 rounded mt-4 w-full">
            Reset Password
        </button>
    </form>
</div>

</body>
</html>
