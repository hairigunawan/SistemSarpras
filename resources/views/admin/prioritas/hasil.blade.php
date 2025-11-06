@extends('layouts.app') {{-- atau layout utama kamu --}}
@section('content')

<div class="container">
    <h1 class="mb-4">Prioritas Peminjaman Berdasarkan Jenis</h1>

    {{-- ===== RUANGAN ===== --}}
    <h3>Prioritas Peminjaman Ruangan</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Jenis</th>
                <th>Nilai AHP</th>
                <th>Nilai SAW</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($ruangan as $r)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $r->nama_peminjam }}</td>
                    <td>{{ $r->jenis }}</td>
                    <td>{{ $r->nilai_ahp }}</td>
                    <td>{{ $r->nilai_saw }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    {{-- ===== PROYEKTOR ===== --}}
    <h3>Prioritas Peminjaman Proyektor</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Jenis</th>
                <th>Nilai AHP</th>
                <th>Nilai SAW</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($proyektor as $p)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $p->nama_peminjam }}</td>
                    <td>{{ $p->jenis }}</td>
                    <td>{{ $p->nilai_ahp }}</td>
                    <td>{{ $p->nilai_saw }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
