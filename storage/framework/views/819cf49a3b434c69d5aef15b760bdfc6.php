<?php $__env->startSection('title', 'Detail Sarana & Prasarana'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-gray-50 min-h-screen py-8 font-sans text-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <a href="<?php echo e(route('public.sarana_perasarana.halamansarpras')); ?>"
               class="group inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#179ACE] transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
            </a>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    
                    <div class="aspect-w-4 aspect-h-3 bg-gray-200 relative h-64">
                        <?php if($sarpras->gambar): ?>
                            <img src="<?php echo e(asset('storage/' . str_replace('public/', '', $sarpras->gambar))); ?>"
                                 alt="<?php echo e($sarpras->nama_ruangan ?? $sarpras->nama_proyektor); ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 flex-col">
                                <i class="fa-regular fa-image text-4xl mb-2"></i>
                                <span>Tidak Ada Gambar</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur text-gray-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm uppercase tracking-wide">
                                <?php echo e($type === 'ruangan' ? 'Ruangan' : 'Proyektor'); ?>

                            </span>
                        </div>
                    </div>

                    
                    <div class="p-5">
                        <div class="flex justify-between">
                            <h1 class="text-xl font-bold text-gray-700 mb-1">
                                <?php echo e($sarpras->nama_ruangan ?? $sarpras->nama_proyektor ?? 'Nama Tidak Diketahui'); ?>

                            </h1>
                            <div>
                                <?php if(!$mainPeminjaman || $mainPeminjaman->status_peminjaman === 'Tersedia' || $resourceStatus === 'Tersedia'): ?>
                                        <a href="<?php echo e(route('public.peminjaman.create', ['sarpras_type' => $type, 'sarpras_id' => $sarpras->id_ruangan ?? $sarpras->id_proyektor])); ?>"
                                        class="block w-full text-center text-xs px-4 py-2.5 border hover:text-[#179ACE] hover:border-[#179ACE] hover:bg-white text-gray-700 bg-gray-200 font-medium rounded-lg transition-colors">
                                            Ajukan Peminjaman
                                        </a>
                                    <?php else: ?>
                                        <button disabled class="block w-full px-4 py-3 bg-gray-100 text-gray-400 font-semibold rounded-lg cursor-not-allowed border border-gray-200">
                                            Sedang Dipakai
                                        </button>
                                    <?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="text-sm text-gray-500 mb-4 flex items-start">
                             <i class="fa-solid fa-location-dot mt-1 text-gray-400"></i>
                             <span><?php echo e($sarpras->merk); ?></span>
                        </div>

                        
                        <div class="flex items-center mb-6">
                            <div class="flex text-yellow-400 text-sm">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="mx-1.5 text-gray-300">•</span>
                            <span class="text-sm text-gray-500"><?php echo e($feedbacks->count()); ?> Reviews</span>
                        </div>

                        <hr class="border-gray-100 mb-6">

                        <div class="space-y-3">
                            
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-600">Status:</span>
                                <?php if($mainPeminjaman): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        <?php echo e($mainPeminjaman->status_peminjaman); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($resourceStatus === 'Tersedia' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'); ?>">
                                        <?php echo e($resourceStatus); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-gray-700 mb-4">Spesifikasi</h3>
                        <div class="space-y-3">
                            <?php if($type === 'ruangan'): ?>
                                <div class="flex justify-between border-b border-gray-50 pb-2 last:border-0">
                                    <span class="text-sm text-gray-500">Kapasitas</span>
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($sarpras->kapasitas ?? '-'); ?> Orang</span>
                                </div>
                            <?php else: ?>
                                <div class="flex justify-between border-b border-gray-50 pb-2">
                                    <span class="text-sm text-gray-500">Merk</span>
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($sarpras->merk ?? '-'); ?></span>
                                </div>
                                <div class="flex justify-between border-b border-gray-50 pb-2 last:border-0">
                                    <span class="text-sm text-gray-500">Kode Aset</span>
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($sarpras->kode_proyektor ?? '-'); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-2 space-y-8">

                
                <?php if(!empty($sarpras->deskripsi)): ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-700 text-lg mb-3">Tentang Fasilitas Ini</h3>
                        <div class="text-gray-600 leading-relaxed text-sm">
                            <?php echo e($sarpras->deskripsi); ?>

                        </div>
                    </div>
                <?php endif; ?>

                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                    
                    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white sticky top-0 z-10">
                        <h3 class="text-xl font-bold text-gray-700">All Feedback</h3>

                        <?php if(Auth::check()): ?>
                            <a href="<?php echo e(route('public.feedback.index', ['id_sarpras' => $sarpras->id_ruangan ?? $sarpras->id_proyektor, 'type' => $type])); ?>"
                               class="inline-flex items-center px-4 py-2 bg-[#4285F4] hover:bg-[#3367D6] text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                <i class="fa-solid fa-plus mr-2"></i> Submit Feedback
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="text-sm text-[#179ACE] hover:underline font-medium">
                                Login untuk review
                            </a>
                        <?php endif; ?>
                    </div>

                    
                    <div class="divide-y divide-gray-100">
                        <?php if($feedbacks && $feedbacks->count() > 0): ?>
                            <?php $__currentLoopData = $feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm">
                                                <?php if($feedback->user->avatar): ?>
                                                    <img src="<?php echo e(str_starts_with($feedback->user->avatar, 'http') ? $feedback->user->avatar : asset($feedback->user->avatar)); ?>"
                                                    onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo e(urlencode($feedback->user->nama)); ?>&background=random';"
                                                    alt="Profile"
                                                    class="h-full w-full rounded-full object-cover border border-gray-200 shadow-sm">
                                                <?php else: ?>
                                                    <div class="h-full w-full rounded-full bg-[#179ACE] text-white flex items-center justify-center font-medium text-xl shadow-sm">
                                                    <?php echo e(strtoupper(substr($feedback->user->nama, 0, 1))); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-700 text-sm"><?php echo e($feedback->user->nama); ?></h4>
                                                <p class="text-xs text-gray-400"><?php echo e($feedback->created_at->format('M d, Y')); ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex text-yellow-400 text-xs">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                    </div>

                                    <div class="pl-13 mt-2">
                                        <p class="text-gray-600 text-sm leading-relaxed">
                                            <?php echo e($feedback->isi_feedback); ?>

                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-center items-center">
                                <?php echo e($feedbacks->onEachSide(1)->links()); ?>

                            </div>

                        <?php else: ?>
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-regular fa-comment-dots text-gray-300 text-2xl"></i>
                                </div>
                                <h3 class="text-gray-700 font-medium">Belum ada ulasan</h3>
                                <?php if($type === 'proyektor'): ?>
                                    <p class="text-gray-500 text-sm mt-1">Berikan Feedback Untuk Memperbaiki Proyektor Ini.</p>
                                <?php else: ?>
                                    <p class="text-gray-500 text-sm mt-1">Berikan Feedback Untuk Memperbaiki Ruangan Ini.</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/public/sarana_perasarana/detail_sarpras.blade.php ENDPATH**/ ?>