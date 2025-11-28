@extends('layouts.app')

@section('styles')
<style>
    /* Force reset colors for bobot page */
    .text-gray-800 { color: #1f2937 !important; }
    .text-gray-600 { color: #4b5563 !important; }
    .text-gray-500 { color: #6b7280 !important; }
    .text-gray-400 { color: #9ca3af !important; }
    .text-gray-900 { color: #111827 !important; }
    .text-blue-600 { color: #2563eb !important; }
    .text-blue-500 { color: #3b82f6 !important; }
    .text-blue-700 { color: #1d4ed8 !important; }
    .text-red-600 { color: #dc2626 !important; }
    .text-red-700 { color: #b91c1c !important; }
    .text-red-900 { color: #7f1d1d !important; }
    .text-green-600 { color: #16a34a !important; }
    .text-green-700 { color: #15803d !important; }
    .text-green-500 { color: #22c55e !important; }
    .text-yellow-600 { color: #d97706 !important; }
    .text-indigo-600 { color: #4f46e5 !important; }
    .text-white { color: #ffffff !important; }

    .bg-white { background-color: #ffffff !important; }
    .bg-gray-100 { background-color: #f3f4f6 !important; }
    .bg-gray-200 { background-color: #e5e7eb !important; }
    .bg-blue-100 { background-color: #dbeafe !important; }
    .bg-blue-600 { background-color: #2563eb !important; }
    .bg-blue-700 { background-color: #1d4ed8 !important; }
    .bg-red-100 { background-color: #fee2e2 !important; }
    .bg-red-600 { background-color: #dc2626 !important; }
    .bg-red-800 { background-color: #991b1b !important; }
    .bg-green-100 { background-color: #dcfce7 !important; }
    .bg-green-500 { background-color: #22c55e !important; }
    .bg-yellow-100 { background-color: #fef3c7 !important; }
    .bg-indigo-100 { background-color: #e0e7ff !important; }

    .border-gray-200 { border-color: #e5e7eb !important; }
    .border-gray-300 { border-color: #d1d5db !important; }
    .border-gray-400 { border-color: #9ca3af !important; }

    .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important; }

    /* Ensure form inputs have proper colors */
    .form-input {
        background-color: #ffffff !important;
        border-color: #d1d5db !important;
        color: #1f2937 !important;
    }

    .form-input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }

    /* Reset table colors */
    .bg-gray-50 { background-color: #f9fafb !important; }

    /* Reset progress bar colors */
    .bg-blue-600 { background-color: #2563eb !important; }
</style>
@endsection

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="flex items-center gap-2 text-xl font-semibold text-gray-800">
                <i class="fas fa-chart-pie text-blue-500"></i> Kelola Bobot Prioritas
            </h2>
            <p class="text-gray-600">Kelola bobot prioritas untuk sistem penilaian</p>
        </div>
        <div class="p-3 bg-blue-100 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-chart-pie text-blue-600 text-2xl mr-2"></i>
                <div>
                    <small class="block text-gray-500">Total Bobot</small>
                    <strong class="text-xl font-bold text-blue-600">{{ number_format($total, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <svg class="fill-current h-6 w-6 text-green-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle mr-2 mt-1"></i>
                <div>
                    <strong class="font-bold">Terjadi kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <svg class="fill-current h-6 w-6 text-red-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Form Section -->
        <div class="w-full lg:w-1/3 mb-6 lg:mb-0">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 h-full">
                <div class="p-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-plus-circle text-blue-500 mr-2"></i>
                        Tambah Bobot Baru
                    </h3>
                </div>
                <div class="p-5">
                    <form action="{{ route('admin.bobot.store') }}" method="POST" id="addBobotForm">
                        @csrf

                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Bobot</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <input type="text" name="nama" class="form-input pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" id="nama" placeholder="Contoh: Kedisiplinan" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="nilai" class="block text-sm font-medium text-gray-700 mb-1">Nilai Bobot (0-1)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-percentage text-gray-400"></i>
                                </div>
                                <input type="number" name="nilai" step="0.01" min="0" max="1" class="form-input pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" id="nilai" placeholder="0.00" required>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Nilai antara 0 hingga 1 (contoh: 0.25)</p>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>Simpan Bobot
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 h-full">
                <div class="p-5 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-list text-blue-500 mr-2"></i>
                        Daftar Bobot Prioritas
                    </h3>
                    <span class="px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 rounded-full">
                        {{ $bobot->count() }} Data
                    </span>
                </div>
                <div class="p-5">
                    @if($bobot->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-4/12">Nama Bobot</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">Nilai</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($bobot as $row)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $loop->iteration }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 mr-4">
                                                        <i class="fas fa-balance-scale"></i>
                                                    </div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $row->nama }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex items-center justify-center">
                                                    <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-2">
                                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $row->nilai * 100 }}%"></div>
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-900">{{ $row->nilai }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <a href="{{ route('admin.bobot.edit', $row->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                                <button type="button" class="text-red-600 hover:text-red-900" data-modal-target="deleteModal{{ $row->id }}" data-modal-toggle="deleteModal{{ $row->id }}">
                                                    <i class="fas fa-trash mr-1"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Delete Confirmation Modal -->
                                        <div id="deleteModal{{ $row->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                            <div class="relative p-4 w-full max-w-md max-h-full">
                                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                    <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteModal{{ $row->id }}">
                                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                        </svg>
                                                        <span class="sr-only">Close modal</span>
                                                    </button>
                                                    <div class="p-4 md:p-5 text-center">
                                                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                        </svg>
                                                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus bobot "<strong>{{ $row->nama }}</strong>"?</h3>
                                                        <form action="{{ route('admin.bobot.destroy', $row->id) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                                Ya, saya yakin
                                                            </button>
                                                        </form>
                                                        <button data-modal-hide="deleteModal{{ $row->id }}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Tidak, batalkan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-5 text-center">
                            <div class="mb-4">
                                <i class="fas fa-inbox text-gray-400 text-6xl"></i>
                            </div>
                            <h5 class="text-gray-600 text-lg font-medium">Belum Ada Data</h5>
                            <p class="text-gray-500 mt-1">Tambahkan bobot prioritas untuk memulai</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="mt-6 bg-white rounded-lg shadow-md border border-gray-200 p-5">
        <div class="flex flex-wrap -mx-3 items-center">
            <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center mb-3">
                    <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                    Ringkasan Bobot Prioritas
                </h3>
                <div class="w-full bg-gray-200 rounded-full h-6 mb-3">
                    <div class="bg-green-500 h-6 rounded-full text-xs flex items-center justify-center text-white font-bold" style="width: {{ min($total * 100, 100) }}%">
                        {{ number_format($total * 100, 1) }}%
                    </div>
                </div>
                <p class="text-gray-600">Total bobot yang telah ditetapkan: <strong class="font-semibold">{{ number_format($total, 2) }}</strong></p>
                @if($total < 1)
                    <p class="mt-2 text-yellow-600 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Total bobot belum mencapai 1.0. Tambahkan bobot hingga total mencapai 1.0.
                    </p>
                @elseif($total > 1)
                    <p class="mt-2 text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Total bobot melebihi 1.0. Sesuaikan bobot agar total tepat 1.0.
                    </p>
                @else
                    <p class="mt-2 text-green-600 flex items-center">
                        <i class="fas fa-check-circle mr-1"></i>
                        Total bobot sudah tepat (1.0). Sistem siap digunakan.
                    </p>
                @endif
            </div>
            <div class="w-full md:w-1/2 px-3">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="p-4 bg-blue-100 rounded-lg">
                            <h4 class="text-2xl font-bold text-blue-600 mb-1">{{ $bobot->count() }}</h4>
                            <small class="text-gray-500">Total Bobot</small>
                        </div>
                    </div>
                    <div>
                        <div class="p-4 bg-green-100 rounded-lg">
                            <h4 class="text-2xl font-bold text-green-600 mb-1">{{ number_format($total, 2) }}</h4>
                            <small class="text-gray-500">Total Nilai</small>
                        </div>
                    </div>
                    <div>
                        <div class="p-4 bg-indigo-100 rounded-lg">
                            <h4 class="text-2xl font-bold text-indigo-600 mb-1">{{ $bobot->count() > 0 ? number_format($total / $bobot->count(), 2) : '0.00' }}</h4>
                            <small class="text-gray-500">Rata-rata</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
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
@endpush
