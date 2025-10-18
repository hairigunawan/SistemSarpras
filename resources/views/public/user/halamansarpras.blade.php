<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Sarana & Prasarana - SIMPERSITE</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

  <!-- 🔹 Navbar -->
  <nav class="bg-white border-b py-3">
    <div class="flex mx-10 justify-between items-center">
      <div class="flex gap-1 items-center">
        <p class="rotate-6">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m21 14l-9 6l-9-6m18-4l-9 6l-9-6l9-6z" />
          </svg>
        </p>
        <h1 class="text-xl font-semibold text-gray-700">SIMPERSITE.</h1>
      </div>

      <div class="flex justify-end px-6 py-3 gap-10 items-center">
        <ul class="flex space-x-6">
          <li><a href="{{ route('public.beranda.index') }}" class="hover:text-blue-500 font-normal">Beranda</a></li>
          <li><a href="{{ route('public.peminjaman.daftarpeminjaman') }}" class="hover:text-blue-500 font-normal">Peminjaman</a></li>
          <li><a href="{{ route('public.user.halamansarpras') }}" class="hover:text-blue-500 font-normal text-blue-600">Sarana & Prasarana</a></li>
        </ul>

        <p class="text-xl text-gray-300 font-light">|</p>

        <div class="flex items-center">
          @guest
            <a href="{{ route('login') }}" class="font-semibold text-gray-700 hover:text-gray-300">Log in</a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="ml-4 border py-1 px-4 rounded-full border-gray-300 font-semibold text-gray-600 hover:text-gray-300">Register</a>
            @endif
          @else
            <div class="flex items-center space-x-3">
              <span class="font-semibold text-gray-700">Admin</span>
              <a href="{{ route('logout') }}" 
                 class="border py-1 px-4 rounded-full border-gray-300 font-semibold text-gray-600 hover:text-gray-300"
                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                 Logout
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
              </form>
            </div>
          @endguest
        </div>
      </div>
    </div>
  </nav>

  <!-- 🔹 Header -->
  <header class="text-center py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Sarana & Prasarana</h1>
    <p class="text-gray-500">Daftar fasilitas yang tersedia untuk digunakan</p>
  </header>

  <!-- 🔹 Daftar Sarpras -->
  <div class="max-w-7xl mx-auto px-6 pb-12">
    @if($sarpras->isEmpty())
      <div class="text-center text-gray-500 py-20">
        <p>Belum ada data sarana dan prasarana yang tersedia.</p>
      </div>
    @else
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($sarpras as $item)
          <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
            <div class="h-48 w-full">
              @if($item->gambar)
                <img src="{{ asset('storage/' . str_replace('public/', '', $item->gambar)) }}" 
                     alt="{{ $item->nama_sarpras }}" 
                     class="w-full h-full object-cover">
              @else
                <img src="https://via.placeholder.com/400x250?text=Tidak+Ada+Gambar" 
                     alt="Tidak Ada Gambar" 
                     class="w-full h-full object-cover">
              @endif
            </div>

            <div class="p-5">
              <h2 class="text-lg font-bold text-gray-800">{{ $item->nama_sarpras }}</h2>
              <p class="text-sm text-gray-500 mb-3">{{ $item->lokasi ?? 'Lokasi tidak diketahui' }}</p>

              <div class="flex justify-between items-center mb-4">
                <span class="text-sm font-medium {{ $item->status == 'Tersedia' ? 'text-green-600' : 'text-yellow-600' }}">
                  {{ $item->status }}
                </span>
                <span class="text-sm text-gray-500 italic">{{ $item->jenis_sarpras ?? '-' }}</span>
              </div>

              <a href="{{ route('public.user.detail_sarpras', $item->id_sarpras) }}" 
                 class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">
                Lihat Detail
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <!-- 🔹 Footer -->
  <footer class="mt-10 py-6 text-center text-gray-500 text-sm border-t">
    © {{ date('Y') }} SIMPERSITE. Semua hak dilindungi.
  </footer>

</body>
</html>
