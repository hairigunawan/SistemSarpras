<?php $__env->startSection('title', 'Edit Jadwal'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl p-8 mx-auto my-8 bg-white border border-gray-100 shadow-md rounded-xl">
    <h2 class="mb-6 text-xl font-semibold tracking-tight text-gray-900">✏️ Edit Jadwal</h2>

    <form action="<?php echo e(route('admin.jadwal.update', $j)); ?>" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Kode Mata Kuliah</label>
            <input type="text"
                   name="kode_mk"
                   value="<?php echo e(old('kode_mk', $j->kode_mk)); ?>"
                   placeholder="Masukkan kode mata kuliah"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                   required>
        </div>

        
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Nama Kelas</label>
            <input type="text"
                   name="nama_kelas"
                   value="<?php echo e(old('nama_kelas', $j->nama_kelas)); ?>"
                   placeholder="Masukkan nama kelas"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                   required>
        </div>

        
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Kelas Mahasiswa</label>
            <input type="text"
                   name="kelas_mahasiswa"
                   value="<?php echo e(old('kelas_mahasiswa', $j->kelas_mahasiswa)); ?>"
                   placeholder="Masukkan kelas mahasiswa"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                   required>
        </div>

        
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Sebaran Mahasiswa</label>
            <input type="number"
                   name="sebaran_mahasiswa"
                   value="<?php echo e(old('sebaran_mahasiswa', $j->sebaran_mahasiswa)); ?>"
                   placeholder="Jumlah sebaran"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                   required>
        </div>

        
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Hari</label>
            <select name="hari"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-700"
                    required>
                <option value="" class="text-gray-400">-- Pilih Hari --</option>
                <?php $__currentLoopData = ['Senin','Selasa','Rabu','Kamis','Jumat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($hari); ?>" <?php echo e(old('hari', $j->hari) == $hari ? 'selected' : ''); ?>><?php echo e($hari); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-600">Jam Mulai</label>
                <input type="time"
                       name="jam_mulai"
                       value="<?php echo e(old('jam_mulai', $j->jam_mulai)); ?>"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                       required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-600">Jam Selesai</label>
                <input type="time"
                       name="jam_selesai"
                       value="<?php echo e(old('jam_selesai', $j->jam_selesai)); ?>"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                       required>
            </div>
        </div>

        
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Ruangan</label>
            <input type="text"
                   name="ruangan"
                   value="<?php echo e(old('ruangan', $j->ruangan)); ?>"
                   placeholder="Nama ruangan"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                   required>
        </div>

        
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-600">Daya Tampung</label>
            <input type="number"
                   name="daya_tampung"
                   value="<?php echo e(old('daya_tampung', $j->daya_tampung)); ?>"
                   placeholder="Jumlah daya tampung"
                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition text-gray-800 bg-gray-50"
                   required>
        </div>

        
        <div class="flex items-center justify-between mt-10">
            <a href="<?php echo e(route('admin.jadwal.index')); ?>"
               class="px-6 py-2 text-gray-500 transition bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200">Batal</a>
            <button type="submit"
                class="px-7 py-2.5 bg-blue-600 text-white rounded-lg font-semibold shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-200 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/jadwal/edit.blade.php ENDPATH**/ ?>