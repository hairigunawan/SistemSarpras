<?php $__env->startSection('title', 'Sarana'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto max-w-7xl">
    <!-- Header Card -->
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Judul -->
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Sarana & Prasarana</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola ruangan dan proyektor</p>
                </div>

                <!-- Aksi (Cari + Filter + Tambah) -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" placeholder="Cari sarpras..."
                            class="w-full sm:w-64 pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm
                                   focus:ring-1 focus:ring-[#8bc9e2] focus:border-transparent focus:outline-none
                                   transition-all duration-200" />
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                        </svg>
                    </div>

                    <!-- Filter Status -->
                    <div class="relative">
                        <select id="status-filter" class="w-full sm:w-40 pl-3 pr-10 py-2.5 border border-gray-200 rounded-lg text-sm
                                   focus:ring-1 focus:ring-[#8bc9e2] focus:border-transparent focus:outline-none
                                   transition-all duration-200 appearance-none bg-white">
                            <option value="">Semua Status</option>
                            <?php $__currentLoopData = $s; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status->nama_status); ?>"><?php echo e($status->nama_status); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400 pointer-events-none" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <!-- Tombol Tambah Sarpras -->
                    <button id="btn-tambah-sarpras"
                        class="bg-[#179ACE] hover:bg-[#0F6A8F] text-white px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-normal transition flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Sarpras
                    </button>
                </div>
            </div>
        </div>

        <!-- GRID SARPRAS -->
        <!-- GRID SARPRAS -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 p-4">

            <!-- Menampilkan data ruangan -->
            <?php if(isset($r)): ?>
                <?php $__currentLoopData = $r; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-xl border border-gray-500 hover:shadow-sm transition overflow-hidden">

                        <!-- Gambar stabil -->
                        <div class="w-full aspect-[4/3] overflow-hidden">
                            <?php if($item->gambar): ?>
                                <img src="<?php echo e(asset('storage/' . str_replace('public/', '', $item->gambar))); ?>"
                                    alt="<?php echo e($item->nama_ruangan); ?>"
                                    class="w-full h-full object-cover">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/400x250?text=Tidak+Ada+Gambar"
                                    alt="Tidak Ada Gambar"
                                    class="w-full h-full object-cover">
                            <?php endif; ?>
                        </div>

                        <div class="p-3">
                            <h2 class="text-gray-800 font-semibold"><?php echo e($item->nama_ruangan); ?></h2>
                            <p class="text-xs font-medium text-gray-500 mb-3"><?php echo e($item->lokasi->nama_lokasi ?? '-'); ?></p>

                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm font-medium
                                    <?php echo e($item->status->nama_status == 'Tersedia' ? 'text-green-600' :
                                    ($item->status->nama_status == 'Dipakai' ? 'text-yellow-600' :
                                    ($item->status->nama_status == 'Diperbaiki' ? 'text-orange-600' : 'text-red-600'))); ?>">
                                    <?php echo e($item->status->nama_status); ?>

                                </span>
                                <span class="text-sm text-gray-500">Ruangan</span>
                            </div>

                            <a href="<?php echo e(route('sarpras.ruangan.lihat_ruangan', $item->id_ruangan)); ?>"
                            class="block text-center bg-[#66bfe2] hover:bg-[#179ACE] text-white py-1.5 border border-gray-200 rounded-lg text-xs font-normal transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <!-- Menampilkan data proyektor -->
            <?php if(isset($p)): ?>
                <?php $__currentLoopData = $p; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-xl border border-gray-500 hover:shadow-sm transition overflow-hidden">

                        <!-- Gambar stabil -->
                        <div class="w-full aspect-[4/3] overflow-hidden">
                            <?php if($item->gambar): ?>
                                <img src="<?php echo e(asset('storage/' . str_replace('public/', '', $item->gambar))); ?>"
                                    alt="<?php echo e($item->nama_proyektor); ?>"
                                    class="w-full h-full object-cover border-b-2 border-gray-200">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/400x250?text=Tidak+Ada+Gambar"
                                    alt="Tidak Ada Gambar"
                                    class="w-full h-full object-cover">
                            <?php endif; ?>
                        </div>

                        <div class="p-3">
                            <h2 class="text-gray-800 font-semibold"><?php echo e($item->nama_proyektor); ?></h2>
                            <p class="text-xs font-medium text-gray-500 mb-3"><?php echo e($item->merk ?? '-'); ?></p>

                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm font-medium
                                    <?php echo e($item->status->nama_status == 'Tersedia' ? 'text-green-600' :
                                    ($item->status->nama_status == 'Dipakai' ? 'text-yellow-600' :
                                    ($item->status->nama_status == 'Diperbaiki' ? 'text-orange-600' : 'text-red-600'))); ?>">
                                    <?php echo e($item->status->nama_status); ?>

                                </span>
                                <span class="text-sm text-gray-500">Proyektor</span>
                            </div>

                            <a href="<?php echo e(route('sarpras.proyektor.lihat_proyektor', $item->id_proyektor)); ?>"
                            class="block text-center bg-[#66bfe2] hover:bg-[#179ACE] text-white py-1.5 border border-gray-200 rounded-lg text-xs font-normal transition">
                                Lihat Detail
                            </a>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Modal Tambah Sarpras -->
<div id="modal-tambah-sarpras"
    class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-80 text-center">
        <h2 class="text-lg font-bold text-gray-800 mb-2">Tambah Sarpras</h2>
        <p class="text-sm text-gray-500 mb-5">
            Pilih jenis sarana & prasarana yang ingin ditambahkan:
        </p>

        <div class="flex flex-col gap-3">
            <a href="<?php echo e(route('sarpras.ruangan.tambah_ruangan')); ?>"
                class="bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition">
                Tambah Ruangan
            </a>
            <a href="<?php echo e(route('sarpras.proyektor.tambah_proyektor')); ?>"
                class="text-white py-2 rounded-lg bg-green-500 hover:bg-green-600 transition border border-gray-300">
                Tambah Proyektor
            </a>
        </div>

        <button id="btn-tutup-modal"
            class="mt-5 text-gray-500 hover:text-gray-700 px-4 py-1.5 text-sm font-medium">
            Batal
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modal-tambah-sarpras');
    const openBtn = document.getElementById('btn-tambah-sarpras');
    const closeBtn = document.getElementById('btn-tutup-modal');

    const searchInput = document.querySelector('input[placeholder="Cari sarpras..."]');
    const statusFilter = document.getElementById('status-filter');

    // Handle modal
    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const searchTerm = this.value.trim();
            window.location.href = '/admin/sarpras?search=' + encodeURIComponent(searchTerm);
        }
    });

    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = this.value.trim();
            const url = new URL(window.location);
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }, 500);
    });

    statusFilter.addEventListener('change', function() {
        const selectedStatus = this.value;
        const url = new URL(window.location);
        if (selectedStatus) {
            url.searchParams.set('nama_status', selectedStatus);
        } else {
            url.searchParams.delete('nama_status');
        }
        window.location.href = url.toString();
    });

    // Set nilai filter status dari URL saat halaman dimuat
    const urlParams = new URLSearchParams(window.location.search);
    const statusParam = urlParams.get('nama_status');
    if (statusParam) {
        statusFilter.value = statusParam;
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/sarpras/index.blade.php ENDPATH**/ ?>