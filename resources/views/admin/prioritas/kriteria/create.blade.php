@extends('layouts.app')

@section('title', 'Tambah Kriteria')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Tambah Kriteria Baru</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.kriteria.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nama_kriteria">Nama Kriteria:</label>
                            <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" required>
                        </div>
                        <div class="form-group">
                            <label for="tipe">Tipe:</label>
                            <select class="form-control" id="tipe" name="tipe" required>
                                <option value="benefit">Benefit</option>
                                <option value="cost">Cost</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah Kriteria</button>
                        <a href="{{ route('admin.kriteria.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
