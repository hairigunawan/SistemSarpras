<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-100 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Akun</p>
                    <p class="text-3xl text-white font-bold"><?php echo e($jumlah_akun ?? 0); ?></p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg backdrop-blur-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-100 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Total Sarpras</p>
                    <p class="text-3xl text-white font-bold"><?php echo e($jumlah_sarpras ?? 0); ?></p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg backdrop-blur-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <a href="<?php echo e(route('admin.peminjaman.index', ['status' => 'Menunggu'])); ?>" class="block group">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-100 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium mb-1">Peminjaman Menunggu</p>
                        <p class="text-3xl text-white font-bold"><?php echo e($peminjaman_menunggu ?? 0); ?></p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg backdrop-blur-sm group-hover:bg-opacity-30 transition-all">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>

        <a href="<?php echo e(route('admin.peminjaman.index', ['status' => 'disetujui'])); ?>" class="block group">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-100 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1">Peminjaman<br>Disetujui</p>
                        <p class="text-3xl text-white font-bold"><?php echo e($peminjaman_disetujui ?? 0); ?></p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg backdrop-blur-sm group-hover:bg-opacity-30 transition-all">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="bg-gradient-to-tl from-blue-600 to-blue-700 p-6 rounded-xl shadow-lg">
        <h3 class="text-lg font-medium text-white mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Aksi Cepat
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="<?php echo e(route('sarpras.ruangan.tambah_ruangan')); ?>" class="group">
                <div class="bg-white p-4 rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 group-hover:border-blue-200 group-hover:transform group-hover:scale-100">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-blue-100 p-4 rounded-full group-hover:bg-blue-200 transition-colors mb-4">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-medium">Tambah Ruangan</h3>
                    </div>
                </div>
            </a>

            <a href="<?php echo e(route('sarpras.proyektor.tambah_proyektor')); ?>" class="group">
                <div class="bg-white p-4 rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 group-hover:border-green-200 group-hover:transform group-hover:scale-100">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-blue-100 p-4 rounded-full group-hover:bg-blue-200 transition-colors mb-4">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-medium">Tambah Proyektor</h3>
                    </div>
                </div>
            </a>

            <a href="<?php echo e(route('laporan.pdf')); ?>" class="group">
                <div class="bg-white p-4 rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 group-hover:border-red-200 group-hover:transform group-hover:scale-100">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-red-100 p-4 rounded-full group-hover:bg-red-200 transition-colors mb-4">
                            <svg class="w-6 h-6 text-gray-600 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-medium">Laporan PDF</h3>
                    </div>
                </div>
            </a>

            <a href="<?php echo e(route('laporan.excel')); ?>" class="group">
                <div class="bg-white p-4 rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 group-hover:border-green-200 group-hover:transform group-hover:scale-100">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-green-100 p-4 rounded-full group-hover:bg-green-200 transition-colors mb-4">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v1a1 1 0 001 1h4a1 1 0 001-1v-1m3-2V8a2 2 0 00-2-2H8a2 2 0 00-2 2v6m9-5h-6a2 2 0 100 4h6a2 2 0 100-4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-medium">Laporan Excel</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div>
        <div x-data="{ open: false }" class="bg-white p-6 border-t border-gray-200 rounded-b-xl shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Statistik Peminjaman</h3>
                    <p class="text-sm text-gray-500"><?php echo e($periodeLabel ?? 'Data Statistik'); ?></p>
                </div>

                <div class="flex gap-2">
                <div class="flex items-center gap-3" x-data="{ open: false, currentChart: 'line' }">
                    <div class="relative">

                        <!-- Toggle Dropdown -->
                        <button
                            @click="open = !open"
                            class="px-3 py-1.5 border text-xs rounded-md bg-gray-100 flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                            <span x-text="currentChart.charAt(0).toUpperCase() + currentChart.slice(1)"></span>

                            <!-- Ikon Panah -->
                            <span class="text-gray-600 transition-transform duration-200"
                                :class="{ 'rotate-180': open }">
                                ▼
                            </span>
                        </button>

                        <!-- Dropdown Items -->
                        <div x-show="open" @click.outside="open = false"
                            class="absolute mt-1 w-32 right-0 bg-white border shadow-sm rounded-md text-xs overflow-hidden z-50">

                            <template x-for="type in ['line','bar','pie','doughnut']">
                                <div @click="currentChart = type; open = false; renderChart(type)"
                                    class="px-3 py-1.5 hover:bg-blue-100 cursor-pointer capitalize"
                                    x-text="type">
                                </div>
                            </template>

                        </div>
                    </div>
                </div>


                    <div class="relative">
                        <button @click="open = !open" class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 rounded-sm text-xs font-medium flex items-center gap-2 transition-colors">
                            <span>
                                <?php if($periode === 'minggu'): ?> Minggu
                                <?php elseif($periode === 'bulan'): ?> Bulan
                                <?php elseif($periode === 'semester'): ?> Semester
                                <?php else: ?> Pilih Periode
                                <?php endif; ?>
                            </span>
                            <span class="text-gray-600 transition-transform duration-200"
                                :class="{ 'rotate-180': open }">
                                ▼
                            </span>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-20 overflow-hidden">
                            <a href="<?php echo e(route('admin.dashboard', ['periode' => 'minggu'])); ?>" class="block px-4 py-2 text-xs hover:bg-gray-100 <?php echo e($periode === 'minggu' ? 'text-blue-500 font-semibold bg-blue-50' : 'text-gray-700'); ?>">Minggu</a>
                            <a href="<?php echo e(route('admin.dashboard', ['periode' => 'bulan'])); ?>" class="block px-4 py-2 text-xs hover:bg-gray-100 <?php echo e($periode === 'bulan' ? 'text-blue-500 font-semibold bg-blue-50' : 'text-gray-700'); ?>">Bulan</a>
                            <a href="<?php echo e(route('admin.dashboard', ['periode' => 'semester'])); ?>" class="block px-4 py-2 text-xs hover:bg-gray-100 <?php echo e($periode === 'semester' ? 'text-blue-500 font-semibold bg-blue-50' : 'text-gray-700'); ?>">Semester</a>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <div class="relative h-96 bg-gray-50 rounded-b-lg p-4 border-b mb-4 border-gray-100">
                <canvas id="peminjamantChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-sm">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Sarpras Terpopuler</h3>
                <p class="text-sm text-gray-500">Berdasarkan periode: <span class="font-medium text-gray-700"><?php echo e($periodeLabel ?? 'Semua Waktu'); ?></span></p>
            </div>

            <div class="mb-6">
                <h4 class="font-medium text-gray-700 mb-4 flex items-center gap-2">
                    Ruangan
                </h4>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $topSarpras['ruangan'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ruangan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3 w-full">
                            <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 bg-gray-500 text-white rounded-full text-sm font-bold shadow-md">
                                <?php echo e($index + 1); ?>

                            </span>
                            <div class="flex-grow">
                                <span class="text-gray-700 font-medium block"><?php echo e($ruangan['nama']); ?></span>
                                <div class="w-full bg-blue-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: <?php echo e(min(($ruangan['jumlah'] / 20) * 100, 100)); ?>%"></div>
                                </div>
                            </div>
                        </div>
                        <span class="flex-shrink-0 bg-white text-gray-800 px-3 py-1 rounded-full text-xs font-bold border border-blue-200 shadow-sm ml-2">
                            <?php echo e($ruangan['jumlah']); ?>x
                        </span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p class="text-gray-400 text-sm italic">Belum ada data</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-gray-700 mb-4 flex items-center gap-2">
                    Proyektor
                </h4>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $topSarpras['proyektor'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $proyektor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3 w-full">
                            <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 bg-gray-500 text-white rounded-full text-sm font-bold shadow-md">
                                <?php echo e($index + 1); ?>

                            </span>
                            <div class="flex-grow">
                                <span class="text-gray-700 font-medium block"><?php echo e($proyektor['nama']); ?></span>
                                <div class="w-full bg-green-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-gray-500 h-1.5 rounded-full" style="width: <?php echo e(min(($proyektor['jumlah'] / 20) * 100, 100)); ?>%"></div>
                                </div>
                            </div>
                        </div>
                        <span class="flex-shrink-0 bg-white text-green-800 px-3 py-1 rounded-full text-xs font-bold border border-green-200 shadow-sm ml-2">
                            <?php echo e($proyektor['jumlah']); ?>x
                        </span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p class="text-gray-400 text-sm italic">Belum ada data</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-sm">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Top Peminjam</h3>
                <p class="text-sm text-gray-500">3 peminjam terbanyak</p>
            </div>

            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $topPeminjam ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $peminjam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-300 border border-transparent hover:border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <span class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-gray-500 to-gray-600 text-white rounded-full text-sm font-bold shadow-md">
                                <?php echo e($index + 1); ?>

                            </span>
                            <?php if($index === 0): ?>
                            <div class="absolute -top-1 -right-1 bg-blue-400 text-white rounded-full p-0.5 shadow-sm border border-white">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800"><?php echo e($peminjam['nama']); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($peminjam['jumlah']); ?> kali meminjam</p>
                        </div>
                    </div>
                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold text-xs border border-blue-100">
                        <?php echo e($peminjam['jumlah']); ?>x
                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-sm italic">Belum ada data peminjam</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. Siapkan data di luar function agar bisa diakses global
    const rawData = <?php echo json_encode($chartData ?? [], 15, 512) ?>;

    // Mapping Data
    const labels = rawData.map(item => item.label);
    const dataRuangan = rawData.map(item => item.ruangan);
    const dataProyektor = rawData.map(item => item.proyektor);

    // Hitung total untuk Pie Chart
    const totalRuangan = dataRuangan.reduce((a, b) => a + b, 0);
    const totalProyektor = dataProyektor.reduce((a, b) => a + b, 0);

    let chartInstance = null;

    // 2. Definisikan renderChart ke window agar bisa dipanggil Alpine.js dari HTML
    window.renderChart = function(type) {
        const ctx = document.getElementById('peminjamantChart').getContext('2d');

        // Hancurkan chart lama jika ada
        if (chartInstance) {
            chartInstance.destroy();
        }

        // Konfigurasi Dataset
        let datasetsConfig = [];
        let chartLabels = labels;

        if (type === 'pie' || type === 'doughnut') {
            chartLabels = ['Ruangan', 'Proyektor'];
            datasetsConfig = [{
                data: [totalRuangan, totalProyektor],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)', // Blue
                    'rgba(168, 85, 247, 0.8)'  // green
                ],
                borderColor: '#ffffff',
                borderWidth: 2
            }];
        } else {
            datasetsConfig = [
                {
                    label: 'Ruangan',
                    data: dataRuangan,
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: type === 'line'
                },
                {
                    label: 'Proyektor',
                    data: dataProyektor,
                    backgroundColor: 'rgba(168, 85, 247, 0.5)',
                    borderColor: 'rgba(168, 85, 247, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: type === 'line'
                }
            ];
        }

        // Buat Chart Baru
        chartInstance = new Chart(ctx, {
            type: type,
            data: {
                labels: chartLabels,
                datasets: datasetsConfig
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 20 }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1f2937',
                        bodyColor: '#4b5563',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true
                    }
                },
                scales: (type === 'pie' || type === 'doughnut') ? {
                    x: { display: false },
                    y: { display: false }
                } : {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4], color: '#f3f4f6' },
                        ticks: { precision: 0 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    };

    // 3. Render chart default saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        renderChart('line');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SIMPERSITE\SistemSarpras\resources\views/admin/dashboard/index.blade.php ENDPATH**/ ?>