


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Sarpras - {{ $sarpras->nama_sarpras }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-color: #f8f9fa;
    }

    /* 🔵 Warna utama */
    .text-primary-blue { color: #2563eb !important; }
    .bg-primary-blue { background-color: #2563eb !important; }
    .border-primary-blue { border-color: #2563eb !important; }

    /* Navbar */
    nav a {
      color: #374151; /* abu-abu */
      transition: color 0.2s;
    }
    nav a:hover {
      color: #2563eb; /* biru saat hover */
    }
    nav a.active {
      color: #2563eb;
      font-weight: 600;
    }

    /* Komponen lain */
    .card-header {
      background-color: #2563eb;
      color: white;
      font-weight: bold;
    }

    .btn-primary {
      background-color: #2563eb !important;
      border-color: #2563eb !important;
    }

    .btn-primary:hover {
      background-color: #1e40af !important;
      border-color: #1e40af !important;
    }

    .badge.bg-success {
      background-color: #2563eb !important;
    }

    .badge.bg-warning {
      background-color: #facc15 !important;
      color: #1e3a8a !important;
    }

    .card-title {
      color: #2563eb !important;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">

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
          <li><a href="{{ route('public.user.halamansarpras') }}" class="hover:text-blue-500 font-normal active">Sarana & Prasarana</a></li>
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

  <!-- 🔹 Konten Utama -->
  <div class="container py-5">
    <div class="mb-4">
      <a href="{{ route('public.user.halamansarpras') }}" class="text-decoration-none text-secondary">
        ← Kembali ke Daftar Sarpras
      </a>
    </div>

    <div class="card shadow-sm border-0">
      <div class="row g-0">
        <div class="col-md-5">
          @if($sarpras->gambar)
              <img src="{{ asset('storage/' . str_replace('public/', '', $sarpras->gambar)) }}" 
                   alt="{{ $sarpras->nama_sarpras }}" 
                   class="img-fluid rounded-start w-100 h-100 object-fit-cover">
          @else
              <img src="https://via.placeholder.com/500x400?text=No+Image" 
                   alt="No Image" 
                   class="img-fluid rounded-start w-100 h-100 object-fit-cover">
          @endif
        </div>

        <div class="col-md-7">
          <div class="card-body">
            <h3 class="card-title fw-bold mb-3">{{ $sarpras->nama_sarpras }}</h3>

            <table class="table table-borderless">
              <tr><th class="text-muted" style="width:150px;">Jenis</th><td>{{ $sarpras->jenis_sarpras ?? '-' }}</td></tr>
              <tr><th class="text-muted">Lokasi</th><td>{{ $sarpras->lokasi ?? '-' }}</td></tr>
              <tr><th class="text-muted">Kondisi</th><td>{{ $sarpras->kondisi ?? '-' }}</td></tr>
              <tr>
                <th class="text-muted">Status</th>
                <td>
                  <span class="badge {{ $sarpras->status == 'Tersedia' ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ $sarpras->status }}
                  </span>
                </td>
              </tr>
            </table>

            @if($sarpras->deskripsi)
              <div class="mt-4">
                <h5 class="fw-semibold mb-2">Deskripsi</h5>
                <p class="text-muted">{{ $sarpras->deskripsi }}</p>
              </div>
            @endif

            <div class="mt-4">
              <a href="{{ route('public.peminjaman.daftarpeminjaman') }}" 
                 class="btn btn-primary px-4 py-2">
                Ajukan Peminjaman
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="text-center text-muted py-4 mt-5 border-top">
    © {{ date('Y') }} SIMPERSITE. Semua hak dilindungi.
  </footer>

</body>
</html>
