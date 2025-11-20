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
        dayMaxEvents: 3,

        dayCellDidMount(info) {
            const dateStr = info.date.toISOString().split('T')[0];
            info.el.setAttribute('data-date', dateStr);
        },

        eventDidMount(info) {
            const dateStr = info.event.startStr.split('T')[0];
            const dayCell = calendarEl.querySelector(`td[data-date="${dateStr}"]`);
            if (!dayCell) return;

            const types = info.event.extendedProps.sarpras_types || [];

            // Jika meminjam keduanya (ruangan + proyektor)
            if (types.includes('ruangan') && types.includes('proyektor')) {
                dayCell.style.background = 'linear-gradient(135deg, #dbeafe 50%, #dcfce7 50%)';
                dayCell.style.borderColor = '#3b82f6';
            }
            // Hanya ruangan
            else if (types.includes('ruangan')) {
                dayCell.style.backgroundColor = "#dbeafe";
                dayCell.style.borderColor = "#3b82f6";
            }
            // Hanya proyektor
            else if (types.includes('proyektor')) {
                dayCell.style.backgroundColor = "#dcfce7";
                dayCell.style.borderColor = "#22c55e";
            }

            info.el.style.cursor = 'pointer';
        },

        dayCellContent(arg) {
            return { html: arg.dayNumberText };
        },

        events: async (info, success, failure) => {
            try {
                const res = await fetch(fetchUrl);
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

                const data = await res.json();
                const events = [];
                const dateGroups = {};
                const processedPeminjaman = new Set();

                if (data.approvedDetails) {
                    // Kelompokkan peminjaman berdasarkan peminjam dan waktu
                    const peminjamanMap = {};

                    for (const date in data.approvedDetails) {
                        data.approvedDetails[date].forEach((p) => {
                            const key = `${p.nama_peminjam}_${p.tanggal_pinjam}_${p.jam_mulai}_${p.jam_selesai}`;
                            
                            if (!peminjamanMap[key]) {
                                peminjamanMap[key] = {
                                    peminjam: p.nama_peminjam || "N/A",
                                    kegiatan: p.jenis_kegiatan,
                                    tanggal_pinjam: p.tanggal_pinjam,
                                    tanggal_kembali: p.tanggal_kembali,
                                    jam_mulai: p.jam_mulai,
                                    jam_selesai: p.jam_selesai,
                                    jumlah_peserta: p.jumlah_peserta,
                                    ruangan: null,
                                    proyektor: null,
                                    types: [],
                                    ids: []
                                };
                            }

                            const type = p.sarpras_type;
                            if (type === 'ruangan') {
                                peminjamanMap[key].ruangan = p.id_sarpras;
                                peminjamanMap[key].types.push('ruangan');
                                peminjamanMap[key].ids.push({ type: 'ruangan', id: p.id_sarpras });
                            } else if (type === 'proyektor') {
                                peminjamanMap[key].proyektor = p.id_sarpras;
                                peminjamanMap[key].types.push('proyektor');
                                peminjamanMap[key].ids.push({ type: 'proyektor', id: p.id_sarpras });
                            }
                        });
                    }

                    // Buat events dari peminjaman yang sudah digabungkan
                    for (const key in peminjamanMap) {
                        const p = peminjamanMap[key];
                        const date = p.tanggal_pinjam;

                        if (!dateGroups[date]) {
                            dateGroups[date] = [];
                        }

                        let title = p.kegiatan;
                        let color = '#6b7280';
                        
                        if (p.types.includes('ruangan') && p.types.includes('proyektor')) {
                            title = `🏢📽️ ${p.kegiatan}`;
                            color = '#8b5cf6'; // Purple untuk kombinasi
                        } else if (p.types.includes('ruangan')) {
                            title = `🏢 ${p.kegiatan}`;
                            color = '#3b82f6'; // Biru untuk ruangan
                        } else if (p.types.includes('proyektor')) {
                            title = `📽️ ${p.kegiatan}`;
                            color = '#22c55e'; // Hijau untuk proyektor
                        }

                        events.push({
                            title: title,
                            start: `${p.tanggal_pinjam}T${p.jam_mulai}:00`,
                            end: `${p.tanggal_kembali}T${p.jam_selesai}:00`,
                            color: color,
                            extendedProps: {
                                peminjam_nama: p.peminjam,
                                jenis_kegiatan: p.kegiatan,
                                jam_mulai: p.jam_mulai,
                                jam_selesai: p.jam_selesai,
                                jumlah_peserta: p.jumlah_peserta,
                                sarpras_ids: p.ids,
                                sarpras_types: p.types,
                                ruangan_id: p.ruangan,
                                proyektor_id: p.proyektor
                            },
                        });

                        dateGroups[date].push({
                            peminjam: p.peminjam,
                            types: p.types,
                            kegiatan: p.kegiatan,
                            jam_mulai: p.jam_mulai,
                            jam_selesai: p.jam_selesai,
                            ruangan_id: p.ruangan,
                            proyektor_id: p.proyektor
                        });
                    }
                }

                setTimeout(() => {
                    for (const date in dateGroups) {
                        const dayCell = calendarEl.querySelector(`td[data-date="${date}"]`);
                        if (dayCell && dateGroups[date].length > 0) {
                            addBorrowerBadge(dayCell, dateGroups[date]);
                        }
                    }
                }, 100);

                success(events);
            } catch (error) {
                console.error("Error fetching approved dates:", error);
                failure(error);
            }
        },

        eventClick(info) {
            const sarprasIds = info.event.extendedProps.sarpras_ids || [];
            const types = info.event.extendedProps.sarpras_types || [];
            const ruanganId = info.event.extendedProps.ruangan_id;
            const proyektorId = info.event.extendedProps.proyektor_id;

            if (typeof Swal !== 'undefined') {
                let sarprasInfo = '';
                
                if (types.includes('ruangan') && types.includes('proyektor')) {
                    sarprasInfo = `
                        <div class="flex items-start gap-3 p-3 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                            <svg class="w-5 h-5 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <div>
                                <span class="text-xs text-purple-600 font-bold">Jenis Peminjaman</span>
                                <p class="text-sm font-medium text-gray-800">Ruangan + Proyektor</p>
                            </div>
                        </div>
                    `;
                } else if (types.includes('ruangan')) {
                    sarprasInfo = `
                        <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <div>
                                <span class="text-xs text-blue-600 font-bold">Jenis Peminjaman</span>
                                <p class="text-sm font-medium text-gray-800">Ruangan</p>
                            </div>
                        </div>
                    `;
                } else if (types.includes('proyektor')) {
                    sarprasInfo = `
                        <div class="flex items-start gap-3 p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
                            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <span class="text-xs text-green-600 font-bold">Jenis Peminjaman</span>
                                <p class="text-sm font-medium text-gray-800">Proyektor</p>
                            </div>
                        </div>
                    `;
                }

                Swal.fire({
                    title: '<div class="text-lg font-bold">' + info.event.extendedProps.jenis_kegiatan + '</div>',
                    html: `
                        <div class="text-left space-y-3 p-2">
                            ${sarprasInfo}
                            
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <span class="text-xs text-gray-500 font-medium">Peminjam</span>
                                    <p class="text-sm font-medium text-gray-800">${info.event.extendedProps.peminjam_nama}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <span class="text-xs text-gray-500 font-medium">Waktu</span>
                                    <p class="text-sm font-medium text-gray-800">${info.event.extendedProps.jam_mulai} - ${info.event.extendedProps.jam_selesai}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <div>
                                    <span class="text-xs text-gray-500 font-medium">Jumlah Peserta</span>
                                    <p class="text-sm font-medium text-gray-800">${info.event.extendedProps.jumlah_peserta} orang</p>
                                </div>
                            </div>
                        </div>
                    `,
                    icon: false,
                    showCancelButton: sarprasIds.length > 0,
                    confirmButtonText: sarprasIds.length > 0 ? "Lihat Detail" : "Tutup",
                    cancelButtonText: "Tutup",
                    confirmButtonColor: "#3b82f6",
                    cancelButtonColor: "#6b7280",
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-lg px-6 py-2.5',
                        cancelButton: 'rounded-lg px-6 py-2.5'
                    }
                }).then((result) => {
                    if (result.isConfirmed && sarprasIds.length > 0) {
                        // Jika meminjam keduanya, prioritaskan ruangan
                        if (ruanganId) {
                            window.location.href = `/sarana-prasarana/detail/ruangan/${ruanganId}`;
                        } else if (proyektorId) {
                            window.location.href = `/sarana-prasarana/detail/proyektor/${proyektorId}`;
                        }
                    }
                });
            }
        },

        loading(isLoading) {
            calendarEl.classList.toggle('fc-loading', isLoading);
        }
    });

    function showBorrowerDetails(borrowers, date) {
        if (typeof Swal === 'undefined') return;

        const formattedDate = new Date(date).toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const ruanganOnly = borrowers.filter(b => b.types.length === 1 && b.types.includes('ruangan'));
        const proyektorOnly = borrowers.filter(b => b.types.length === 1 && b.types.includes('proyektor'));
        const both = borrowers.filter(b => b.types.length === 2);

        let borrowersHtml = `
            <div class="text-left space-y-4">
                <div class="bg-gradient-to-r from-blue-500 to-purple-500 p-4 rounded-lg text-white">
                    <p class="text-sm font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        ${formattedDate}
                    </p>
                </div>
        `;

        if (both.length > 0) {
            borrowersHtml += `
                <div>
                    <h3 class="text-sm font-bold text-purple-700 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Ruangan + Proyektor (${both.length})
                    </h3>
                    <div class="space-y-2">
            `;

            both.forEach((borrower, index) => {
                borrowersHtml += `
                    <div class="bg-gradient-to-r from-blue-50 to-green-50 p-3 rounded-lg border-l-4 border-purple-500">
                        <div class="flex items-start gap-2">
                            <span class="bg-purple-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mt-0.5">${index + 1}</span>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 text-sm">${borrower.peminjam}</p>
                                <p class="text-xs text-gray-600 mt-1">${borrower.kegiatan}</p>
                                <p class="text-xs text-purple-600 font-medium mt-1">🏢 Ruangan + 📽️ Proyektor</p>
                                <p class="text-xs text-gray-500 mt-1">⏰ ${borrower.jam_mulai} - ${borrower.jam_selesai}</p>
                            </div>
                        </div>
                    </div>
                `;
            });

            borrowersHtml += `</div></div>`;
        }

        if (ruanganOnly.length > 0) {
            borrowersHtml += `
                <div>
                    <h3 class="text-sm font-bold text-blue-700 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Ruangan Saja (${ruanganOnly.length})
                    </h3>
                    <div class="space-y-2">
            `;

            ruanganOnly.forEach((borrower, index) => {
                borrowersHtml += `
                    <div class="bg-blue-50 p-3 rounded-lg border-l-4 border-blue-500">
                        <div class="flex items-start gap-2">
                            <span class="bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mt-0.5">${index + 1}</span>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 text-sm">${borrower.peminjam}</p>
                                <p class="text-xs text-gray-600 mt-1">${borrower.kegiatan}</p>
                                <p class="text-xs text-gray-500 mt-1">⏰ ${borrower.jam_mulai} - ${borrower.jam_selesai}</p>
                            </div>
                        </div>
                    </div>
                `;
            });

            borrowersHtml += `</div></div>`;
        }

        if (proyektorOnly.length > 0) {
            borrowersHtml += `
                <div>
                    <h3 class="text-sm font-bold text-green-700 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Proyektor Saja (${proyektorOnly.length})
                    </h3>
                    <div class="space-y-2">
            `;

            proyektorOnly.forEach((borrower, index) => {
                borrowersHtml += `
                    <div class="bg-green-50 p-3 rounded-lg border-l-4 border-green-500">
                        <div class="flex items-start gap-2">
                            <span class="bg-green-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mt-0.5">${index + 1}</span>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 text-sm">${borrower.peminjam}</p>
                                <p class="text-xs text-gray-600 mt-1">${borrower.kegiatan}</p>
                                <p class="text-xs text-gray-500 mt-1">⏰ ${borrower.jam_mulai} - ${borrower.jam_selesai}</p>
                            </div>
                        </div>
                    </div>
                `;
            });

            borrowersHtml += `</div></div>`;
        }

        borrowersHtml += `
                <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                    <p class="text-xs text-yellow-800 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Klik pada event untuk melihat detail lengkap
                    </p>
                </div>
            </div>
        `;

        Swal.fire({
            title: `<div class="text-lg font-bold text-gray-800">📋 Daftar Peminjam</div>`,
            html: borrowersHtml,
            icon: false,
            confirmButtonText: "Tutup",
            confirmButtonColor: "#3b82f6",
            width: '650px',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-lg px-6 py-2.5',
                htmlContainer: 'max-h-96 overflow-y-auto'
            }
        });
    }

    function addBorrowerBadge(dayCell, borrowers) {
        if (dayCell.querySelector('.borrower-badge')) {
            return;
        }

        const dayCellContent = dayCell.querySelector('.fc-daygrid-day-frame');
        if (!dayCellContent) return;

        const badgeContainer = document.createElement('div');
        badgeContainer.className = 'borrower-badge';
        badgeContainer.style.cssText = `
            position: absolute;
            top: 2px;
            right: 2px;
            display: flex;
            flex-direction: column;
            gap: 2px;
            z-index: 10;
            cursor: pointer;
        `;

        badgeContainer.addEventListener('click', (e) => {
            e.stopPropagation();
            showBorrowerDetails(borrowers, dayCell.getAttribute('data-date'));
        });

        const bothCount = borrowers.filter(b => b.types.length === 2).length;
        const ruanganOnlyCount = borrowers.filter(b => b.types.length === 1 && b.types.includes('ruangan')).length;
        const proyektorOnlyCount = borrowers.filter(b => b.types.length === 1 && b.types.includes('proyektor')).length;

        // Badge untuk peminjaman kombinasi (ruangan + proyektor)
        if (bothCount > 0) {
            const bothBadge = document.createElement('div');
            bothBadge.style.cssText = `
                background: linear-gradient(135deg, #3b82f6 50%, #22c55e 50%);
                color: white;
                font-size: 10px;
                padding: 2px 6px;
                border-radius: 9999px;
                font-weight: 600;
                white-space: nowrap;
                box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            `;
            bothBadge.textContent = `${bothCount} R+P`;
            bothBadge.title = `${bothCount} Peminjaman Ruangan + Proyektor`;
            badgeContainer.appendChild(bothBadge);
        }

        // Badge untuk ruangan saja
        if (ruanganOnlyCount > 0) {
            const ruanganBadge = document.createElement('div');
            ruanganBadge.style.cssText = `
                background-color: #3b82f6;
                color: white;
                font-size: 10px;
                padding: 2px 6px;
                border-radius: 9999px;
                font-weight: 600;
                white-space: nowrap;
                box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            `;
            ruanganBadge.textContent = `${ruanganOnlyCount} R`;
            ruanganBadge.title = `${ruanganOnlyCount} Peminjaman Ruangan`;
            badgeContainer.appendChild(ruanganBadge);
        }

        // Badge untuk proyektor saja
                // Badge untuk ruangan saja
        if (ruanganOnlyCount > 0) {
            const ruanganBadge = document.createElement('div');
            ruanganBadge.style.cssText = `
                background-color: #3b82f6;
                color: white;
                font-size: 10px;
                padding: 2px 6px;
                border-radius: 9999px;
                font-weight: 600;
                white-space: nowrap;
                box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            `;
            ruanganBadge.textContent = `${ruanganOnlyCount} R`;
            ruanganBadge.title = `${ruanganOnlyCount} Peminjaman Ruangan`;
            badgeContainer.appendChild(ruanganBadge);
        }

        // Badge untuk proyektor saja
        if (proyektorOnlyCount > 0) {
            const proyektorBadge = document.createElement('div');
            proyektorBadge.style.cssText = `
                background-color: #22c55e;
                color: white;
                font-size: 10px;
                padding: 2px 6px;
                border-radius: 9999px;
                font-weight: 600;
                white-space: nowrap;
                box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            `;
            proyektorBadge.textContent = `${proyektorOnlyCount} P`;
            proyektorBadge.title = `${proyektorOnlyCount} Peminjaman Proyektor`;
            badgeContainer.appendChild(proyektorBadge);
        }

        dayCellContent.style.position = "relative";
        dayCellContent.appendChild(badgeContainer);
    }

    // Jalankan calendar
    calendar.render();
});
