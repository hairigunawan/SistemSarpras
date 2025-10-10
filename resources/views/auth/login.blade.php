<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMPERSITE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex bg-white">

    {{-- Bagian kiri (Form Login) --}}
    <div class="flex-1 flex flex-col justify-center px-10 lg:px-5">
        <div class="max-w-[320px] w-full mx-auto">
            <h1 class="text-3xl font-bold mb-2">Login</h1>
            <p class="text-gray-500 mb-8">See your growth and get consulting support!</p>

            {{-- Tombol Google --}}
            <a href="{{ route('auth.google') }}"
               class="w-full flex justify-center items-center gap-2 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-600 hover:bg-gray-50">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                Sign in with Google
            </a>

            {{-- Divider --}}
            <div class="flex items-center my-6">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="mx-3 text-gray-400 text-sm">or sign in with Email</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>

            {{-- Form Login --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email*</label>
                    <input id="email" name="email" type="email" required
                        class="w-full border rounded-md py-2 px-3 mt-1 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password*</label>
                    <input id="password" name="password" type="password" required
                        class="w-full border rounded-md py-2 px-3 mt-1 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" class="mr-2 rounded border-gray-300">
                        Remember me
                    </label>
                    <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                </div>

                <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition">
                    Login
                </button>

                <p class="text-center text-sm text-gray-500 mt-4">
                    Not registered yet?
                    <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">Create an Account</a>
                </p>
            </form>
        </div>
    </div>

    {{-- Bagian kanan (Ilustrasi & Background) --}}
    <div class="hidden lg:flex flex-1 bg-gradient-to-br from-blue-500 to-blue-500 items-center justify-center relative overflow-hidden">
        <div class="text-white text-center px-8">
            <h2 class="text-3xl font-semibold mb-4">Turn your ideas into reality.</h2>
            <p class="text-indigo-100 mb-8">Consistent quality and experience across all platforms and devices.</p>

            {{-- Elemen dekoratif (grafik & kartu) --}}
            <div class="flex flex-col items-center gap-6">
                <div class="bg-white text-gray-800 rounded-xl shadow-lg p-4 w-64">
                    <h3 class="text-sm font-semibold mb-2">Rewards</h3>
                    <div class="flex items-center gap-4">
                        <img src="https://via.placeholder.com/50" alt="user" class="rounded-full">
                        <div>
                            <p class="text-lg font-bold">172,832</p>
                            <span class="text-sm text-gray-500">Points</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-4 w-64">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">$162,751</h3>
                    <p class="text-xs text-gray-500 mb-1">+ $23,827 August</p>
                    <div class="h-16 w-full bg-gradient-to-r from-blue-300 to-purple-400 rounded-md opacity-50"></div>
                </div>
            </div>
        </div>

        {{-- Ornamen latar belakang --}}
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
    </div>

</body>
</html>
