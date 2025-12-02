@extends('layouts.app')

@section('title', 'Konfigurasi Bobot')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 bg-white h-full">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Konfigurasi Bobot</h1>
            <p class="text-sm text-neutral-500">Atur prioritas kriteria dalam perhitungan</p>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-2">
        <form action="{{ route('admin.bobot.store') }}" method="POST">
            @csrf
            <div class="flex justify-start items-center gap-3">
                <div>
                    <input type="text" class="py-1.5 px-3 border border-gray-300 text-sm rounded" name="nama" placeholder="Nama bobot" value="{{ old('nama') }}" required>
                </div>

                <div>
                    <input type="number" step="0.01" class="py-1.5 px-3 border border-gray-300 text-sm rounded w-32 " name="nilai" placeholder="Nilai bobot" value="{{ old('nilai') }}" required>
                </div>

                <button class="py-1.5 px-3 font-medium text-white text-sm rounded bg-blue-500">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-xl shadow-sm border border-neutral-200 bg-white">
        <table class="w-full text-sm text-neutral-700">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">Nama Bobot</th>
                    <th class="px-6 py-3 text-center font-semibold">Nilai</th>
                    <th class="px-6 py-3 text-left font-semibold">Keterangan</th>
                    <th class="px-6 py-3 font-semibold w-20 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-neutral-200">
                @foreach ($bobot as $row)
                <tr class="group hover:bg-neutral-50 transition">
                    <td class="px-6 py-4">{{ $row->nama }}</td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-600">
                        {{ number_format($row->nilai, 2) }}
                    </td>
                    <td class="px-6 py-4">{{ $row->keterangan_bobot ?? '-' }}</td>

                    <!-- Kolom Aksi -->
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex gap-2">

                            <!-- Edit -->
                            <a href="{{ route('admin.prioritas.bobot.edit', $row->id) }}"
                               class="p-1.5 rounded-lg hover:bg-blue-100 text-blue-600 hover:text-blue-700 transition"
                               title="Edit">Edit
                            </a>

                            <!-- Delete -->
                            <button type="button"
                                onclick="openDeleteModal('{{ route('admin.prioritas.bobot.destroy', $row->id) }}', '{{ $row->nama }}')"
                                class="p-1.5 rounded-lg hover:bg-rose-100 text-rose-600 hover:text-rose-700 transition"
                                title="Hapus">Hapus
                            </button>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-neutral-200">
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div>
                    <div class="sm:grid sm:items-start">
                        <div class="flex justify-start gap-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            <h3 class="text-lg font-semibold leading-6 text-neutral-900" id="modal-title">Hapus Kriteria?</h3>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-neutral-500">
                                Apakah Anda yakin ingin menghapus kriteria <span id="modalItemName" class="font-bold text-neutral-800"></span>?
                                Tindakan ini tidak dapat dibatalkan dan akan mempengaruhi perhitungan total bobot.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="bg-neutral-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-neutral-100">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-rose-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 sm:ml-3 sm:w-auto transition-colors">
                            Ya, Hapus
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 sm:mt-0 sm:w-auto transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // --- Input Visualization Logic ---
    const input = document.getElementById('nilai');
    const inputBar = document.getElementById('inputBar');
    const display = document.getElementById('nilaiDisplay');

    if(input) {
        input.addEventListener('input', updateVisuals);
        updateVisuals();
    }

    function updateVisuals() {
        let val = parseFloat(input.value) || 0;
        // Clamp value between 0 and 1
        val = Math.min(Math.max(val, 0), 1);

        inputBar.style.width = `${val * 100}%`;

        display.textContent = val.toFixed(2);

        if(val > 1) {
            inputBar.classList.remove('bg-blue-500');
            inputBar.classList.add('bg-rose-500');
        } else {
            inputBar.classList.remove('bg-rose-500');
            inputBar.classList.add('bg-blue-500');
        }
    }

    const modal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const itemNameSpan = document.getElementById('modalItemName');

    function openDeleteModal(actionUrl, itemName) {
        deleteForm.action = actionUrl;
        itemNameSpan.textContent = itemName;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        modal.classList.add('hidden');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape" && !modal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    });
</script>
@endpush
