@extends('layouts.app')

@section('title', 'Sarana')

@section('content')
<div class="container mx-auto max-w-7xl">
    <!-- Header Card -->
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Judul -->
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Sarana & Prasarana</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola ruangan dan proyektor</p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" placeholder="Cari sarpras..."
                            value="{{ request('search') }}"
                            class="w-full sm:w-64 pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm
                                   focus:ring-1 focus:ring-[#8bc9e2] focus:border-transparent focus:outline-none
                                   transition-all duration-200" />
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                        </svg>
                    </div>

                    <div class="relative">
                        <select id="status-filter" class="w-full sm:w-40 pl-3 pr-10 py-2.5 border border-gray-200 rounded-lg text-sm
                                   focus:ring-1 focus:ring-[#8bc9e2] focus:border-transparent focus:outline-none
                                   transition-all duration-200 appearance-none bg-white">
                            <option value="">Semua Status</option>
                            @foreach($s as $status)
                                <option value="{{ $status->nama_status }}" {{ request('nama_status') == $status->nama_status ? 'selected' : '' }}>
                                    {{ $status->nama_status }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400 pointer-events-none" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <button id="btn-tambah-sarpras"
                        class="bg-[#179ACE] hover:bg-[#0F6A8F] text-white px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-normal transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Sarpras
                    </button>
                </div>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 p-4">
            @forelse ($items as $item)
                <div class="bg-white rounded-xl border border-gray-500 hover:shadow-sm transition overflow-hidden">
                    <div class="w-full aspect-[4/3] overflow-hidden">
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . str_replace('public/', '', $item->gambar)) }}"
                                alt="{{ $item->nama }}"
                                class="w-full h-full object-cover">
                        @else
                            <img src="https://via.placeholder.com/400x250?text=Tidak+Ada+Gambar"
                                alt="Tidak Ada Gambar"
                                class="w-full h-full object-cover">
                        @endif
                    </div>

                    <div class="p-3">
                        <h2 class="text-gray-800 font-semibold">{{ $item->nama }}</h2>
                        <p class="text-xs font-medium text-gray-500 mb-3">
                            {{ $item->detail ?? '-' }}
                        </p>

                        <div class="mb-4">
                            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full
                                {{ $item->nama_status == 'Tersedia' ? 'bg-green-100 text-green-700' :
                                ($item->nama_status == 'Dipakai' ? 'bg-yellow-100 text-yellow-700' :
                                ($item->nama_status == 'Diperbaiki' ? 'bg-orange-100 text-orange-700' :
                                'bg-red-100 text-red-700')) }}">
                                {{ $item->nama_status ?? 'Status Tidak Ditemukan' }}
                            </span>
                        </div>

                        @if($item->type === 'ruangan')
                            <a href="{{ route('sarpras.ruangan.lihat_ruangan', $item->id) }}"
                            class="block text-center bg-[#66bfe2] hover:bg-[#179ACE] text-white py-1.5 border border-gray-200 rounded-lg text-xs font-normal transition">
                                Lihat Detail
                            </a>
                        @else
                            <a href="{{ route('sarpras.proyektor.lihat_proyektor', $item->id) }}"
                            class="block text-center bg-[#66bfe2] hover:bg-[#179ACE] text-white py-1.5 border border-gray-200 rounded-lg text-xs font-normal transition">
                                Lihat Detail
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">Tidak ada data sarpras</p>
                </div>
            @endforelse
        </div>
        <!-- Pagination Links -->
        <div class="mt-8 flex justify-center items-center px-4 pb-4">
            {{ $items->appends(request()->query())->links('pagination.tailwind-custom') }}
        </div>

    </div>
</div>

<!-- Modal Tambah Sarpras -->
<div id="modal-tambah-sarpras"
    class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-80 text-center">
        <h2 class="text-lg font-bold text-gray-800 mb-2">Tambah Sarpras</h2>
        <p class="text-sm text-gray-500 mb-5">
            Pilih jenis sarana & prasarana yang ingin ditambahkan:
        </p>

        <div class="flex flex-col gap-3">
            <a href="{{ route('sarpras.ruangan.tambah_ruangan') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition">
                Tambah Ruangan
            </a>
            <a href="{{ route('sarpras.proyektor.tambah_proyektor') }}"
                class="text-white py-2 rounded-lg bg-green-500 hover:bg-green-600 transition border border-gray-300">
                Tambah Proyektor
            </a>
        </div>

        <button id="btn-tutup-modal"
            class="mt-5 text-gray-500 hover:text-gray-700 px-4 py-1.5 text-sm font-medium">
            Batal
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modal-tambah-sarpras');
    const openBtn = document.getElementById('btn-tambah-sarpras');
    const closeBtn = document.getElementById('btn-tutup-modal');

    const searchInput = document.querySelector('input[placeholder="Cari sarpras..."]');
    const statusFilter = document.getElementById('status-filter');

    // Handle modal
    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const searchTerm = this.value.trim();
            window.location.href = '/admin/sarpras?search=' + encodeURIComponent(searchTerm);
        }
    });

    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = this.value.trim();
            const url = new URL(window.location);
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }, 500);
    });

    statusFilter.addEventListener('change', function() {
        const selectedStatus = this.value;
        const url = new URL(window.location);
        if (selectedStatus) {
            url.searchParams.set('nama_status', selectedStatus);
        } else {
            url.searchParams.delete('nama_status');
        }
        window.location.href = url.toString();
    });

    // Set nilai filter status dari URL saat halaman dimuat
    const urlParams = new URLSearchParams(window.location.search);
    const statusParam = urlParams.get('nama_status');
    if (statusParam) {
        statusFilter.value = statusParam;
    }
});
</script>
@endsection
