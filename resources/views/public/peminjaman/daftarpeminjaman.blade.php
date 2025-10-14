<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Peminjaman Ruangan & Proyektor</title>

  <!-- CSS Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Tailwind via Vite -->
  @vite('resources/css/app.css')

  <style>
    body {
      background-color: #f8f9fa;
    }

    .hero h2 {
      font-weight: bold;
    }

    /* 🔵 Gaya biru utama */
    .card-header {
      background-color: #2563eb;
      color: white;
      font-weight: bold;
    }

    .btn-outline-success:hover,
    .btn-success {
      background-color: #2563eb !important;
      border-color: #2563eb !important;
      color: white !important;
    }

    .modal-header {
      background-color: #2563eb;
      color: white;
    }

    .table-success {
      background-color: #dbeafe !important;
      color: #1e3a8a !important;
    }

    .badge.bg-success {
      background-color: #2563eb !important;
    }

    .badge.bg-warning {
      background-color: #facc15 !important;
      color: #1e3a8a !important;
    }

    .table > :not(:first-child) {
      border-top: 2px solid #dee2e6;
    }

    a {
      text-decoration: none !important;
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
          <li><a href="{{ route('public.peminjaman.daftarpeminjaman') }}" class="hover:text-blue-500 font-normal text-blue-600">Peminjaman</a></li>
          <li><a href="{{ route('public.user.halamansarpras') }}" class="hover:text-blue-500 font-normal">Sarana & Prasarana</a></li>
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

<div class="container py-5">

  <!-- Hero -->
  <div class="text-center mb-5 hero">
    <h2 class="text-2xl font-bold mb-2">Peminjaman Ruangan Dan Proyektor</h2>
    <p class="text-gray-600 text-sm mb-4">
        Selamat datang di portal layanan peminjaman sarana prasarana program studi Teknologi Informasi
    </p>

    <div class="flex justify-center space-x-4 mt-3">
      <a href="{{ route('public.peminjaman.create') }}"
       class="inline-block bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-md hover:bg-blue-700 transition">
       Ajukan Peminjaman
      </a>
  </div>
  </div>

  <!-- Statistik -->
  <div class="row text-center mb-5">
    <div class="col-md-6 mb-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="fw-bold text-primary">Total Ruangan Dipinjam</h5>
          <p class="display-5 fw-bold mb-0">10</p> <!-- Angka hitam -->
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h5 class="fw-bold text-primary">Total Proyektor Dipinjam</h5>
          <p class="display-5 fw-bold mb-0">7</p> <!-- Angka hitam -->
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header">
      Daftar Peminjaman
    </div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0">
        <thead class="table-success">
          <tr>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Keperluan</th>
            <th>Tanggal Peminjaman</th>
            <th>Tanggal Pengembalian</th>
            <th>Jam</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Asep Mahasiswa</td>
            <td>Ruangan</td>
            <td>Presentasi Proyek</td>
            <td>2025-10-08</td>
            <td>2025-10-08</td>
            <td>10:00 - 12:00</td>
            <td><span class="badge bg-success">Disetujui</span></td>
          </tr>
          <tr>
            <td>Budi Dosen</td>
            <td>Proyektor</td>
            <td>Kuliah Umum</td>
            <td>2025-10-09</td>
            <td>2025-10-09</td>
            <td>08:00 - 10:00</td>
            <td><span class="badge bg-warning text-dark">Pending</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Ruangan -->
<div class="modal fade" id="modalRuangan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="#">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Form Peminjaman Ruangan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="jenis" value="ruangan">
          <div class="mb-3">
            <label class="form-label">Nama Peminjam</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal Peminjaman</label>
            <input type="date" name="tgl_pinjam" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal Pengembalian</label>
            <input type="date" name="tgl_kembali" class="form-control" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Jam Mulai</label>
              <input type="time" name="jam_mulai" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Jam Selesai</label>
              <input type="time" name="jam_selesai" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Keperluan</label>
            <textarea name="keperluan" class="form-control" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Kirim</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
