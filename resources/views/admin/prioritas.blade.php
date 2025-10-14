@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-lg font-semibold">Prioritas Peminjaman {{ $jenisSarana }}</h2>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Peringkat</th>
                <th>Nama Peminjam</th>
                <th>Nilai Preferensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilai as $i => $n)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $n['nama'] }}</td>
                <td>{{ number_format($n['nilai_preferensi'], 3) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
