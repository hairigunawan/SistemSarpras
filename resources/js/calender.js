document.addEventListener("DOMContentLoaded", () => {
    const calendarEl = document.getElementById("calendar");
    if (!calendarEl) {
        console.error("Element calendar tidak ditemukan!");
        return;
    }

    const fetchUrl = window.approvedDatesApiUrl;
    const { Calendar, dayGridPlugin, interactionPlugin } = window.FullCalendar;

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        locale: "id",

        buttonText: {
            today: 'Hari Ini',
        },

        headerToolbar: {
            left: "prev",
            center: "title",
            right: "next",
        },

        height: 'auto',
        aspectRatio: 1.2,
        fixedWeekCount: false,
        dayMaxEvents: 2,

        eventDidMount(info) {
            const dateStr = info.event.startStr.split('T')[0];

            // Cari sel tanggal yg cocok
            const dayCell = calendarEl.querySelector(`td[data-date="${dateStr}"]`);
            if (!dayCell) return;

            const type = info.event.extendedProps.sarpras_type;

            // Warna ruangan → biru soft
            if (type === "ruangan") {
                dayCell.style.backgroundColor = "#dbeafe";
                dayCell.style.borderColor = "#3b82f6";
            }

            // Warna proyektor → hijau soft
            else if (type === "proyektor") {
                dayCell.style.backgroundColor = "#dcfce7";
                dayCell.style.borderColor = "#22c55e";
            }
        },

        events: async (info, success, failure) => {
            try {
                const res = await fetch(fetchUrl);
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

                const data = await res.json();
                const events = [];

                if (data.approvedDetails) {
                    for (const date in data.approvedDetails) {
                        data.approvedDetails[date].forEach((p) => {
                            const type = p.sarpras_type;

                            events.push({
                                title: `${p.jenis_kegiatan}`,
                                start: `${p.tanggal_pinjam}T${p.jam_mulai}:00`,
                                end: `${p.tanggal_kembali}T${p.jam_selesai}:00`,

                                // Warna event
                                color: type === "ruangan" ? "#3b82f6" : "#22c55e",

                                extendedProps: {
                                    peminjam_nama: p.nama_peminjam || "N/A",
                                    jenis_kegiatan: p.jenis_kegiatan,
                                    jam_mulai: p.jam_mulai,
                                    jam_selesai: p.jam_selesai,
                                    jumlah_peserta: p.jumlah_peserta,
                                    sarpras_id: p.id_sarpras,
                                    sarpras_type: type,
                                },
                            });
                        });
                    }
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

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '<medium>' + info.event.extendedProps.jenis_kegiatan + '</medium>',
                    html: `
                        <div class="text-left space-y-3 p-2">
                            <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <span class="text-xs text-gray-500 font-medium">Peminjam</span>
                                    <p class="text-sm font-medium text-gray-800">${info.event.extendedProps.peminjam_nama}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <span class="text-xs text-gray-500 font-medium">Waktu</span>
                                    <p class="text-sm font-medium text-gray-800">${info.event.extendedProps.jam_mulai} - ${info.event.extendedProps.jam_selesai}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <div>
                                    <span class="text-xs text-gray-500 font-medium">Jumlah Peserta</span>
                                    <p class="text-sm font-medium text-gray-800">${info.event.extendedProps.jumlah_peserta} orang</p>
                                </div>
                            </div>
                        </div>
                    `,
                    icon: "info",
                    showCancelButton: id && type,
                    confirmButtonText: id && type ? "Lihat Detail" : "Tutup",
                    cancelButtonText: "Tutup",
                    confirmButtonColor: "#3b82f6",
                    cancelButtonColor: "#6b7280",
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-lg px-6 py-2.5',
                        cancelButton: 'rounded-lg px-6 py-2.5'
                    }
                }).then((result) => {
                    if (result.isConfirmed && id && type) {
                        window.location.href = `/sarana-prasarana/detail/${type}/${id}`;
                    }
                });
            }
        },

        loading(isLoading) {
            calendarEl.classList.toggle('fc-loading', isLoading);
        }
    });

    calendar.render();
});
