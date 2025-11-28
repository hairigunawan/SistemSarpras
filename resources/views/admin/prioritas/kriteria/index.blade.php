@extends('layouts.app')

@section('title', 'Kriteria Prioritas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Daftar Kriteria</h4>
                    <p class="mb-0">Manajemen kriteria untuk perhitungan prioritas.</p>
                </div>
                <a href="{{ route('admin.kriteria.create') }}" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>Tambah Kriteria</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="data-tables table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No</th>
                            <th>Nama Kriteria</th>
                            <th>Tipe</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach ($kriteria as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_kriteria }}</td>
                            <td>{{ $item->tipe }}</td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge badge-info mr-2" data-toggle="tooltip" data-placement="top" title="Edit"
                                        href="{{ route('admin.kriteria.edit', $item->id) }}"><i class="ri-pencil-line mr-0"></i></a>
                                    <form action="{{ route('admin.kriteria.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="badge badge-warning mr-2" data-toggle="tooltip" data-placement="top" title="Delete"><i class="ri-delete-bin-line mr-0"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
