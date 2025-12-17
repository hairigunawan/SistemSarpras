@extends('layouts.guest')

@section('title', 'Tentang Kami')

@section('content')
    <style>
        .fade-in-up { animation: fadeInUp 0.8s ease-out forwards; opacity: 0; transform: translateY(20px); }
        .delay-200 { animation-delay: 0.2s; }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    </style>

    <div class="font-jakarta bg-white text-gray-900 overflow-hidden">

        <!-- Hero Section -->
        <div class="relative pt-12 pb-20 sm:pt-24 sm:pb-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:max-w-none fade-in-up">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-16 gap-y-10 items-center">
                        <!-- Abstract Image Composition -->
                        <div class="relative lg:pl-20 fade-in-up delay-200">
                            <div class="aspect-[4/3] bg-gray-50 rounded-2xl overflow-hidden shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500 border border-gray-100">
                                <img src="{{ asset('storage/images/tentang_kami.jpeg') }}" alt="Kolaborasi Tim" class="w-full h-full object-cover opacity-90 grayscale hover:grayscale-0 transition-all duration-500">
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2 mb-6">
                                <span class="h-px w-8 bg-[#179ACE]"></span>
                                <span class="text-[#179ACE] font-semibold text-sm uppercase tracking-widest">Tentang Kami</span>
                            </div>
                            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl mb-6">
                                Memodernisasi <br>
                                <span class="text-gray-400">Peminjaman Kampus.</span>
                            </h1>
                            <p class="text-lg leading-8 text-gray-600">
                                <span class="text-[#179ACE]">SIMPERSITE</span> adalah terobosan digital dari Jurusan Teknologi Informasi Politeknik Negeri Tanah Laut untuk efisiensi pengelolaan sarana dan prasarana.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 1: Apa Itu (Clean Layout) -->
        <div class="mx-auto max-w-7xl px-6 lg:px-8 py-24 border-t border-gray-100">
            <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-none">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <div class="lg:col-span-4 pl-16">
                        <div class="grid gap-y-3">
                            <span class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Apa Itu</span>
                            <div class="flex mb-4">
                                <span class="text-[#179ACE] text-3xl font-bold tracking-tight sm:text-4xl">SIMPERSITE</span>
                                <span class="text-gray-600 text-3xl font-bold tracking-tight sm:text-4xl">?</span>
                            </div>
                        </div>
                        <div class="h-1 w-20 bg-[#179ACE] rounded-full"></div>
                    </div>
                    <div class="lg:col-span-8">
                        <div class="text-lg leading-8 text-gray-600 space-y-6">
                            <p>
                                <strong class="text-gray-600">SIMPERSITE</strong> adalah sistem informasi peminjaman sarana dan prasarana berbasis web yang dikembangkan oleh Jurusan Teknologi Informasi Politeknik Negeri Tanah Laut.
                            </p>
                            <p>
                                Website ini dirancang khusus untuk menjembatani kebutuhan civitas akademika dalam proses peminjaman fasilitas. Dengan sistem ini, peminjam dapat dengan mudah melakukan permohonan secara online, melacak status, serta mengelola riwayat peminjaman tanpa batasan waktu dan tempat.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Tujuan (Grid Layout Cards) -->
        <div class="bg-gray-50 py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:mx-auto mb-16 text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Tujuan & Manfaat</h2>
                    <p class="mt-3 text-lg leading-8 text-gray-600">Alasan mengapa <span> hadir di lingkungan kampus.</p>
                </div>

                <dl class="mx-auto grid max-w-2xl grid-cols-1 gap-8 text-base leading-7 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3">

                    <!-- Card 1 -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-300 hover:shadow-md transition-shadow">
                        <dt class="font-semibold text-gray-900 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#179ACE]/10 text-[#179ACE]">
                                <!-- Icon Settings -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                            </div>
                            Optimasi Pengelolaan
                        </dt>
                        <dd class="mt-4 text-gray-600">Mengoptimalkan pengelolaan sarana dan prasarana di lingkungan kampus agar lebih terstruktur.</dd>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-300 hover:shadow-md transition-shadow">
                        <dt class="font-semibold text-gray-900 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#179ACE]/10 text-[#179ACE]">
                                <!-- Icon Smartphone -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                            </div>
                            Digitalisasi Proses
                        </dt>
                        <dd class="mt-4 text-gray-600">Mempermudah proses peminjaman fasilitas secara digital, meninggalkan cara manual yang lambat.</dd>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-300 hover:shadow-md transition-shadow">
                        <dt class="font-semibold text-gray-900 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#179ACE]/10 text-[#179ACE]">
                                <!-- Icon Clock -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            Efisiensi Waktu
                        </dt>
                        <dd class="mt-4 text-gray-600">Mengurangi birokrasi berbelit dan memangkas waktu tunggu dalam proses persetujuan peminjaman.</dd>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-300 hover:shadow-md transition-shadow">
                        <dt class="font-semibold text-gray-900 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#179ACE]/10 text-[#179ACE]">
                                <!-- Icon Eye -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </div>
                            Pengawasan Efektif
                        </dt>
                        <dd class="mt-4 text-gray-600">Membantu admin dalam pengawasan dan pendataan aset yang sedang dipinjam secara real-time.</dd>
                    </div>

                    <!-- Card 5 -->
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-300 hover:shadow-md transition-shadow">
                        <dt class="font-semibold text-gray-900 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#179ACE]/10 text-[#179ACE]">
                                <!-- Icon Shield Check -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                            </div>
                            Transparansi
                        </dt>
                        <dd class="mt-4 text-gray-600">Meningkatkan transparansi dan akuntabilitas penggunaan aset kampus bagi semua pihak.</dd>
                    </div>

                </dl>
            </div>
        </div>

        <!-- Section 3: Fitur Utama (Dark Mode Block) -->
        <div class="bg-gray-700 py-24 sm:py-32 relative overflow-hidden">
            <!-- Decorative background blur -->
            <div class="absolute top-0 left-0 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-500/50 rounded-full blur-3xl opacity-50"></div>

            <div class="mx-auto max-w-7xl px-6 lg:px-8 relative z-10">
                <div class="mx-auto max-w-2xl lg:mx-0">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Fitur Utama</h2>
                    <p class="mt-6 text-lg leading-8 text-gray-300">Dirancang untuk memudahkan setiap langkah Anda.</p>
                </div>
                <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                    <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">

                        <div class="flex flex-col">
                            <dt class="text-base font-semibold leading-7 text-white flex items-center gap-x-3">
                                <!-- Icon Clipboard -->
                                <svg class="h-6 w-6 text-[#179ACE]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
                                Peminjaman Online
                            </dt>
                            <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-400">
                                <p class="flex-auto">Permohonan peminjaman sarana prasarana dapat dilakukan dari mana saja secara digital tanpa formulir kertas.</p>
                            </dd>
                        </div>

                        <div class="flex flex-col">
                            <dt class="text-base font-semibold leading-7 text-white flex items-center gap-x-3">
                                <!-- Icon Search -->
                                <svg class="h-6 w-6 text-[#179ACE]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                Pelacakan Status
                            </dt>
                            <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-400">
                                <p class="flex-auto">Monitoring status permohonan (Disetujui, Ditolak, atau Pending) secara real-time melalui dashboard.</p>
                            </dd>
                        </div>

                        <div class="flex flex-col">
                            <dt class="text-base font-semibold leading-7 text-white flex items-center gap-x-3">
                                <!-- Icon Chart -->
                                <svg class="h-6 w-6 text-[#179ACE]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                                Riwayat Peminjaman
                            </dt>
                            <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-400">
                                <p class="flex-auto">Pencatatan otomatis dan dokumentasi seluruh aktivitas peminjaman yang pernah dilakukan untuk arsip pribadi.</p>
                            </dd>
                        </div>

                    </dl>
                </div>
            </div>
        </div>

        <!-- Section 4: Jurusan TI (Split Layout) -->
        <div class="mx-auto max-w-7xl px-6 lg:px-8 py-24 sm:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="order-2 lg:order-1">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl mb-6">Jurusan Teknologi Informasi</h2>
                    <p class="text-lg text-gray-500 mb-2 font-medium">Politeknik Negeri Tanah Laut</p>
                    <div class="h-1 w-20 bg-[#179ACE] mb-8"></div>

                    <div class="space-y-6 text-gray-600 leading-relaxed">
                        <p>
                            Jurusan Teknologi Informasi Politeknik Negeri Tanah Laut merupakan salah satu program studi unggulan yang berfokus pada pengembangan sistem informasi, teknologi digital, dan solusi teknologi informasi untuk berbagai kebutuhan masyarakat dan industri.
                        </p>
                        <p>
                            Dengan didukung oleh dosen-dosen yang kompeten dan fasilitas pembelajaran yang memadai, Jurusan TI terus berkomitmen untuk menghasilkan lulusan yang siap bersaing di dunia kerja.
                        </p>
                        <blockquote class="border-l-4 border-[#179ACE] pl-4 italic text-gray-700 bg-gray-50 py-2 pr-2 rounded-r-lg">
                            "<span> merupakan kontribusi nyata Jurusan TI dalam mengembangkan solusi teknologi untuk efisiensi operasional kampus."
                        </blockquote>
                    </div>
                </div>

                <!-- Image/Visual -->
                <div class="order-1 lg:order-2">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl bg-gray-100 aspect-[5/4]">
                        <img src="{{ asset('storage/images/teknologi informasi.jpeg') }}"
                             alt="Mahasiswa Teknologi Informasi"
                             class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <p class="font-bold text-xl">Inovasi Kampus</p>
                            <p class="text-sm opacity-90">Karya Mahasiswa & Dosen</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
