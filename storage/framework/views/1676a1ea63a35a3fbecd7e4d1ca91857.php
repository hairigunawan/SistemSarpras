<?php $__env->startSection('title', 'Data Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header & Search Section -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Peminjaman</h1>
                <p class="text-sm text-gray-500">Kelola daftar pengajuan peminjaman sarana & prasarana.</p>
            </div>

            <form method="GET" action="<?php echo e(route('admin.peminjaman.index')); ?>" class="relative">
                <?php if(request('status')): ?>
                    <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
                <?php endif; ?>

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                        class="block w-full sm:w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm"
                        placeholder="Cari nama peminjam...">
                </div>
            </form>
        </div>

        <!-- Alert Section -->
        <?php if(session('success')): ?>
            <div class="mb-6 rounded-md bg-green-50 p-4 border-l-4 border-green-400 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="mb-6 rounded-md bg-red-50 p-4 border-l-4 border-red-400 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?php echo e(session('error')); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Filter Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                <?php
                    $tabs = [
                        'all' => 'Semua',
                        'Menunggu' => 'Menunggu',
                        'Disetujui' => 'Disetujui',
                        'Ditolak' => 'Ditolak',
                        'Selesai' => 'Selesai'
                    ];
                    $currentStatus = request('status', 'all');
                ?>

                <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('admin.peminjaman.index', ['status' => $key])); ?>"
                       class="<?php echo e($currentStatus == $key
                            ? 'border-blue-500 text-blue-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'); ?>

                            whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                        <?php echo e($label); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>
        </div>

        <!-- Table Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sarana Prasarana</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $peminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e(($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo e($item->nama_peminjam ?? $item->user->name ?? 'N/A'); ?>

                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo e($item->user->userRole->nama_role ?? '-'); ?>

                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <?php if($item->ruangan && $item->proyektor): ?>
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium w-fit">
                                                <?php echo e($item->ruangan->nama_ruangan); ?>

                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium w-fit">
                                                <?php echo e($item->proyektor->nama_proyektor); ?>

                                            </span>
                                        </div>
                                    <?php elseif($item->ruangan): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium">
                                            <?php echo e($item->ruangan->nama_ruangan); ?>

                                        </span>
                                    <?php elseif($item->proyektor): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium">
                                            <?php echo e($item->proyektor->nama_proyektor); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400 italic text-xs">Tidak spesifik</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <?php echo e(\Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y')); ?>

                                    <div class="text-xs text-gray-500">
                                        <?php echo e($item->jam_mulai); ?> - <?php echo e($item->jam_selesai); ?>

                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-medium rounded-full
                                        <?php if($item->status_peminjaman == 'Menunggu'): ?> bg-yellow-100 text-yellow-800
                                        <?php elseif($item->status_peminjaman == 'Disetujui'): ?> bg-green-100 text-green-800
                                        <?php elseif($item->status_peminjaman == 'Ditolak'): ?> bg-red-100 text-red-800
                                        <?php elseif($item->status_peminjaman == 'Selesai'): ?> bg-blue-100 text-blue-800
                                        <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                        <?php echo e($item->status_peminjaman); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium flex items-center justify-center gap-2">
                                    <a href="<?php echo e(route('admin.peminjaman.lihat_peminjaman', $item->id_peminjaman)); ?>"
                                       class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors">
                                        Detail
                                    </a>
                                    <?php if($item->status_peminjaman == 'Menunggu'): ?>
                                        <?php if($item->tanggal_pinjam == now()->toDateString()): ?>
                                            <form action="<?php echo e(route('peminjaman.approve', $item->id_peminjaman)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini?')"
                                                    class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-md transition-colors">
                                                    Setujui
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button type="button" 
                                                onclick="showErrorMessage('Peminjaman hanya dapat disetujui pada hari peminjaman yang dijadwalkan (<?php echo e(\Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y')); ?>).')"
                                                class="text-gray-400 bg-gray-100 px-3 py-1.5 rounded-md transition-colors">
                                                Setujui
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-10 w-10 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="text-base font-medium">Tidak ada data pemi    njaman.</p>
                                        <p class="text-sm mt-1">Coba ubah filter status atau kata kunci pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination (Jika ada) -->
            <?php if(method_exists($peminjaman, 'links')): ?>
                <div class="bg-white px-4 py-4 border-t border-gray-200 sm:px-6 flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Menampilkan <span class="font-medium"><?php echo e(($peminjaman->currentPage() - 1) * $peminjaman->perPage() + 1); ?></span>
                        hingga <span class="font-medium"><?php echo e(min($peminjaman->currentPage() * $peminjaman->perPage(), $peminjaman->total())); ?></span>
                        dari <span class="font-medium"><?php echo e($peminjaman->total()); ?></span> data
                    </div>

                    <div class="mt-8 flex justify-center items-center px-4 pb-4">
                        <?php echo e($peminjaman->appends(request()->query())->links('pagination.tailwind-custom')); ?>

                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function showErrorMessage(message) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: message,
            confirmButtonColor: '#d33',
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/peminjaman/index.blade.php ENDPATH**/ ?>