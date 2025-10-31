@extends('layouts.app')

@section('title', 'Hasil Prioritas')

@section('content')
<div class="container py-4">
  <!-- Header -->
  <div class="mb-4 page-header">
    <h3 class="fw-bold">Projector Loan Requests – Hasil Prioritas</h3>
    <p class="text-muted">{{ Auth::user()->email ?? 'admin@gmail.com' }}</p>
  </div>

  <!-- Card Bobot AHP -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="fw-semibold mb-3">Bobot AHP</h5>

      <div class="table-responsive">
        <table class="table table-sm align-middle table-borderless mb-0">
          <thead class="table-light">
            <tr>
              <th>Kriteria</th>
              <th class="text-end">Nilai Bobot</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Jenis Kegiatan</td>
              <td class="text-end">{{ number_format($bobotAHP[0] ?? 0, 4) }}</td>
            </tr>
            <tr>
              <td>Jumlah Peserta</td>
              <td class="text-end">{{ number_format($bobotAHP[1] ?? 0, 4) }}</td>
            </tr>
            <tr>
              <td>Waktu Pengajuan</td>
              <td class="text-end">{{ number_format($bobotAHP[2] ?? 0, 4) }}</td>
            </tr>
            <tr>
              <td>Durasi Peminjaman</td>
              <td class="text-end">{{ number_format($bobotAHP[3] ?? 0, 4) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Info Konsistensi -->
      <div class="mt-3 text-muted">
        λ<sub>max</sub>: <b>{{ $consistency['lambda_max'] ?? '-' }}</b>,
        CI: <b>{{ $consistency['CI'] ?? '-' }}</b>,
        CR: <b>{{ $consistency['CR'] ?? '-' }}</b>
      </div>

      @if(isset($consistency['CR']) && $consistency['CR'] > 0.1)
        <div class="mt-3 alert alert-warning mb-0">
          ⚠️ CR > 0.1 — Periksa kembali matriks AHP.
        </div>
      @endif
    </div>
  </div>

  <!-- Hasil SAW -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="fw-semibold mb-3">Hasil Perhitungan SAW</h5>

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
          <thead class="table-light">
            <tr class="text-center">
              <th>Peringkat</th>
              <th>Nama Peminjam</th>
              <th>Barang</th>
              <th>Nilai SAW</th>
            </tr>
          </thead>
          <tbody>
            @forelse($hasil as $row)
              <tr class="text-center">
                <td>{{ $row['peringkat'] ?? $row->peringkat ?? '-' }}</td>
                <td>{{ $row['nama'] ?? $row->nama ?? 'Tidak diketahui' }}</td>
                <td>{{ $row['barang'] ?? $row->barang ?? '-' }}</td>
                <td>{{ number_format($row['nilai_saw'] ?? $row->nilai_saw ?? 0, 6) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-3">Tidak ada hasil perhitungan tersedia.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Tombol Navigasi -->
  <div class="d-flex justify-content-end gap-2">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left-circle"></i> Kembali ke Dashboard
    </a>

    <a href="{{ isset($tipe) && $tipe === 'Proyektor'
      ? route('admin.prioritas.proyektor')
      : route('admin.prioritas.ruangan') }}"
      class="btn btn-primary">
      <i class="bi bi-arrow-repeat"></i> Hitung Ulang
    </a>
  </div>
</div>
@endsection
