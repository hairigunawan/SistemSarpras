@extends('layouts.app')

@section('title', 'Detail Kriteria')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Kriteria</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Kriteria:</label>
                                <p class="form-control-static">{{ $kriteria->nama_kriteria }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipe:</label>
                                <p class="form-control-static">
                                    <span class="badge {{ $kriteria->tipe === 'benefit' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($kriteria->tipe) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Keterangan:</label>
                                <p class="form-control-static">{{ $kriteria->keterangan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Created At:</label>
                                <p class="form-control-static">{{ $kriteria->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Updated At:</label>
                                <p class="form-control-static">{{ $kriteria->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.kriteria.index') }}" class="btn btn-secondary">Kembali</a>
                    <a href="{{ route('admin.kriteria.edit', $kriteria->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('admin.kriteria.destroy', $kriteria->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection