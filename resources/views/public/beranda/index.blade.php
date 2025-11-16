@extends('layouts.guest')
@section('title', 'Sarpras - Sistem Informasi Sarana dan Prasarana')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Hero Section -->
    <div class="relative w-full h-72 md:h-[545px]">
        <!-- Background Image -->
        <img src="{{ asset('storage/images/GKT.jpg') }}"
            class="w-full h-full object-cover" alt="Sarana Prasarana">

        <!-- Overlay Gelap -->
        <div class="absolute inset-0 bg-black/60"></div>

        <!-- Teks di Tengah -->
        <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white px-4">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">
                Sistem Informasi Sarana dan Prasarana
            </h1>
            <p class="text-lg opacity-90">
                Monitor dan kelola fasilitas pendukung pembelajaran
            </p>
        </div>
    </div>


    <div class="container mx-auto px-4 md:px-6 py-8">
        <!-- Informasi Jurusan -->
        <div class="mb-12">
            <div class="bg-white rounded-t-2xl overflow-hidden border border-gray-300">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-8 text-white">
                    <div class="flex md:flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <p class="border w-1 bg-white h-32"></p>
                            <div class="mb-6 md:mb-0">
                                <div class="flex items-center mb-3">
                                    <h2 class="text-3xl font-semibold">Teknologi Informasi</h2>
                                </div>
                                <p class="text-blue-100 text-sm mb-2">Politeknik Negeri Tanah Laut</p>
                                <div class="flex items-center text-blue-50 text-xs">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Panggung, Kec.Pelaihari, Kab.Tanah Laut, Kalimantan Selatan
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/20 backdrop-blur px-4 py-2 rounded-lg">
                            <img src="{{ asset('storage/images/GKT.jpg') }}" alt="" class="relative h-40">
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 p-6 bg-gray-50">
                    <div class="text-center hover:text-blue-100">
                        <div class="text-2xl font-bold text-gray-600 hover:text-blue-100">{{ ($RuanganTersedia ?? 0) + ($RuanganTerpakai ?? 0) + ($RuanganPerbaikan ?? 0) }}</div>
                        <p class="text-sm text-gray-600">Total Ruangan</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-600 hover:text-blue-100">{{ ($ProyektorTersedia ?? 0) + ($ProyektorTerpakai ?? 0) + ($ProyektorPerbaikan ?? 0) }}</div>
                        <p class="text-sm text-gray-600">Total Proyektor</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-600 hover:text-blue-100">{{ $RuanganTersedia ?? 0 }}</div>
                        <p class="text-sm text-gray-600">Ruangan Tersedia</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-600 hover:text-blue-100">{{ $ProyektorTersedia ?? 0 }}</div>
                        <p class="text-sm text-gray-600">Proyektor Tersedia</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-600 hover:text-blue-100">{{ ($RuanganTerpakai ?? 0) + ($ProyektorTerpakai ?? 0) }}</div>
                        <p class="text-sm text-gray-600">Terpakai</p>
                    </div>
                </div>
            </div>
        </div>


            <!-- Catatan Penting -->
            <div class="mt-6 bg-blue-50 border-l-4 border-[#179ACE] p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#179ACE] mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 mb-1">Informasi Penting</h4>
                        <p class="text-sm text-blue-800">
                            Sistem ini menampilkan data real-time ketersediaan sarana dan prasarana di Jurusan Teknologi Informasi.
                            Untuk peminjaman atau reservasi, silakan hubungi bagian administrasi atau gunakan sistem booking online.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Ruangan Terpakai -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-800">Jadwal Ruangan Terpakai</h2>
                </div>
                @if(isset($labs) && count($labs) > 0)
                    <span class="bg-blue-100 text-gray-600 text-sm font-medium px-3 py-1 rounded-full">
                        {{ count($labs) }} ruangan aktif
                    </span>
                @endif
            </div>

            @if(isset($labs) && count($labs) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($labs as $lab)
                        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                            <!-- Header -->
                            <div class="bg-gradient-to-r from-red-500 to-red-600 p-4 text-white">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-lg font-bold">{{ $lab['nama'] }}</h4>
                                    <span class="bg-white/20 backdrop-blur px-2 py-1 text-xs rounded-full font-medium">
                                        Terpakai
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <div>
                                            <span class="text-sm text-gray-500">Kelas</span>
                                            <p class="font-semibold text-gray-800">{{ $lab['kelas'] }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <div>
                                            <span class="text-sm text-gray-500">Mata Kuliah</span>
                                            <p class="font-semibold text-gray-800">{{ $lab['matkul'] }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <span class="text-sm text-gray-500">Waktu</span>
                                            <p class="font-semibold text-gray-800">{{ $lab['waktu'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <button class="w-full bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-sm">
                                        Lihat Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-600 mb-2">Tidak ada ruangan terpakai</h3>
                    <p class="text-gray-400 text-sm">Semua ruangan saat ini tersedia untuk digunakan</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var fetchUrl = `/api/peminjaman/approved-dates/all/all`; // Mengambil semua peminjaman tanpa filter awal

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                fetch(fetchUrl)
                    .then(response => response.json())
                    .then(data => {
                        var events = [];
                        for (const date in data.approvedDetails) {
                            data.approvedDetails[date].forEach(peminjaman => {
                                events.push({
                                    title: `${peminjaman.jenis_kegiatan} (${peminjaman.jam_mulai}-${peminjaman.jam_selesai})`,
                                    start: `${peminjaman.tanggal_pinjam}T${peminjaman.jam_mulai}:00`,
                                    end: `${peminjaman.tanggal_kembali}T${peminjaman.jam_selesai}:00`,
                                    color: '#179ACE', // Warna untuk tanggal yang disetujui
                                    extendedProps: {
                                        peminjam_nama: peminjaman.peminjam_nama,
                                        jenis_kegiatan: peminjaman.jenis_kegiatan,
                                        jam_mulai: peminjaman.jam_mulai,
                                        jam_selesai: peminjaman.jam_selesai,
                                        jumlah_peserta: peminjaman.jumlah_peserta,
                                        sarpras_id: peminjaman.id_sarpras, // Asumsi ada id_sarpras di objek peminjaman
                                        sarpras_type: sarprasType // Menggunakan sarprasType dari scope luar
                                    }
                                });
                            });
                        }
                        successCallback(events);
                    })
                    .catch(error => {
                        console.error('Error fetching approved dates:', error);
                        failureCallback(error);
                    });
            },
            eventClick: function(info) {
                var sarprasId = info.event.extendedProps.sarpras_id;
                var sarprasType = info.event.extendedProps.sarpras_type;
                if (sarprasId && sarprasType) {
                    window.location.href = `/sarana-prasarana/detail/${sarprasType}/${sarprasId}`;
                } else {
                    Swal.fire({
                        title: info.event.title,
                        html: `
                            <p><strong>Peminjam:</strong> ${info.event.extendedProps.peminjam_nama}</p>
                            <p><strong>Kegiatan:</strong> ${info.event.extendedProps.jenis_kegiatan}</p>
                            <p><strong>Waktu:</strong> ${info.event.extendedProps.jam_mulai} - ${info.event.extendedProps.jam_selesai}</p>
                            <p><strong>Jumlah Peserta:</strong> ${info.event.extendedProps.jumlah_peserta}</p>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Tutup'
                    });
                }
            }
        });
        calendar.render();
    });
</script>
@endpush

