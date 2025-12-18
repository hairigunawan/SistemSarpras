<?php $__env->startSection('title', 'Edit Jadwal'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto my-10">
    
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Jadwal Kuliah</h2>
            <p class="text-sm text-gray-500">Perbarui informasi jadwal perkuliahan di bawah ini.</p>
        </div>
        <a href="<?php echo e(route('admin.jadwal.index')); ?>" class="text-sm text-gray-600 hover:text-blue-600 flex items-center gap-1 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    
    <div class="bg-white border border-gray-200 shadow-xs rounded overflow-hidden">
        <form action="<?php echo e(route('admin.jadwal.update', $j)); ?>" method="POST" class="p-8 space-y-8">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4 border-b pb-2">Informasi Kelas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Kode Mata Kuliah</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                            </div>
                            <input type="text" name="kode_mk" value="<?php echo e(old('kode_mk', $j->kode_mk)); ?>"
                                class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xs focus:ring-blue-500 focus:border-blue-500 block transition-all" required placeholder="Contoh: IF210">
                        </div>
                    </div>

                    
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Nama Kelas</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <input type="text" name="nama_kelas" value="<?php echo e(old('nama_kelas', $j->nama_kelas)); ?>"
                                class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xs focus:ring-blue-500 focus:border-blue-500 block transition-all" required placeholder="Contoh: Pemrograman Web">
                        </div>
                    </div>

                    
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Kelas Mahasiswa</label>
                        <input type="text" name="kelas_mahasiswa" value="<?php echo e(old('kelas_mahasiswa', $j->kelas_mahasiswa)); ?>"
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xs focus:ring-blue-500 focus:border-blue-500 block transition-all" required placeholder="A / B / C">
                    </div>

                    
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Sebaran (Semester)</label>
                        <input type="number" name="sebaran_mahasiswa" value="<?php echo e(old('sebaran_mahasiswa', $j->sebaran_mahasiswa)); ?>"
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xs focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    </div>
                </div>
            </div>

            
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4 border-b pb-2">Waktu & Tempat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    
                    <div x-data="{ selectedHari: '<?php echo e(old('hari', $j->hari)); ?>' }" class="col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Pilih Hari</label>
                        
                        
                        <input type="hidden" name="hari" x-model="selectedHari">

                        
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                            <?php $__currentLoopData = ['Senin','Selasa','Rabu','Kamis','Jumat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" 
                                    @click="selectedHari = '<?php echo e($day); ?>'"
                                    :class="selectedHari === '<?php echo e($day); ?>' 
                                        ? 'bg-blue-300 text-white shadow-md ring-blue-500 ring-1 border-transparent' 
                                        : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50 hover:border-gray-300'"
                                    class="py-2 px-2 text-sm font-medium border rounded-xs transition-all duration-200 focus:outline-none text-center truncate">
                                    <?php echo e($day); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Ruangan</label>
                        <div class="relative">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <select name="ruangan"
                                class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xs focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                                <option value="" disabled>Pilih Ruangan</option>
                                <?php $__currentLoopData = $ruangans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ruangan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($ruangan->nama_ruangan); ?>" <?php echo e(old('ruangan', $j->ruangan) == $ruangan->nama_ruangan ? 'selected' : ''); ?>>
                                        <?php echo e($ruangan->nama_ruangan); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Jam Mulai</label>
                        <input type="time" name="jam_mulai" value="<?php echo e(old('jam_mulai', $j->jam_mulai)); ?>"
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xs focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Jam Selesai</label>
                        <input type="time" name="jam_selesai" value="<?php echo e(old('jam_selesai', $j->jam_selesai)); ?>"
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xs focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Daya Tampung</label>
                        <input type="number" name="daya_tampung" value="<?php echo e(old('daya_tampung', $j->daya_tampung)); ?>"
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xs focus:ring-blue-500 focus:border-blue-500 block transition-all" required>
                    </div>
                </div>
            </div>

            
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="<?php echo e(route('admin.jadwal.index')); ?>"
                   class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xs hover:bg-gray-50 focus:ring-1 focus:outline-none focus:ring-gray-200 transition-all">
                   Batal
                </a>
                <button type="submit"
                        class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-xs hover:bg-blue-700 focus:ring-1 focus:outline-none focus:ring-blue-300 shadow-lg shadow-blue-500/30 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/jadwal/edit.blade.php ENDPATH**/ ?>