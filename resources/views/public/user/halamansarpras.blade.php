<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sarana & Prasarana</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Tailwind -->
  @vite('resources/css/app.css')

  <style>
    body {
      background-color: #f8f9fa;
    }

    .hero h2 {
      font-weight: bold;
      color: #2563eb !important; /* Biru elegan */
    }

    .card-header {
      background-color: #198754;
      color: white;
      font-weight: bold;
    }

    .btn-outline-success:hover {
      background-color: #198754;
      color: white;
    }

    a {
      text-decoration: none !important;
    }

    .inventory-card {
      transition: all 0.3s ease;
    }

    .inventory-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">

  <!-- Navbar -->
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
            <a href="{{ route('admin.dashboard.index') }}" class="font-semibold text-gray-700 hover:text-gray-300">Dashboard</a>
          @endguest
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
<section class="hero text-center py-5 bg-light mb-4">
  <h2 class="text-4xl font-extrabold text-blue-600 mb-2">Daftar Sarana & Prasarana</h2>
  <p class="text-muted">Lihat fasilitas yang tersedia untuk digunakan.</p>
</section>

  <!-- Konten Sarana & Prasarana -->
  <div class="container">
    <div class="row g-4">
      @forelse($inventories as $item)
        <div class="col-md-4">
          <div class="card inventory-card shadow-sm border-0">
            <div class="card-header text-center">
              {{ $item->nama_barang }}
            </div>
            <div class="card-body text-center">
              @if($item->gambar)
                <img src="{{ asset('storage/' . $item->gambar) }}" class="img-fluid mb-3 rounded" alt="{{ $item->nama_barang }}">
              @else
                <img src="https://via.placeholder.com/300x200?text=No+Image" class="img-fluid mb-3 rounded" alt="No Image">
              @endif
              <p class="text-muted">{{ Str::limit($item->keterangan, 100) }}</p>
              <p><strong>Jumlah:</strong> {{ $item->jumlah }}</p>
              <a href="{{ route('public.user.halamansarpras.show', $item->id) }}" class="btn btn-outline-success">Lihat Detail</a>
            </div>
          </div>
        </div>
      @empty
        <p class="text-center text-muted">Belum ada data inventaris tersedia.</p>
      @endforelse
    </div>
  </div>
</body>
</html>
