<?php $__env->startSection('title', 'Jadwal Mata Kuliah'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{ showImport: false }" class="min-h-screen p-6 bg-gray-50/50 space-y-6">

    <!-- Header & Actions -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Jadwal Mata Kuliah</h1>
            <p class="text-sm text-gray-500">Kelola jadwal perkuliahan, ruang, dan waktu.</p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="showImport = !showImport"
                :class="showImport ? 'bg-gray-200 text-gray-800' : 'bg-white text-gray-700 hover:bg-gray-50'"
                class="flex items-center gap-2 px-4 py-2 text-sm font-medium transition border border-gray-300 rounded-lg shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span x-text="showImport ? 'Tutup Import' : 'Import Excel'"></span>
            </button>
            <a href="<?php echo e(route('admin.jadwal.create')); ?>" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Jadwal
            </a>
        </div>
    </div>

    <div x-show="showImport"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="p-5 bg-white border border-blue-100 rounded-xl shadow-sm ring-1 ring-blue-500/10">
        <form action="<?php echo e(route('admin.jadwal.import.store')); ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <?php echo csrf_field(); ?>
            <div class="flex-1 w-full">
                <label class="block mb-2 text-sm font-medium text-gray-700">Upload File Excel (.xlsx/.xls)</label>
                <input type="file" name="file" accept=".xls,.xlsx" required
                    class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition shadow-sm">
                Proses Import
            </button>
        </form>
        <p class="mt-2 text-[10px] uppercase tracking-wider text-gray-400 font-bold mt-0.5">*Pastikan format header file Excel sesuai dengan template sistem.</p>
    </div>

    <!-- Tabel Jadwal -->
    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-gray-900 uppercase text-xs">Kode MK</th>
                        <th class="px-6 py-4 font-semibold text-gray-900 uppercase text-xs">Mata Kuliah & Sistem</th>
                        <th class="px-6 py-4 font-semibold text-gray-900 uppercase text-xs">Kelas & Sebaran</th>
                        <th class="px-6 py-4 font-semibold text-gray-900 uppercase text-xs text-center">Waktu</th>
                        <th class="px-6 py-4 font-semibold text-gray-900 uppercase text-xs text-center">Ruang</th>
                        <th class="px-6 py-4 font-semibold text-gray-900 uppercase text-xs text-center">Kuota</th>
                        <th class="px-6 py-4 font-semibold text-gray-900 uppercase text-xs text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $j; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4 font-medium text-gray-700">
                                <?php echo e($jadwal->kode_mk); ?>

                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-700"><?php echo e($jadwal->nama_kelas); ?></span>
                                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mt-0.5"><?php echo e($jadwal->sistem_kuliah); ?></span>
                                </div>
                            </td>

                            <!-- Kelas & Sebaran -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($jadwal->kelas_mahasiswa); ?></span>
                                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mt-0.5"><?php echo e($jadwal->sebaran_kelas); ?></span>
                                </div>
                            </td>

                            <!-- Waktu (Hari & Jam) -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="font-medium text-gray-700"><?php echo e($jadwal->hari); ?></span>
                                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mt-0.5">
                                        <?php echo e(\Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')); ?>

                                    </span>
                                </div>
                            </td>

                            <!-- Ruangan -->
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center text-xs font-medium text-gray-800">
                                    <?php echo e($jadwal->ruangan); ?>

                                </span>
                            </td>

                            <!-- Daya Tampung -->
                            <td class="px-6 py-4 text-center">
                                <span class="font-medium text-gray-900">
                                    <?php echo e($jadwal->daya_tampung); ?>

                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit -->
                                    <a href="<?php echo e(route('admin.jadwal.edit', $jadwal->id_jadwal)); ?>" class="p-2 text-xs text-blue-600 transition bg-blue-50 rounded hover:bg-blue-100 hover:text-blue-700" title="Edit Jadwal">Edit
                                    </a>

                                    <!-- Delete -->
                                    <button type="button" onclick="openModal('<?php echo e($jadwal->id_jadwal); ?>')" class="p-2 text-red-600 transition bg-red-50 rounded text-xs hover:bg-red-100 hover:text-red-700" title="Hapus Jadwal">Hapus</button>

                                    <!-- Modal Konfirmasi -->
                                    <div id="modal-<?php echo e($jadwal->id_jadwal); ?>" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                                        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm text-center">
                                            <h2 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Hapus</h2>
                                            <p class="text-sm text-gray-600 mb-4">
                                                Apakah Anda yakin ingin menghapus jadwal <b><?php echo e($jadwal->nama_kelas); ?></b>?
                                            </p>

                                            <form action="<?php echo e(route('admin.jadwal.destroy', $jadwal->id_jadwal)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>

                                                <div class="flex justify-center gap-3 mt-4">
                                                    <button type="button" onclick="closeModal('<?php echo e($jadwal->id_jadwal); ?>')" class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700">
                                                        Batal
                                                    </button>

                                                    <button type="submit" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <p class="text-base font-medium text-gray-900">Belum ada jadwal</p>
                                    <p class="text-sm">Silakan tambah manual atau import data excel.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/notif.js']); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/jadwal/index.blade.php ENDPATH**/ ?>