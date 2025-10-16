<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prioritas Ruangan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card-header { background-color: #2563eb; color: white; font-weight: bold; }
    .btn-primary { background-color: #2563eb; border-color: #2563eb; }
    .table-success { background-color: #dbeafe; color: #1e3a8a; }
    .badge.bg-success { background-color: #2563eb !important; }
    .badge.bg-warning { background-color: #facc15 !important; color: #1e3a8a !important; }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold text-primary">Prioritas Peminjaman Ruangan</h2>
    <p class="text-muted">Menampilkan daftar peminjaman ruangan dan menghitung prioritas menggunakan AHP</p>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="text-end mb-3">
    <form method="POST" action="{{ route('admin.prioritas.ruangan.hitung') }}">
      @csrf
      <button class="btn btn-primary px-4">Hitung</button>
    </form>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-header">Daftar Peminjaman Ruangan</div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0">
        <thead class="table-success">
          <tr>
            <th>Nama</th>
            <th>Jenis Kegiatan</th>
            <th>Jumlah Peserta</th>
            <th>Waktu Pengajuan</th>
            <th>Durasi Peminjaman</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($peminjamans as $item)
            <tr>
              <td>{{ $item->nama_peminjam }}</td>
              <td>{{ $item->jenis_kegiatan }}</td>
              <td>{{ $item->jumlah_peserta }}</td>
              <td>{{ $item->waktu_pengajuan }}</td>
              <td>{{ $item->durasi_peminjaman }}</td>
              <td>
                @if($item->status == 'Disetujui')
                  <span class="badge bg-success">{{ $item->status }}</span>
                @elseif($item->status == 'Menunggu')
                  <span class="badge bg-warning">{{ $item->status }}</span>
                @else
                  <span class="badge bg-secondary">{{ $item->status }}</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted">Tidak ada data peminjaman ruangan</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
