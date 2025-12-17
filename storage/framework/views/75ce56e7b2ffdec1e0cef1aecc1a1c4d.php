<?php $__env->startSection('title', 'Daftar Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-gray-50 text-gray-800">
  <div class="container mx-auto py-10 px-4">
    <!-- Hero -->
    <div class="text-center mb-16">
      <h2 class="text-2xl font-bold mb-2">Daftar Peminjaman Sarana dan Prasarana</h2>
      <p class="text-gray-600 text-sm mb-6">
        Berikut adalah daftar semua pengajuan peminjaman yang telah diajukan
      </p>

      <div class="flex justify-center space-x-4">
        <a href="<?php echo e(route('public.peminjaman.create')); ?>">
            <button class="bg-[#179ACE] text-white px-5 py-2 font-medium rounded-md hover:bg-[#0E7CBA]">Ajukan Peminjaman</button>
        </a>
      </div>
    </div>

    <!-- Tabel -->
    <div class="bg-white mx-20 shadow-sm rounded-lg border border-gray-200 overflow-hidden mb-16">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-blue-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">No</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Nama Peminjam</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Sarana/Prasarana</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Tanggal Peminjaman</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Jam</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Status</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php $__empty_1 = true; $__currentLoopData = $peminjaman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($index + 1); ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($item->nama_peminjam); ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <?php if($item->ruangan && $item->proyektor): ?>
                    <div class="flex items-center">
                        <div class="flex items-center">
                            <?php echo e($item->ruangan->nama_ruangan); ?>

                             -
                            <?php echo e($item->proyektor->nama_proyektor); ?>

                        </div>
                    </div>
                <?php elseif($item->ruangan): ?>
                    <div class="flex items-center">
                        <?php echo e($item->ruangan->nama_ruangan); ?>

                    </div>
                <?php elseif($item->proyektor): ?>
                    <div class="flex items-center">
                        <?php echo e($item->proyektor->nama_proyektor); ?>

                    </div>
                <?php else: ?>
                  <span class="text-gray-500">N/A</span>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e(\Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y')); ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                <?php echo e($item->jam_mulai ?? '-'); ?> - <?php echo e($item->jam_selesai ?? '-'); ?>

              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <?php if($item->status_peminjaman == 'Menunggu'): ?>
                  <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                    Menunggu
                  </span>
                <?php elseif($item->status_peminjaman == 'Disetujui'): ?>
                  <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                    Disetujui
                  </span>
                <?php elseif($item->status_peminjaman == 'Ditolak'): ?>
                  <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                    Ditolak
                  </span>
                <?php elseif($item->status_peminjaman == 'Selesai'): ?>
                  <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center">
                    Selesai
                  </span>
                <?php else: ?>
                  <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full"><?php echo e($item->status_peminjaman); ?></span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="8" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                  <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <h3 class="text-lg font-medium text-gray-500 mb-1">Tidak ada data peminjaman</h3>
                  <p class="text-gray-400">Belum ada pengajuan peminjaman yang diajukan</p>
                </div>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Jadwal Ruangan Terpakai -->
        <div class="mx-20">
            <div class="mb-12">

                <!-- Header -->
                <div class="flex items-center mb-6 gap-4">
                    <h2 class="text-xl font-semibold text-gray-800">Jadwal Ruangan Terpakai</h2>

                    <?php if(isset($p) && count($p) > 0): ?>
                        <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-[10px] font-medium px-5 py-1 rounded-xl">
                            <?php echo e(count($p)); ?> Ruangan Terpakai
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Tabel -->
                <?php if(isset($p) && count($p) > 0): ?>
                    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Ruangan</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Kelas</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Mata Kuliah</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Waktu</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                <?php $__currentLoopData = $p; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-medium text-gray-800"><?php echo e($k['nama']); ?></td>
                                        <td class="px-6 py-4 text-gray-700"><?php echo e($k['kelas']); ?></td>
                                        <td class="px-6 py-4 text-gray-700"><?php echo e($k['matkul']); ?></td>
                                        <td class="px-6 py-4 text-gray-700"><?php echo e($k['waktu']); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                <!-- Jika tidak ada data -->
                <?php else: ?>
                    <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-600 mb-2">Tidak ada ruangan terpakai</h3>
                        <p class="text-gray-400 text-sm">Semua ruangan saat ini tersedia untuk digunakan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/public/peminjaman/index.blade.php ENDPATH**/ ?>