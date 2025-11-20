@extends('layouts.app')

@section('content')
<div class="py-4 container-fluid">
    <!-- Header Section -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 fw-bold">⚙️ Kelola Bobot Prioritas</h3>
            <p class="mb-0 text-muted">Kelola bobot prioritas untuk sistem penilaian</p>
        </div>
        <div class="p-3 bg-primary bg-opacity-10 rounded-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-chart-pie text-primary fs-4 me-2"></i>
                <div>
                    <small class="text-muted d-block">Total Bobot</small>
                    <strong class="fs-5 text-primary">{{ number_format($total, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-start">
                <i class="mt-1 fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Form Section -->
        <div class="mb-4 col-lg-4">
            <div class="shadow-sm card h-100">
                <div class="pt-4 pb-3 bg-white border-0 card-header">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-plus-circle text-primary me-2"></i>
                        Tambah Bobot Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('/admin/bobot') }}" method="POST" id="addBobotForm">
                        @csrf

                        <div class="mb-4">
                            <label for="nama" class="form-label fw-medium">Nama Bobot</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-tag text-muted"></i>
                                </span>
                                <input type="text" name="nama" class="form-control" id="nama" placeholder="Contoh: Kedisiplinan" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="nilai" class="form-label fw-medium">Nilai Bobot (0-1)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-percentage text-muted"></i>
                                </span>
                                <input type="number" name="nilai" step="0.01" min="0" max="1" class="form-control" id="nilai" placeholder="0.00" required>
                            </div>
                            <div class="form-text">Nilai antara 0 hingga 1 (contoh: 0.25)</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Simpan Bobot
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="mb-4 col-lg-8">
            <div class="shadow-sm card h-100">
                <div class="pt-4 pb-3 bg-white border-0 card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-list text-primary me-2"></i>
                            Daftar Bobot Prioritas
                        </h5>
                        <div class="px-3 py-2 badge bg-primary bg-opacity-10 text-primary">
                            {{ $bobot->count() }} Data
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($bobot->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-center" style="width: 5%">No</th>
                                        <th scope="col" style="width: 40%">Nama Bobot</th>
                                        <th scope="col" class="text-center" style="width: 15%">Nilai</th>
                                        <th scope="col" class="text-center" style="width: 40%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bobot as $row)
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $loop->iteration }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="p-2 bg-primary bg-opacity-10 rounded-circle me-3">
                                                        <i class="fas fa-balance-scale text-primary"></i>
                                                    </div>
                                                    <span class="fw-medium">{{ $row->nama }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <div class="progress" style="width: 50px; height: 8px;">
                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $row->nilai * 100 }}%"></div>
                                                    </div>
                                                    <span class="ms-2 fw-medium">{{ $row->nilai }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ url('/admin/bobot/'.$row->id.'/edit') }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>

                                                    <form action="{{ url('/admin/bobot/'.$row->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $row->id }}">
                                                            <i class="fas fa-trash me-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Delete Confirmation Modal -->
                                                <div class="modal fade" id="deleteModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="border-0 modal-header">
                                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Apakah Anda yakin ingin menghapus bobot "<strong>{{ $row->nama }}</strong>"?</p>
                                                            </div>
                                                            <div class="border-0 modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <form action="{{ url('/admin/bobot/'.$row->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-5 text-center">
                            <div class="mb-4">
                                <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="text-muted">Belum Ada Data</h5>
                            <p class="text-muted">Tambahkan bobot prioritas untuk memulai</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="mt-4 shadow-sm card">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-3 fw-semibold">
                        <i class="fas fa-chart-bar text-primary me-2"></i>
                        Ringkasan Bobot Prioritas
                    </h5>
                    <div class="mb-3 progress" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ min($total * 100, 100) }}%">
                            {{ number_format($total * 100, 1) }}%
                        </div>
                    </div>
                    <p class="mb-0 text-muted">Total bobot yang telah ditetapkan: <strong>{{ number_format($total, 2) }}</strong></p>
                    @if($total < 1)
                        <p class="mt-2 mb-0 text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Total bobot belum mencapai 1.0. Tambahkan bobot hingga total mencapai 1.0.
                        </p>
                    @elseif($total > 1)
                        <p class="mt-2 mb-0 text-danger">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Total bobot melebihi 1.0. Sesuaikan bobot agar total tepat 1.0.
                        </p>
                    @else
                        <p class="mt-2 mb-0 text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            Total bobot sudah tepat (1.0). Sistem siap digunakan.
                        </p>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="text-center row">
                        <div class="col-4">
                            <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                <h4 class="mb-1 text-primary">{{ $bobot->count() }}</h4>
                                <small class="text-muted">Total Bobot</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                <h4 class="mb-1 text-success">{{ number_format($total, 2) }}</h4>
                                <small class="text-muted">Total Nilai</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-info bg-opacity-10 rounded-3">
                                <h4 class="mb-1 text-info">{{ $bobot->count() > 0 ? number_format($total / $bobot->count(), 2) : '0.00' }}</h4>
                                <small class="text-muted">Rata-rata</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Form validation for nilai field
    document.getElementById('nilai').addEventListener('input', function() {
        if (this.value < 0) this.value = 0;
        if (this.value > 1) this.value = 1;
    });

    // Add animation to cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection
@endsection
