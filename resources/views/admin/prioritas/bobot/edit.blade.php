@extends('layouts.app')

@section('title', 'Edit Bobot')

@section('content')
<div class="container">

    <h3 class="mb-4">✏ Edit Bobot</h3>

    {{-- Error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.bobot.update', $bobot->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Bobot</label>
            <input type="text" class="form-control" name="nama" value="{{ $bobot->nama }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nilai Bobot (0-1)</label>
            <input type="number" step="0.01" class="form-control" name="nilai" value="{{ $bobot->nilai }}" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.bobot.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection
