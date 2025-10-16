@extends('layouts.admin')

@section('content')
<div class="container py-4">
  <h3>Hasil Prioritas - {{ $tipe }}</h3>

  <div class="card mb-3">
    <div class="card-body">
      <h5>Bobot AHP</h5>
      <ul>
        <li>Jenis Kegiatan: {{ number_format($bobotAHP[0], 4) }}</li>
        <li>Jumlah Peserta: {{ number_format($bobotAHP[1], 4) }}</li>
        <li>Waktu Pengajuan: {{ number_format($bobotAHP[2], 4) }}</li>
        <li>Durasi Peminjaman: {{ number_format($bobotAHP[3], 4) }}</li>
      </ul>

      <p>λ_max: {{ $consistency['lambda_max'] }}, CI: {{ $consistency['CI'] }}, CR: {{ $consistency['CR'] }}</p>
      @if($consistency['CR'] > 0.1)
        <div class="alert alert-warning">CR > 0.1 — periksa matriks AHP.</div>
      @endif
    </div>
  </div>

  <table class="table table-striped">
    <thead><tr><th>Peringkat</th><th>Nama</th><th>Barang</th><th>Nilai SAW</th></tr></thead>
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

  <a href="{{ $tipe === 'Proyektor' ? route('admin.prioritas.proyektor') : route('admin.prioritas.ruangan') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
