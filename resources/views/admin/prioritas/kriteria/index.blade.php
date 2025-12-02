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
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($kriteria as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_kriteria }}</td>
                            <td>
                                @if($item->tipe === 'benefit')
                                    <span class="badge badge-success">Benefit</span>
                                @else
                                    <span class="badge badge-danger">Cost</span>
                                @endif
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge badge-info mr-2" data-toggle="tooltip" data-placement="top" title="Lihat"
                                        href="{{ route('admin.kriteria.show', $item->id) }}"><i class="ri-eye-line mr-0"></i></a>
                                    <a class="badge badge-primary mr-2" data-toggle="tooltip" data-placement="top" title="Edit"
                                        href="{{ route('admin.kriteria.edit', $item->id) }}"><i class="ri-pencil-line mr-0"></i></a>
                                    <form action="{{ route('admin.kriteria.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="badge badge-warning" data-toggle="tooltip" data-placement="top" title="Delete"><i class="ri-delete-bin-line mr-0"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data kriteria.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
