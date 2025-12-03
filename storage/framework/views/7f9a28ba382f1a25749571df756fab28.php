<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kriteria Baru</h1>

            <form action="<?php echo e(route('admin.kriteria.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php $__errorArgs = ['nama_kriteria'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <?php echo e($message); ?>

                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <div>
                    <label for="nama_kriteria" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kriteria
                    </label>
                    <input type="text"
                           id="nama_kriteria"
                           name="nama_kriteria"
                           value="<?php echo e(old('nama_kriteria')); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: Jenis Kegiatan, Jumlah Peserta, Durasi"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Masukkan nama kriteria (maksimal 100 karakter)</p>
                </div>

                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Kriteria
                    </label>
                    <select id="tipe"
                            name="tipe"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Pilih tipe kriteria</option>
                        <option value="benefit" <?php echo e(old('tipe') === 'benefit' ? 'selected' : ''); ?>>
                            Benefit (Semakin baik semakin tinggi)
                        </option>
                        <option value="cost" <?php echo e(old('tipe') === 'cost' ? 'selected' : ''); ?>>
                            Cost (Semakin baik semakin rendah)
                        </option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">
                        <strong>Benefit:</strong> Kriteria di mana nilai semakin tinggi semakin baik (contoh: keuntungan)<br>
                        <strong>Cost:</strong> Kriteria di mana nilai semakin rendah semakin baik (contoh: biaya)
                    </p>
                </div>

                <div>
                    <label for="bobot" class="block text-sm font-medium text-gray-700 mb-2">
                        Bobot Kriteria
                    </label>
                    <input type="number"
                           id="bobot"
                           name="bobot"
                           value="<?php echo e(old('bobot', 0.0000)); ?>"
                           step="0.0001"
                           min="0"
                           max="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.0000"
                           required>
                    <p class="mt-1 text-sm text-gray-500">
                        Masukkan nilai bobot antara 0 hingga 1 dengan 4 desimal. Total semua bobot harus sama dengan 1.
                    </p>
                </div>

                <div class="flex justify-end space-x-4 pt-4">
                    <a href="<?php echo e(route('admin.kriteria.index')); ?>"
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-save mr-2"></i>Simpan Kriteria
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/kriteria/create.blade.php ENDPATH**/ ?>