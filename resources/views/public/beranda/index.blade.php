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
            <br class="text-lg opacity-90">
                SIMPERSITE hadir sebagai solusi inovatif untuk pengelolaan peminjaman ruangandan proyektor yang digunakan<br>oleh civitas Jurusan Teknologi Informasi.</br>
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
    </div>
</div>
@endsection

@push('scripts')

@vite(['resources/css/app.css', 'resources/js/app.js'])

<script>
// Jalankan ketika DOM siap
document.addEventListener("DOMContentLoaded", () => {
    const calendarEl = document.getElementById("calendar");
    if (!calendarEl) return; // aman

    const sarprasType = calendarEl.dataset.sarprasType ?? null; // jika pakai
    const fetchUrl = `/api/peminjaman/approved-dates/all/all`;

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        locale: "id",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay",
        },

        events: async (info, success, failure) => {
            try {
                const res = await fetch(fetchUrl);
                const data = await res.json();

                const events = [];

                for (const date in data.approvedDetails) {
                    data.approvedDetails[date].forEach((p) => {
                        events.push({
                            title: `${p.jenis_kegiatan} (${p.jam_mulai}-${p.jam_selesai})`,
                            start: `${p.tanggal_pinjam}T${p.jam_mulai}:00`,
                            end: `${p.tanggal_kembali}T${p.jam_selesai}:00`,
                            color: "#179ACE",
                            extendedProps: {
                                peminjam_nama: p.peminjam_nama,
                                jenis_kegiatan: p.jenis_kegiatan,
                                jam_mulai: p.jam_mulai,
                                jam_selesai: p.jam_selesai,
                                jumlah_peserta: p.jumlah_peserta,
                                sarpras_id: p.id_sarpras,
                                sarpras_type: sarprasType,
                            },
                        });
                    });
                }

                success(events);
            } catch (error) {
                console.error("Error fetching approved dates:", error);
                failure(error);
            }
        },

        eventClick(info) {
            const id = info.event.extendedProps.sarpras_id;
            const type = info.event.extendedProps.sarpras_type;

            if (id && type) {
                window.location.href = `/sarana-prasarana/detail/${type}/${id}`;
                return;
            }

            Swal.fire({
                title: info.event.title,
                html: `
                    <p><strong>Peminjam:</strong> ${info.event.extendedProps.peminjam_nama}</p>
                    <p><strong>Kegiatan:</strong> ${info.event.extendedProps.jenis_kegiatan}</p>
                    <p><strong>Waktu:</strong> ${info.event.extendedProps.jam_mulai} - ${info.event.extendedProps.jam_selesai}</p>
                    <p><strong>Jumlah Peserta:</strong> ${info.event.extendedProps.jumlah_peserta}</p>
                `,
                icon: "info",
                confirmButtonText: "Tutup",
            });
        },
    });

    calendar.render();
});

</script>
@endpush

