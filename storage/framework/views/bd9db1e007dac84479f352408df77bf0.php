<?php $__env->startSection('content'); ?>

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">

    <div class="max-w-7xl mx-auto" x-data="{ showAHP: false, showSAW: false }">

        <!-- Header Section -->
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-light text-gray-800 mb-2">
                Prioritas Peminjaman Ruangan
            </h2>
            <div class="w-24 h-0.5 bg-gray-300 mx-auto"></div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 mb-8">
            <div class="border-b border-gray-200 px-6 py-4">
                <h4 class="uppercase text-sm">
                    Table Peminjam
                </h4>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Nama Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Ruangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Keperluan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Jam</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php $no = 1; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $peminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($no++); ?></td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo e($p->nama_peminjam ?? '-'); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <?php echo e(optional($p->ruangan)->nama_ruangan ?? '-'); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($p->keperluan ?? $p->jenis_kegiatan ?? '-'); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600 text-center"><?php echo e(\Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y')); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                    <?php echo e($p->jam_mulai); ?> - <?php echo e($p->jam_selesai); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    Tidak ada data peminjaman yang perlu diprioritaskan.
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol Hitung AHP -->
        <div class="flex justify-end mb-6">
            <button
                @click="showAHP = !showAHP; showSAW = false"
                :class="showAHP ? 'bg-gray-700 text-white' : 'bg-white text-gray-700 border border-gray-300'"
                class="font-medium px-6 py-2 rounded-lg transition-all duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <span x-text="showAHP ? 'Tutup Detail AHP' : 'Hitung Bobot AHP'"></span>
            </button>
        </div>

        <!-- BAGIAN AHP -->
        <div x-show="showAHP"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-lg border border-gray-200 mb-8">
                <div class="border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-700 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Perhitungan AHP (Analytic Hierarchy Process)
                        </h3>
                        <a href="<?php echo e(route('admin.kriteria.create')); ?>"
                           class="text-gray-600 hover:text-gray-800 font-medium px-4 py-2 rounded-lg text-sm transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Kriteria
                        </a>
                    </div>
                </div>

                <div class="p-6 space-y-8">
                    <!-- Matriks Perbandingan -->
                    <div>
                        <h4 class="text-base font-medium mb-3 text-gray-700 flex items-center">
                            <span class="text-gray-500 mr-2">1.</span>
                            Matriks Perbandingan Berpasangan
                        </h4>
                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="border-r border-gray-200 px-4 py-3 text-left text-xs font-medium text-gray-600">Kriteria</th>
                                        <?php $__currentLoopData = $kriteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th class="border-r border-gray-200 px-4 py-3 text-center text-xs font-medium text-gray-600"><?php echo e($value['nama_asli']); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php $__currentLoopData = $pairwiseMatrix; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <?php
                                            $rowKey = array_keys($kriteria)[$i];
                                            $rowName = $kriteria[$rowKey]['nama_asli'];
                                        ?>
                                        <td class="border-r border-gray-200 px-4 py-3 font-medium bg-gray-50 text-sm text-gray-700"><?php echo e($rowName); ?></td>
                                        <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <td class="border-r border-gray-200 px-4 py-3 text-center text-sm <?php if($val == 1): ?> bg-gray-100 font-semibold <?php endif; ?> text-gray-600">
                                                <?php echo e(number_format($val, 3)); ?>

                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Normalisasi -->
                    <div>
                        <h4 class="text-base font-medium mb-3 text-gray-700 flex items-center">
                            <span class="text-gray-500 mr-2">2.</span>
                            Normalisasi Matriks
                        </h4>
                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="border-r border-gray-200 px-4 py-3 text-left text-xs font-medium text-gray-600">Kriteria</th>
                                        <?php $__currentLoopData = $kriteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th class="border-r border-gray-200 px-4 py-3 text-center text-xs font-medium text-gray-600"><?php echo e($value['nama_asli']); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <th class="border-r border-gray-200 px-4 py-3 text-center text-xs font-medium text-gray-600 bg-gray-100">Bobot</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php $__currentLoopData = $normalizedMatrix; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <?php
                                            $rowKey = array_keys($kriteria)[$i];
                                            $rowName = $kriteria[$rowKey]['nama_asli'];
                                        ?>
                                        <td class="border-r border-gray-200 px-4 py-3 font-medium bg-gray-50 text-sm text-gray-700"><?php echo e($rowName); ?></td>
                                        <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <td class="border-r border-gray-200 px-4 py-3 text-center text-sm text-gray-600"><?php echo e(number_format($val, 3)); ?></td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <td class="border-r border-gray-200 px-4 py-3 text-center font-semibold text-gray-700 bg-gray-50 text-sm">
                                            <?php echo e(number_format($bobotAkhir[$i], 3)); ?>

                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bobot Akhir -->
                    <div>
                        <h4 class="text-base font-medium mb-3 text-gray-700 flex items-center">
                            <span class="text-gray-500 mr-2">3.</span>
                            Bobot Akhir Tiap Kriteria
                        </h4>
                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="border-r border-gray-200 px-4 py-3 text-left text-xs font-medium text-gray-600">Kriteria</th>
                                        <th class="border-r border-gray-200 px-4 py-3 text-left text-xs font-medium text-gray-600">Tipe</th>
                                        <th class="border-r border-gray-200 px-4 py-3 text-center text-xs font-medium text-gray-600">Bobot Akhir</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php $__currentLoopData = $kriteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="border-r border-gray-200 px-4 py-3 font-medium text-sm text-gray-700"><?php echo e($value['nama_asli']); ?></td>
                                        <td class="border-r border-gray-200 px-4 py-3 text-sm">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                <?php if($value['tipe'] == 'benefit'): ?> bg-green-50 text-green-700 <?php else: ?> bg-red-50 text-red-700 <?php endif; ?>">
                                                <?php echo e(ucfirst($value['tipe'])); ?>

                                            </span>
                                        </td>
                                        <td class="border-r border-gray-200 px-4 py-3 text-center text-sm font-semibold text-gray-700"><?php echo e(number_format($value['bobot'], 3)); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Konsistensi Ratio -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div class="mb-4 sm:mb-0">
                                <p class="text-base font-medium text-gray-700 mb-1">Rasio Konsistensi (CR)</p>
                                <p class="text-2xl font-semibold text-gray-800"><?php echo e(number_format($cr, 3)); ?></p>
                            </div>

                            <?php if($cr <= 0.1): ?>
                                <div class="flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-lg font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Konsisten (CR ≤ 0.1)
                                </div>
                            <?php else: ?>
                                <div class="flex items-center px-4 py-2 bg-red-50 text-red-700 rounded-lg font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Tidak Konsisten (CR > 0.1)
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Tombol Hitung SAW -->
                    <div class="flex justify-end">
                        <button
                            @click="showSAW = !showSAW"
                            :disabled="!showAHP"
                            :class="showSAW ? 'bg-gray-700 text-white' : 'bg-white text-gray-700 border border-gray-300'"
                            class="font-medium px-6 py-2 rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span x-text="showSAW ? 'Tutup Hasil SAW' : 'Hitung Perankingan SAW'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN SAW -->
        <div x-show="showSAW"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="bg-white rounded-lg border border-gray-200">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Hasil Akhir Perankingan (SAW)
                    </h3>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="border-r border-gray-200 px-4 py-3 text-left text-xs font-medium text-gray-600">Alternatif</th>
                                    <?php $__currentLoopData = $kriteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="border-r border-gray-200 px-4 py-3 text-center text-xs font-medium text-gray-600"><?php echo e($value['nama_asli']); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <th class="border-r border-gray-200 px-4 py-3 text-center text-xs font-medium text-gray-600 bg-gray-100">Total</th>
                                    <th class="border-r border-gray-200 px-4 py-3 text-center text-xs font-medium text-gray-600 bg-gray-100">Rank</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php $__currentLoopData = $hasil; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150 <?php if($h['ranking'] == 1): ?> bg-yellow-50 border-l-2 border-yellow-400 <?php endif; ?>">
                                    <td class="border-r border-gray-200 px-4 py-3 font-medium text-sm <?php if($h['ranking'] == 1): ?> text-yellow-700 <?php else: ?> text-gray-700 <?php endif; ?>">
                                        <?php if($h['ranking'] == 1): ?>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                <?php echo e($h['nama']); ?>

                                            </div>
                                        <?php else: ?>
                                            <?php echo e($h['nama']); ?>

                                        <?php endif; ?>
                                    </td>

                                    <?php $__currentLoopData = $kriteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="border-r border-gray-200 px-4 py-3 text-center text-sm text-gray-600">
                                            <?php echo e(number_format($alternatif[$index][$key], 2)); ?>

                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <td class="border-r border-gray-200 px-4 py-3 text-center font-semibold text-gray-700 bg-gray-50 text-sm">
                                        <?php echo e($h['nilai']); ?>

                                    </td>

                                    <td class="border-r border-gray-200 px-4 py-3 text-center font-semibold text-lg bg-gray-50">
                                        <?php if($h['ranking'] == 1): ?>
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-yellow-400 text-white text-sm">
                                                1
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-200 text-gray-600 text-sm">
                                                <?php echo e($h['ranking']); ?>

                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/prioritas/ruangan.blade.php ENDPATH**/ ?>