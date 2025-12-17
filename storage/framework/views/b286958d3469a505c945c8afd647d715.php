<?php $__env->startSection('title', 'Detail Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header Section: Judul & Tombol Kembali -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('admin.peminjaman.index')); ?>" class="p-2 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Peminjaman</h1>
                    <p class="text-sm text-gray-500">
                         &bull; Diajukan pada <?php echo e(\Carbon\Carbon::parse($mainPeminjaman->created_at)->format('d M Y')); ?>

                    </p>
                </div>
            </div>

            <a href="<?php echo e(route('admin.peminjaman.index')); ?>"
               class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-gray-50">
                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m0 0l6-6m-6 6l6 6"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <!-- Alert Pesan -->
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
        <?php if(session('warning')): ?>
            <div class="mb-6 rounded-md bg-yellow-50 p-4 border-l-4 border-yellow-400 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700"><?php echo e(session('warning')); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="mb-6 rounded-md bg-red-50 p-4 border-l-4 border-red-400 shadow-sm">
                <div class="ml-3">
                    <ul class="list-disc pl-5 text-sm text-red-700">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

            <!-- Card Header: Status & Actions -->
            <div class="px-6 py-5 border-b border-gray-200 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <!-- Status Badge -->
                <div class="flex items-center gap-3">
                    <span class="text-gray-500 text-sm">Status:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        <?php if($mainPeminjaman->status_peminjaman == 'Menunggu'): ?> bg-yellow-100 text-yellow-800
                        <?php elseif($mainPeminjaman->status_peminjaman == 'Disetujui'): ?> bg-green-100 text-green-800
                        <?php elseif($mainPeminjaman->status_peminjaman == 'Selesai'): ?> bg-blue-100 text-blue-800
                        <?php elseif($mainPeminjaman->status_peminjaman == 'Ditolak'): ?> bg-red-100 text-red-800
                        <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                        <?php echo e($mainPeminjaman->status_peminjaman == 'Menunggu' ? 'Menunggu Konfirmasi' : $mainPeminjaman->status_peminjaman); ?>

                    </span>

                    <?php if($mainPeminjaman->status_peminjaman == 'Ditolak'): ?>
                        <span class="text-xs text-red-600 italic">(Alasan: <?php echo e($mainPeminjaman->alasan_penolakan); ?>)</span>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-2">
                    <?php if($mainPeminjaman->status_peminjaman == 'Menunggu'): ?>
                        <form action="<?php echo e(route('peminjaman.approve', $mainPeminjaman->id_peminjaman)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini?')"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Setujui
                            </button>
                        </form>

                        <button type="button" onclick="openRejectModal('<?php echo e($mainPeminjaman->id_peminjaman); ?>')"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 ">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Tolak
                        </button>
                    <?php elseif($mainPeminjaman->status_peminjaman == 'Disetujui'): ?>
                        <form action="<?php echo e(route('peminjaman.complete', $mainPeminjaman->id_peminjaman)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan peminjaman ini?')"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 ">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Selesaikan
                            </button>
                        </form>
                    <?php endif; ?>

                    <!-- WA Button -->
                    <a href="https://wa.me/<?php echo e($mainPeminjaman->user->nomor_telepon ?? '-'); ?>" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>

            <!-- Informasi Peminjaman -->
            <div class="border-b border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">

                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Detail</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Rincian lengkap mengenai pengajuan peminjaman.</p>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Nama Peminjam</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">
                            <?php echo e($mainPeminjaman->nama_peminjam ?? ($mainPeminjaman->user->name ?? 'N/A')); ?>

                            <span class="text-gray-500 font-normal ml-2">(<?php echo e($mainPeminjaman->user->email ?? '-'); ?>)</span>
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Jenis Kegiatan / Keterangan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($mainPeminjaman->jenis_kegiatan); ?></dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Sarpras yang Dipinjam</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php if($mainPeminjaman->ruangan && $mainPeminjaman->proyektor): ?>
                                <span class="inline-flex items-center py-0.5 rounded-md text-sm font-medium">
                                    <?php echo e($mainPeminjaman->ruangan->nama_ruangan); ?>

                                </span>
                                <span class="text-gray-400">+</span>
                                <span class="inline-flex items-center py-0.5 rounded-md text-sm font-medium">
                                    <?php echo e($mainPeminjaman->proyektor->nama_proyektor); ?>

                                </span>
                            <?php elseif($mainPeminjaman->ruangan): ?>
                                <span class="inline-flex items-center py-0.5 rounded-md text-sm font-medium">
                                    <?php echo e($mainPeminjaman->ruangan->nama_ruangan); ?>

                                </span>
                            <?php elseif($mainPeminjaman->proyektor): ?>
                                <span class="inline-flex items-center py-0.5 rounded-md text-sm font-medium">
                                    <?php echo e($mainPeminjaman->proyektor->nama_proyektor); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-gray-500 italic">Tidak ada fasilitas spesifik</span>
                            <?php endif; ?>
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                        <?php if($mainPeminjaman->proyektor && $mainPeminjaman->ruangan): ?>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span><?php echo e($mainPeminjaman->ruangan->nama_ruangan); ?></span>
                                -
                                <span><?php echo e($mainPeminjaman->ruangan->lokasi->nama_lokasi); ?></span>
                            </dd>
                        <?php elseif($mainPeminjaman->proyektor): ?>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span><?php echo e($mainPeminjaman->ruangan->nama_ruangan); ?></span>
                                -
                                <span><?php echo e($mainPeminjaman->ruangan->lokasi->nama_lokasi); ?></span>
                            </dd>
                        <?php else: ?>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <span><?php echo e($mainPeminjaman->ruangan->lokasi->nama_lokasi); ?></span>
                            </dd>
                        <?php endif; ?>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Jadwal Peminjaman</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex flex-col sm:flex-row sm:gap-8">
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide">Mulai</span>
                                    <span class="font-medium"><?php echo e(\Carbon\Carbon::parse($mainPeminjaman->tanggal_pinjam)->translatedFormat('d F Y')); ?></span>
                                    <span class="text-gray-600">Pukul <?php echo e(date('H:i', strtotime($mainPeminjaman->jam_mulai))); ?></span>
                                </div>
                                <div class="hidden sm:block border-l border-gray-300"></div>
                                <div>
                                    <span class="block text-xs text-gray-500 uppercase tracking-wide">Selesai</span>
                                    <span class="font-medium"><?php echo e(\Carbon\Carbon::parse($mainPeminjaman->tanggal_pinjam)->translatedFormat('d F Y')); ?></span>
                                    <span class="text-gray-600">Pukul <?php echo e(date('H:i', strtotime($mainPeminjaman->jam_selesai))); ?></span>
                                </div>
                            </div>
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Kontak (WhatsApp)</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($mainPeminjaman->user->nomor_telepon ?? '-'); ?></dd>
                    </div>

                    <?php if($mainPeminjaman->status_peminjaman == 'Disetujui' || $mainPeminjaman->status_peminjaman == 'Selesai'): ?>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                            <dt class="text-sm font-medium text-gray-500">Catatan Admin</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <?php if($mainPeminjaman->catatan_admin): ?>
                                    <?php echo e($mainPeminjaman->catatan_admin); ?>

                                <?php else: ?>
                                    <span class="text-gray-500 italic">Tidak ada catatan</span>
                                <?php endif; ?>
                            </dd>
                        </div>
                    <?php endif; ?>

                </dl>
            </div>
        </div>

        <?php if($mainPeminjaman->status_peminjaman == 'Disetujui'): ?>
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Berikan Catatan Admin</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Tambahkan catatan mengenai kondisi peminjaman atau hal lain yang relevan.</p>
                </div>
                <form action="<?php echo e(route('peminjaman.add_catatan_admin', $mainPeminjaman->id_peminjaman)); ?>" method="POST" class="p-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <div class="mb-4">
                        <label for="catatan_admin" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="catatan_admin" id="catatan_admin" rows="4"
                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-3"
                            placeholder="Misal: Proyektor berfungsi baik, ada sedikit goresan pada casing, dll."><?php echo e(old('catatan_admin', $mainPeminjaman->catatan_admin)); ?></textarea>
                        <?php $__errorArgs = ['catatan_admin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900">
                            Simpan Catatan
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- Konflik / Kandidat Lain (Jika Ada) -->
        <?php if(!empty($candidates ?? []) && count($candidates) > 1): ?>
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-orange-200">
                <div class="px-6 py-5 border-b border-orange-100 bg-orange-50 flex items-center">
                    <div class="flex-shrink-0 bg-orange-100 rounded-full p-2">
                        <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-orange-900">Terdeteksi Konflik Jadwal</h3>
                        <p class="text-sm text-orange-700">Menyetujui peminjaman ini akan otomatis menolak peminjaman lain di bawah ini.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $candidates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $candidate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e($candidate->id_peminjaman == $mainPeminjaman->id_peminjaman ? 'bg-blue-50' : 'hover:bg-gray-50'); ?>">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?php echo e($candidate->nama_peminjam ?? $candidate->user->name); ?>

                                        <?php if($candidate->id_peminjaman == $mainPeminjaman->id_peminjaman): ?>
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Sedang Dilihat</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($candidate->ruangan->nama_ruangan ?? ''); ?> <?php echo e($candidate->proyektor ? '& ' . $candidate->proyektor->nama_proyektor : ''); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e(\Carbon\Carbon::parse($candidate->tanggal_pinjam)->format('d/m/Y')); ?> <br>
                                        <?php echo e($candidate->jam_mulai); ?> - <?php echo e($candidate->jam_selesai); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            <?php echo e($candidate->status_peminjaman == 'Menunggu' ? 'bg-yellow-100 text-yellow-800' :
                                               ($candidate->status_peminjaman == 'Disetujui' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')); ?>">
                                            <?php echo e($candidate->status_peminjaman); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php if($candidate->id_peminjaman != $mainPeminjaman->id_peminjaman): ?>
                                            <a href="<?php echo e(route('admin.peminjaman.lihat_peminjaman', $candidate->id_peminjaman)); ?>" class="text-blue-600 hover:text-blue-900">Lihat Detail</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- MODAL TOLAK (Hidden by default) -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="rejectForm" action="#" method="POST" data-action-template="<?php echo e(route('peminjaman.reject', ['id' => ':peminjaman_id'])); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tolak Peminjaman</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    Silakan masukkan alasan penolakan untuk memberitahu peminjam.
                                </p>
                                <textarea name="alasan_penolakan" rows="4"
                                    class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md p-3"
                                    placeholder="Tuliskan alasan penolakan di sini..."
                                    required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tolak Sekarang
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Simple vanilla JS for Modal
    const rejectModal = document.getElementById('rejectModal');
    const rejectForm = document.getElementById('rejectForm');

    function openRejectModal(id) {
        const template = rejectForm.getAttribute('data-action-template');
        rejectForm.action = template.replace(':peminjaman_id', id);
        rejectModal.classList.remove('hidden');
    }

    function closeModal() {
        rejectModal.classList.add('hidden');
        rejectForm.reset();
    }

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/peminjaman/lihat_peminjaman.blade.php ENDPATH**/ ?>