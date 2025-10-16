@extends('layouts')

@section('content')
<div class="container py-4">
  <h3>Prioritas Peminjaman Proyektor</h3>

  <form method="POST" action="{{ route('admin.prioritas.proyektor.hitung') }}">
    @csrf
    <button type="submit" class="btn btn-primary mb-3">Hitung Prioritas Proyektor</button>
  </form>

  <table class="table">
    <thead><tr><th>Nama</th><th>Barang</th><th>Jenis Kegiatan</th><th>Peserta</th><th>Waktu Pengajuan</th><th>Durasi</th></tr></thead>
    <tbody>
      @forelse($peminjamans as $p)
      <tr>
        <td>{{ $p->nama }}</td>
        <td>{{ $p->barang }}</td>
        <td>{{ $p->jenis_kegiatan }}</td>
        <td>{{ $p->jumlah_peserta }}</td>
        <td>{{ $p->waktu_pengajuan }}</td>
        <td>{{ $p->durasi_peminjaman }}</td>
      </tr>
      @empty
      <tr><td colspan="6" class="text-center">Belum ada data</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
