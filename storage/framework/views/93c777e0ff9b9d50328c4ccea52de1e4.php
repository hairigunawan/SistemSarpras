<?php $__env->startSection('title', 'Detail Akun'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header Section: Judul & Tombol Kembali -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('admin.akun.index')); ?>" class="p-2 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Akun</h1>
                    <p class="text-sm text-gray-500">Informasi lengkap pengguna <?php echo e($u->nama); ?></p>
                </div>
            </div>

            <a href="<?php echo e(route('admin.akun.index')); ?>"
               class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-gray-50 active:bg-gray-100">
                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m0 0l6-6m-6 6l6 6"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <!-- Flash Messages -->
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

        <!-- Main Card Content -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

            <!-- Card Header: Title & Actions -->
            <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Pengguna</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Detail data dan hak akses pengguna.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?php echo e(route('admin.akun.edit_akun', $u->id_akun)); ?>"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>

                    <button
                        type="button"
                        onclick="openModal('<?php echo e($u->id_akun); ?>')"
                        class="inline-flex items-center gap-1 rounded-md border border-red-300 bg-white px-6 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Hapus
                    </button>

                    <!-- Modal Konfirmasi -->
                    <div id="modal-<?php echo e($u->id_akun); ?>"
                        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm text-center">
                            <h2 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Hapus</h2>
                            <p class="text-sm text-gray-600 mb-4">
                                Apakah Anda yakin ingin menghapus akun <b><?php echo e($u->nama); ?></b>?
                            </p>

                            <form action="<?php echo e(route('admin.akun.hapus_akun', $u->id_akun)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>

                                <div class="flex justify-center gap-3 mt-4">
                                    <button type="button"
                                            onclick="closeModal('<?php echo e($u->id_akun); ?>')"
                                            class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700">
                                        Batal
                                    </button>

                                    <button type="submit"
                                            class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">
                                        Hapus
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data List -->
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">

                    <!-- Nama -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold"><?php echo e($u->nama); ?></dd>
                    </div>

                    <!-- Email -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Alamat Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 flex items-center">
                            <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <?php echo e($u->email); ?>

                        </dd>
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nomor Telepon (WhatsApp)</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 flex items-center">
                            <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <?php echo e($u->nomor_telepon ?? '-'); ?>

                        </dd>
                    </div>

                    <!-- Role -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Role / Jabatan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="inline-flex items-center text-sm font-medium">
                                <?php echo e($u->userRole->nama_role ?? 'Tidak ada role'); ?>

                            </span>
                        </dd>
                    </div>

                    <!-- Tanggal Dibuat -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Registrasi</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php echo e($u->created_at ? $u->created_at->format('d M Y, H:i') . ' WITA' : '-'); ?>

                        </dd>
                    </div>

                    <!-- Tanggal Diupdate -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php echo e($u->updated_at ? $u->updated_at->diffForHumans() : '-'); ?>

                            <span class="text-gray-400 text-sm ml-1">(<?php echo e($u->updated_at ? $u->updated_at->format('d/m/Y H:i') : ''); ?>)</span>
                        </dd>
                    </div>

                </dl>
            </div>
        </div>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/akun/lihat_akun.blade.php ENDPATH**/ ?>