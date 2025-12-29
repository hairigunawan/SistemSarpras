<!DOCTYPE html>
<html>
<head>
    <title>Debug Supabase Upload</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Test Upload Supabase</h1>
        
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
                Success! <br>
                <a href="{{ session('url') }}" target="_blank" class="underline break-all">{{ session('url') }}</a>
            </div>
            <img src="{{ session('url') }}" class="w-full mt-2 rounded border">
        @endif

        <form action="/debug-upload" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">Pilih Gambar</label>
                <input type="file" name="foto" required class="w-full border p-2 rounded">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Upload & Debug</button>
        </form>

        <div class="mt-4 text-xs text-gray-500">
            <p><strong>Config Check:</strong></p>
            <p>URL: {{ config('services.supabase.url') }}</p>
            <p>Bucket: {{ config('services.supabase.bucket') }}</p>
        </div>
    </div>
</body>
</html>