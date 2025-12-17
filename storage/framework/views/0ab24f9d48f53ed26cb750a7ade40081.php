<?php $__env->startSection('title', 'Halaman Feedback'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto py-10 px-6">

    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-comments text-blue-600"></i>
                Feedback Peminjaman
            </h2>
            <p class="text-sm text-gray-500">Berikan masukan untuk meningkatkan kualitas layanan sarana & prasarana.</p>
        </div>
        <a href="<?php echo e(route('public.sarana_perasarana.detail_sarpras', ['type' => $sarpras_type, 'id' => $id_sarpras])); ?>"
           class="inline-flex items-center text-blue-600 hover:text-blue-800 transition font-medium">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    
    <div class="bg-white rounded-2xl shadow-md p-8 mb-10 hover:shadow-lg transition">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square text-blue-500"></i> Tambah Feedback
        </h3>

        <form action="<?php echo e(route('public.feedback.store')); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id_sarpras" value="<?php echo e($id_sarpras); ?>">
            <input type="hidden" name="type" value="<?php echo e($sarpras_type); ?>">
            <input type="hidden" name="id_peminjaman" value="<?php echo e($peminjaman->id_peminjaman); ?>">

                <div>
                    <label for="isi_feedback" class="block text-sm font-medium text-gray-700 mb-2">
                        Isi Feedback <span class="text-red-500">*</span>
                    </label>
                    <textarea id="isi_feedback" name="isi_feedback" rows="5"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700 resize-none"
                              placeholder="Tulis pengalaman atau saran Anda di sini..."
                              required><?php echo e(old('isi_feedback')); ?></textarea>
                    <?php $__errorArgs = ['isi_feedback'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="mt-1 text-sm text-gray-500">Minimal 10 karakter, maksimal 1000 karakter</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-1.5 bg-blue-600 text-white rounded-sm font-medium hover:bg-blue-700 active:scale-[.98] transition duration-200">
                        Kirim Feedback
                    </button>
                </div>
            </form>
        </div>

    
    <?php if($feedbacks->isNotEmpty()): ?>
        <div class="mt-8">
            <h4 class="text-lg font-semibold text-gray-800 mb-5 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-blue-500"></i> Feedback Sebelumnya
            </h4>

            <div class="space-y-6">
                <?php $__currentLoopData = $feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition p-5">
                        <div class="flex items-center gap-3 mb-3">
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
                                <p class="font-semibold text-gray-800"><?php echo e($feedback->user->nama); ?></p>
                                <p class="text-sm text-gray-600"><?php echo e($feedback->user->email); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($feedback->created_at->format('d M Y, H:i')); ?></p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed border-l-4 border-blue-500 pl-3">
                            <?php echo e($feedback->isi_feedback); ?>

                        </p>

                        <?php if(Auth::id() == $feedback->peminjaman->id_akun): ?>
                            <form action="<?php echo e(route('public.feedback.destroy', $feedback)); ?>" method="POST" class="mt-3">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus feedback ini?')"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center gap-1 transition">
                                    <i class="fa-solid fa-trash"></i> Hapus Feedback
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="mt-6 flex justify-center">
                <?php echo e($feedbacks->onEachSide(1)->links()); ?>

            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/public/feedback/index.blade.php ENDPATH**/ ?>