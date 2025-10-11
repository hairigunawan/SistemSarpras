<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Sarana & Prasarana</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Tailwind (opsional jika kamu pakai Vite di proyekmu) -->
  @vite('resources/css/app.css')

  <style>
    body {
      background-color: #f8f9fa;
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

    .inventory-image {
      max-height: 350px;
      object-fit: cover;
      border-radius: 10px;
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
          <li><a href="/" class="hover:text-blue-500 font-normal">Beranda</a></li>
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
            <a href="{{ route('dashboard.index') }}" class="font-semibold text-gray-700 hover:text-gray-300">Dashboard</a>
          @endguest
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <section class="hero text-center py-5 bg-light mb-4">
    <h2 class="text-success">Detail Sarana & Prasarana</h2>
    <p class="text-muted">Informasi lengkap mengenai fasilitas yang tersedia.</p>
  </section>

  <!-- Konten -->
  <div class="container mb-5">
    <div class="card shadow-sm border-0">
      <div class="card-header">
        {{ $inventory->nama_barang }}
      </div>
      <div class="card-body">
        <div class="text-center mb-4">
          @if($inventory->gambar)
            <img src="{{ asset('storage/' . $inventory->gambar) }}" alt="{{ $inventory->nama_barang }}" class="img-fluid inventory-image">
          @else
            <img src="https://via.placeholder.com/400x300?text=No+Image" class="img-fluid inventory-image" alt="No Image">
          @endif
        </div>

        <div class="px-3">
          <p><strong>Nama Barang:</strong> {{ $inventory->nama_barang }}</p>
          <p><strong>Kategori:</strong> {{ $inventory->kategori ?? 'Tidak ada kategori' }}</p>
          <p><strong>Jumlah:</strong> {{ $inventory->jumlah }}</p>
          <p><strong>Keterangan:</strong></p>
          <p class="text-muted">{{ $inventory->keterangan ?? 'Tidak ada keterangan tambahan.' }}</p>
        </div>

        <div class="text-center mt-4">
          <a href="{{ route('public.user.halamansarpras') }}" class="btn btn-outline-success">← Kembali ke Daftar</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
