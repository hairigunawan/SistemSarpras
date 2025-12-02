<?php $__env->startSection('title', 'Sarpras - Sistem Informasi Sarana dan Prasarana'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-gray-50 min-h-screen font-sans">

    <!-- Hero Section -->
    <div class="relative w-full h-[500px] md:h-[600px] overflow-hidden">
        <!-- Background Image with Parallax Effect -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transform scale-105"
             style="background-image: url('<?php echo e(asset('storage/images/GKT.jpg')); ?>');">
        </div>

        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-gray-900/90"></div>

        <!-- Content -->
        <div class="relative h-full flex flex-col justify-center items-center text-center text-white px-4 max-w-5xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight tracking-tight">
                Sistem Informasi <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">Sarana dan Prasarana</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 mb-10 max-w-2xl leading-relaxed">
                Solusi digital terintegrasi untuk pengelolaan dan peminjaman fasilitas Jurusan Teknologi Informasi yang lebih transparan, cepat, dan efisien.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                <a href="#jadwal" class="px-8 py-2.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-all shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Lihat Jadwal
                </a>
                <a href="#informasi" class="px-8 py-2.5 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/30 font-semibold transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Panduan
                </a>
            </div>
        </div>

        <!-- Wave Divider Bottom -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="fill-gray-50 w-full h-12 md:h-24" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="container mx-auto px-4 md:px-6 py-12 -mt-12 relative z-10">

        <!-- Top Section: Stats & Info -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">

            <!-- Left Side: Calendar Widget (7 cols) -->
            <div class="lg:col-span-7" id="jadwal">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
                    <!-- Header Kalender -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white leading-none">Kalender Peminjaman</h3>
                                <p class="text-blue-100 text-sm mt-1">Cek ketersediaan ruangan & alat</p>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar Body -->
                    <div class="calendar-container md:p-2">
                        <div id="calendar" class="modern-compact-calendar w-full h-full min-h-[300px]"></div>
                    </div>

                    <!-- Legend -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex flex-wrap items-center justify-center gap-6">
                            <div class="flex items-center gap-2">
                                <span class="flex h-3 w-3 rounded-full bg-blue-500 shadow-sm ring-2 ring-blue-200"></span>
                                <span class="text-sm text-gray-600 font-medium">Terpakai / Booking</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="flex h-3 w-3 rounded-full bg-white border-2 border-blue-400"></span>
                                <span class="text-sm text-gray-600 font-medium">Hari Ini</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Jurusan Info & Quick Stats (5 cols) -->
            <div class="lg:col-span-5 flex flex-col gap-6">

                <!-- Card Jurusan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group transition-all duration-300">
                    <div class="relative h-32 bg-gradient-to-r from-slate-800 to-slate-900 overflow-hidden">
                        <!-- Decorative Pattern -->
                        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>

                        <div class="relative z-10 p-6 flex flex-col justify-center h-full">
                            <h2 class="text-2xl font-bold text-white mb-1 group-hover:text-blue-300 transition-colors">Teknologi Informasi</h2>
                            <p class="text-slate-300 text-sm">Politeknik Negeri Tanah Laut</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start gap-3 text-gray-600 text-sm">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="leading-relaxed">
                                Jl. A. Yani Km. 06, Desa Panggung, Kec. Pelaihari, Kab. Tanah Laut, Kalimantan Selatan 70815
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Stat 1 -->
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-blue-200 transition-all group">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Ruangan</p>
                        </div>
                        <h4 class="text-3xl font-bold text-gray-800"><?php echo e($totalRuangan); ?></h4>
                    </div>

                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-purple-200 transition-all group">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ruangan Terpakai</p>
                        </div>
                        <h4 class="text-3xl font-bold text-gray-800"><?php echo e($RuanganTerpakai); ?></h4>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Stat 1 -->
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-blue-200 transition-all group">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Proyektor</p>
                        </div>
                        <h4 class="text-3xl font-bold text-gray-800"><?php echo e($totalProyektor); ?></h4>
                    </div>

                    <!-- Stat 2 -->
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-purple-200 transition-all group">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Proyektor Terpakai</p>
                        </div>
                        <h4 class="text-3xl font-bold text-gray-800"><?php echo e($ProyektorTerpakai); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Section -->
        <div class="mb-12" id="informasi">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-800">Informasi Penting</h2>
                <div class="h-1 w-20 bg-blue-600 mx-auto mt-3 rounded-full"></div>
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto">Panduan singkat mengenai sistem dan tata cara peminjaman fasilitas.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-2xl hover:shadow-sm transition-all duration-300 border border-gray-100 group">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition-colors duration-300">
                        <svg class="w-7 h-7 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-3">Apa itu SIMPERSITE?</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Platform digital terintegrasi untuk pengelolaan peminjaman ruangan dan proyektor. Membantu civitas dalam mendata ketersediaan sarana dan memastikan peminjaman dilakukan secara transparan dan tertib.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-2xl hover:shadow-sm transition-all duration-300 border border-gray-100 group">
                    <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-600 transition-colors duration-300">
                        <svg class="w-7 h-7 text-green-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-3">Cara Meminjam</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Login ke sistem, pilih jenis sarpras (ruangan/proyektor), cek ketersediaan pada kalender, isi formulir peminjaman dengan detail kegiatan, dan tunggu persetujuan dari admin.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-2xl hover:shadow-sm transition-all duration-300 border border-gray-100 group">
                    <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-purple-600 transition-colors duration-300">
                        <svg class="w-7 h-7 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-3">Fitur Lengkap</h4>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Pencatatan jadwal real-time, notifikasi status via WhatsApp (opsional), manajemen lokasi, riwayat peminjaman lengkap, dan sistem umpan balik untuk peningkatan layanan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Pastikan route API ini ada di web.php atau api.php
        window.approvedDatesApiUrl = "<?php echo e(route('api.peminjaman.approvedDates', ['type' => 'all', 'idSarpras' => 'all'])); ?>";
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/calender.js']); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SistemSarpras\resources\views/public/beranda/index.blade.php ENDPATH**/ ?>