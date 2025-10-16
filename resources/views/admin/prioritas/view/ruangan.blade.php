@extends('layouts.admin')

@section('content')
<div class="container py-5">
  <h2 class="fw-bold text-primary mb-3">Prioritas Peminjaman Ruangan</h2>
  <p class="text-muted">Menampilkan daftar peminjaman khusus ruangan.</p>

  <form method="POST" action="{{ route('admin.prioritas.ruangan.hitung') }}">
    @csrf
    <button class="btn btn-success mb-3">Hitung Prioritas</button>
  </form>

  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white">Daftar Peminjaman Ruangan</div>
    <div class="card-body p-0">
      <table class="table table-striped mb-0">
        <thead class="table-light">
          <tr>
            <th>Nama</th>
            <th>Jenis Kegiatan</th>
            <th>Jumlah Peserta</th>
            <th>Waktu Pengajuan</th>
            <th>Durasi Peminjaman</th>
          </tr>
        </thead>
        <tbody>
          @forelse($peminjamans as $item)
          <tr>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->jenis_kegiatan }}</td>
            <td>{{ $item->jumlah_peserta }}</td>
            <td>{{ $item->waktu_pengajuan }}</td>
            <td>{{ $item->durasi_peminjaman }}</td>
          </tr>
          @empty
          <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
