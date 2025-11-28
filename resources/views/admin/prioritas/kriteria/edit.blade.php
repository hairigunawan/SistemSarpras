@extends('layouts.app')

@section('title', 'Edit Kriteria')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Edit Kriteria</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.kriteria.update', $kriteria->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama_kriteria">Nama Kriteria:</label>
                            <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" value="{{ $kriteria->nama_kriteria }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tipe">Tipe:</label>
                            <select class="form-control" id="tipe" name="tipe" required>
                                <option value="benefit" {{ $kriteria->tipe == 'benefit' ? 'selected' : '' }}>Benefit</option>
                                <option value="cost" {{ $kriteria->tipe == 'cost' ? 'selected' : '' }}>Cost</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Kriteria</button>
                        <a href="{{ route('admin.kriteria.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
