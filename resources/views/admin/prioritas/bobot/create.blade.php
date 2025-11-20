@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">➕ Tambah Bobot</h3>

    <form action="{{ route('admin.bobot.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Bobot</label>
            <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nilai Bobot (0-1)</label>
            <input type="number" step="0.01" class="form-control" name="nilai" value="{{ old('nilai') }}" required>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.bobot.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection
