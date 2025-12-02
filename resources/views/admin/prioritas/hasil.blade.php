@extends('layouts.app') {{-- atau layout utama kamu --}}
@section('content')

<div class="container">
    <h1 class="mb-4">Perbandingan Hasil Perhitungan Prioritas</h1>

    {{-- ===== RUANGAN ===== --}}
    <div class="card mb-5">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Prioritas Peminjaman Ruangan</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th>
                            <th>Ranking AHP</th>
                            <th>Nilai AHP</th>
                            <th>Ranking SAW</th>
                            <th>Nilai SAW</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ruangan as $index => $r)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $r['nama'] ?? $r->nama_peminjam ?? 'Tidak Diketahui' }}</td>
                                <td>{{ $r['ranking'] ?? $index + 1 }}</td>
                                <td>{{ number_format($r['nilai'] ?? 0, 4) }}</td>
                                <td>{{ $nilai_saw_ruangan[$index]['ranking'] ?? $index + 1 }}</td>
                                <td>{{ number_format($nilai_saw_ruangan[$index]['nilai'] ?? 0, 4) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data peminjaman ruangan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== PROYEKTOR ===== --}}
    <div class="card">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">Prioritas Peminjaman Proyektor</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th>
                            <th>Ranking AHP</th>
                            <th>Nilai AHP</th>
                            <th>Ranking SAW</th>
                            <th>Nilai SAW</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proyektor as $index => $p)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $p['nama'] ?? $p->nama_peminjam ?? 'Tidak Diketahui' }}</td>
                                <td>{{ $p['ranking'] ?? $index + 1 }}</td>
                                <td>{{ number_format($p['nilai'] ?? 0, 4) }}</td>
                                <td>{{ $nilai_saw_proyektor[$index]['ranking'] ?? $index + 1 }}</td>
                                <td>{{ number_format($nilai_saw_proyektor[$index]['nilai'] ?? 0, 4) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data peminjaman proyektor</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
