@extends('layouts.app')

@section('title', 'Detail proyektor')

@section('content')
<div class="bg-gray-50 text-gray-900">
    <!-- Header -->
        <!-- Global Alert -->
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 rounded-lg bg-red-100 border border-red-300 text-red-700 shadow-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 rounded-lg bg-green-100 border border-green-300 text-green-700 shadow-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <header class="pt-6 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <div class="flex items-center space-x-4">
                    <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-arrow-left text-gray-600"></i>
                    </button>
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">Detail proyektor</h1>
                        <p class="text-sm text-gray-500">Informasi lengkap proyektor</p>
                    </div>
                </div>

                <a href="{{ route('admin.sarpras.index') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-gray-50 active:bg-gray-100">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19 12H5m0 0l6-6m-6 6l6 6"/>
                    </svg>
                    Kembali
                </a>

            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden">
                        @if($p->gambar) <img src="{{ asset('storage/' . $p->gambar) }}" alt="{{ $p->nama_proyektor }}" class="aspect-video w-full rounded-xl object-cover shadow-md"> @else <div class="flex aspect-video w-full items-center justify-center rounded-xl bg-gray-100"> <span class="text-gray-400">Tidak ada gambar</span> </div> @endif
                    </div>

                    @php
                        $status = $p->status->nama_status ?? 'Unknown';
                        $colors = [
                            'Tersedia'   => 'bg-green-50 text-green-700 border-green-200',
                            'Dipakai'    => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                            'Diperbaiki' => 'bg-orange-50 text-orange-700 border-orange-200',
                            'Rusak'      => 'bg-red-50 text-red-700 border-red-200',
                        ];
                        $color = $colors[$status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                    @endphp

                    <div class="mt-4 flex items-center justify-between">
                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium {{ $color }}">
                            {{ $status }}
                        </span>
                    </div>

                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in hover-lift">

                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        {{ $p->nama_proyektor ?? 'Nama proyektor' }}
                    </h2>

                    <div class="space-y-6">

                        <!-- Status -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Status proyektor</h3>
                            <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium {{ $color }}">
                                {{ $status }}
                            </span>
                        </div>

                        {{-- merk --}}
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Merk proyektor</h3>
                            <p class="mt-1 text-gray-900">{{ $p->merk }}</p>
                        </div>

                        <!-- Kode proyektor -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Kode proyektor</h3>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $p->kode_proyektor }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between pt-4 border-t border-blue-100">

                            <a href="{{ route('sarpras.proyektor.edit_proyektor', $p->id_proyektor) }}"
                               class="flex px-8 py-1.5 text-sm font-medium text-blue-700 bg-blue-100 border border-blue-300 rounded-lg hover:bg-blue-50 transition-colors">
                               <svg class="-ml-1 mr-2 h-5 w-5 text-blue-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>

                            <!-- Tombol Hapus -->
                            <button
                                type="button"
                                onclick="openModal('{{ $p->id_proyektor }}')"
                                class="inline-flex items-center gap-1 rounded-md border border-red-300 bg-white px-6 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 transition"
                            >
                                <!-- Icon Trash -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Hapus
                            </button>

                            <!-- Modal Konfirmasi -->
                            <div id="modal-{{ $p->id_proyektor }}"
                                class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm text-center">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Hapus</h2>
                                    <p class="text-sm text-gray-600 mb-4">
                                        Apakah Anda yakin ingin menghapus proyektor <b>{{ $p->nama_proyektor }}</b>?
                                    </p>

                                    <form action="{{ route('sarpras.proyektor.destroy', $p->id_proyektor) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <div class="flex justify-center gap-3 mt-4">
                                            <button type="button"
                                                    onclick="closeModal('{{ $p->id_proyektor }}')"
                                                    class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700">
                                                Batal
                                            </button>

                                            <button type="submit"
                                                    class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById('modal-' + id).classList.remove('hidden');
        document.getElementById('modal-' + id).classList.add('flex');
    }

    function closeModal(id) {
        document.getElementById('modal-' + id).classList.add('hidden');
        document.getElementById('modal-' + id).classList.remove('flex');
    }
</script>
@endpush
