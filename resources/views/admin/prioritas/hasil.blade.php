@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="mb-4 page-header">
    <h3 class="fw-bold">Projector Loan Requests - Hasil Prioritas</h3>
    <p class="text-muted">{{ Auth::user()->email ?? 'admin@gmail.com' }}</p>
  </div>

  <!-- Card Bobot AHP -->
  <div class="mb-4 card">
    <div class="card-body">
      <h5 class="mb-3 fw-semibold">Bobot AHP</h5>
      <table class="table mb-0 align-middle table-borderless">
        <thead class="table-light">
          <tr>
            <th>Kriteria</th>
            <th class="text-end">Nilai Bobot</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Jenis Kegiatan</td><td class="text-end">{{ number_format($bobotAHP[0], 4) }}</td></tr>
          <tr><td>Jumlah Peserta</td><td class="text-end">{{ number_format($bobotAHP[1], 4) }}</td></tr>
          <tr><td>Waktu Pengajuan</td><td class="text-end">{{ number_format($bobotAHP[2], 4) }}</td></tr>
          <tr><td>Durasi Peminjaman</td><td class="text-end">{{ number_format($bobotAHP[3], 4) }}</td></tr>
        </tbody>
      </table>

      <div class="mt-3 text-muted">
        λ_max: <b>{{ $consistency['lambda_max'] }}</b>,
        CI: <b>{{ $consistency['CI'] }}</b>,
        CR: <b>{{ $consistency['CR'] }}</b>
      </div>

      @if($consistency['CR'] > 0.1)
        <div class="mt-3 alert alert-warning">⚠️ CR > 0.1 — Periksa kembali matriks AHP.</div>
      @endif
    </div>
  </div>

  <!-- Hasil SAW -->
  <div class="mb-4 card">
    <div class="card-body">
      <h5 class="mb-3 fw-semibold">Hasil Perhitungan SAW</h5>
      <table class="table align-middle">
        <thead class="table-light">
          <tr>
            <th>Peringkat</th>
            <th>Nama</th>
            <th>Barang</th>
            <th>Nilai SAW</th>
          </tr>
        </thead>
        <tbody>
          @foreach($hasil as $row)
          <tr>
            <td>{{ $row->peringkat }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->barang }}</td>
            <td>{{ number_format($row->nilai_saw, 6) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Tombol Navigasi -->
  <div class="gap-2 d-flex justify-content-end">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    <a href="{{ $tipe === 'Proyektor' ? route('admin.prioritas.proyektor') : route('admin.prioritas.ruangan') }}" class="btn btn-primary">Hitung Ulang</a>
  </div>
</div>
@endsection
