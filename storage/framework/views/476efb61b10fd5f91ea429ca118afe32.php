<?php $__env->startSection('title', 'Jadwal Mata Kuliah'); ?>

<?php $__env->startSection('content'); ?>

<div class="p-6 bg-white rounded-lg h-full">
    <div class="flex items-center justify-between mb-6">
        <h2 class="flex items-center gap-2 text-xl font-semibold text-gray-800">
            Daftar Jadwal
        </h2>

        <div class="flex justify-between gap-4 items-center">
            <div class="flex gap-3">
                
                <form action="<?php echo e(route('admin.jadwal.import.store')); ?>" method="POST" enctype="multipart/form-data"
                class="flex flex-row items-center gap-4 bg-white p-2 rounded-lg border border-gray-200 shadow-sm">
                    <?php echo csrf_field(); ?>
                    <input type="file" name="file" accept=".xls,.xlsx" required class="text-sm">
                    <button type="submit" class="flex gap-3 px-3 py-2 text-white transition bg-blue-500 rounded-lg items-center text-sm shadow hover:bg-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Import Jadwal
                    </button>
                </form>
            </div>
            
            <div>
                <a href="<?php echo e(route('admin.jadwal.create')); ?>"
                class="flex gap-3 px-3 py-2 text-sm items-center text-white transition bg-green-600 rounded-lg shadow hover:bg-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                Tambah Jadwal
                </a>
            </div>
        </div>
    </div>

    
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-gray-700 font-medium text-sm">Kode MK</th>
                    <th class="px-4 py-3 text-gray-700 font-medium text-sm">Nama Kelas</th>
                    <th class="px-4 py-3 text-gray-700 font-medium text-sm">Kelas</th>
                    <th class="px-4 py-3 text-gray-700 font-medium text-sm">Hari</th>
                    <th class="px-4 py-3 text-gray-700 font-medium text-sm text-center">Jam</th>
                    <th class="px-4 py-3 text-gray-700 font-medium text-sm text-center">Ruangan</th>
                    <th class="px-4 py-3 text-gray-700 font-medium text-sm">Daya Tampung</th>
                    <th class="px-4 py-3 text-gray-700 font-medium text-sm text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $j; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2"><?php echo e($jadwal->kode_mk); ?></td>
                        <td class="px-4 py-2"><?php echo e($jadwal->nama_kelas); ?></td>
                        <td class="px-4 py-2"><?php echo e($jadwal->kelas_mahasiswa); ?></td>
                        <td class="px-4 py-2"><?php echo e($jadwal->hari); ?></td>
                        <td class="px-4 py-2 text-center">
                            <?php echo e(\Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')); ?> -
                            <?php echo e(\Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')); ?>

                        </td>
                        <td class="px-4 py-2 text-center"><?php echo e($jadwal->ruangan); ?></td>
                        <td class="px-4 py-2 text-center"><?php echo e($jadwal->daya_tampung); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="<?php echo e(route('admin.jadwal.edit', $jadwal->id_jadwal)); ?>" class="text-blue-600 hover:text-blue-900 px-4 py-1.5 bg-blue-300 text-xs rounded-sm mr-2">Edit</a>

                            
                            <form action="<?php echo e(route('admin.jadwal.destroy', $jadwal->id_jadwal)); ?>" method="POST" class="inline-block form-delete">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-700 px-4 py-1.5 bg-red-300 text-xs rounded-sm hover:text-red-900">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-gray-500">Belum ada data jadwal</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/notif.js']); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/jadwal/index.blade.php ENDPATH**/ ?>