<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 pb-0">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight"><?php echo e($kriteria->nama_kriteria); ?></h1>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold uppercase tracking-wider mb-2 
                            <?php echo e($kriteria->tipe === 'benefit' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'); ?>">
                            <?php echo e($kriteria->tipe); ?>

                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="justify-between items-center">
                            <a href="<?php echo e(route('admin.kriteria.index')); ?>" class="flex px-6 py-2 text-xs font-semibold border border-gray-300 rounded-lg text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-wider">
                                Tutup
                            </a>
                        </div>
                        <div class="justify-between items-center">
                            <a href="<?php echo e(route('admin.kriteria.edit', $kriteria)); ?>" 
                            class="flex px-6 py-2 text-xs font-semibold border border-yellow-300 rounded-lg text-yellow-600 hover:text-yellow-700 transition-colors uppercase tracking-wider">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex items-center gap-5">
                        <div class="h-14 w-14 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Bobot Kriteria</p>
                            <p class="text-3xl font-black text-gray-900"><?php echo e(number_format($kriteria->bobot, 4)); ?></p>
                        </div>
                    </div>

                    <div class="rounded-2xl p-6 border flex items-center gap-5 
                        <?php echo e($kriteria->tipe === 'benefit' ? 'bg-emerald-50/50 border-emerald-100' : 'bg-amber-50/50 border-amber-100'); ?>">
                        <div class="h-14 w-14 rounded-xl flex items-center justify-center shadow-lg 
                            <?php echo e($kriteria->tipe === 'benefit' ? 'bg-emerald-500 text-white shadow-emerald-200' : 'bg-amber-500 text-white shadow-amber-200'); ?>">
                            <?php if($kriteria->tipe === 'benefit'): ?>
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                            <?php else: ?>
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Sifat Kriteria</p>
                            <p class="text-lg font-bold text-gray-900 leading-tight">
                                <?php echo e($kriteria->tipe === 'benefit' ? 'Semakin Tinggi Semakin Baik' : 'Semakin Rendah Semakin Baik'); ?>

                            </p>
                        </div>
                    </div>
                </div>

                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 mb-8">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="h-4 w-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        Analisis Penggunaan
                    </h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Kriteria <span class="font-bold text-gray-900">"<?php echo e($kriteria->nama_kriteria); ?>"</span> akan berkontribusi sebesar <span class="font-bold text-gray-900"><?php echo e(number_format($kriteria->bobot * 100, 2)); ?>%</span> dalam perhitungan keputusan akhir. 
                        Sistem akan mengolah data ini menggunakan normalisasi skala 
                        <span class="px-2 py-0.5 bg-white border border-gray-200 rounded-md text-gray-700 text-xs"><?php echo e($kriteria->tipe); ?></span>.
                    </p>
                </div>

                
                <div class="flex flex-col sm:flex-row justify-between gap-4 text-xs text-gray-400 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span>Ditambahkan pada <?php echo e($kriteria->created_at->translatedFormat('d F Y, H:i')); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Pembaruan terakhir: <?php echo e($kriteria->updated_at->diffForHumans()); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/kriteria/show.blade.php ENDPATH**/ ?>