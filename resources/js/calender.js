document.addEventListener("DOMContentLoaded", () => {
    const calendarEl = document.getElementById("calendar");
    if (!calendarEl) {
        console.error("Element calendar tidak ditemukan!");
        return;
    }

    const fetchUrl = window.approvedDatesApiUrl;
    // Pastikan FullCalendar sudah diload di halaman layout
    if (!window.FullCalendar) {
        console.error("Library FullCalendar belum diload!");
        return;
    }
    const { Calendar, dayGridPlugin, interactionPlugin } = window.FullCalendar;

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        locale: "id",
        themeSystem: 'standard',
        buttonText: { today: 'Hari Ini' },
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: ""
        },
        height: 'auto',
        contentHeight: 650,
        fixedWeekCount: false,
        showNonCurrentDates: false,
        dayMaxEvents: 2,

        dayCellDidMount(info) {
            const dateStr = info.date.toISOString().split('T')[0];
            info.el.setAttribute('data-date', dateStr);
            const contentEl = info.el.querySelector('.fc-daygrid-day-frame');
            if(contentEl) {
                const badgeContainer = document.createElement('div');
                badgeContainer.className = 'custom-badge-container';
                contentEl.appendChild(badgeContainer);
            }
        },

        eventDidMount(info) {
             info.el.title = info.event.title;
        },

        // --- FETCH EVENTS ---
        events: async (info, success, failure) => {
            try {
                const res = await fetch(fetchUrl);
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

                const data = await res.json();
                const events = [];
                const dateGroups = {};

                if (data.approvedDetails) {
                    const peminjamanMap = {};
                    // Grouping Logic
                    for (const date in data.approvedDetails) {
                        data.approvedDetails[date].forEach((p) => {
                            const key = `${p.nama_peminjam}_${p.tanggal_pinjam}_${p.jam_mulai}_${p.jam_selesai}`;
                            if (!peminjamanMap[key]) {
                                peminjamanMap[key] = {
                                    peminjam: p.nama_peminjam || "N/A",
                                    email: p.email_peminjam, // Pastikan field ini ada dari API jika ingin ditampilkan
                                    kegiatan: p.jenis_kegiatan,
                                    lokasi: p.lokasi_kegiatan, // Pastikan field ini ada
                                    tanggal_pinjam: p.tanggal_pinjam,
                                    tanggal_kembali: p.tanggal_kembali,
                                    jam_mulai: p.jam_mulai,
                                    jam_selesai: p.jam_selesai,
                                    jumlah_peserta: p.jumlah_peserta,
                                    types: [],
                                    ids: []
                                };
                            }
                            const type = p.sarpras_type;
                            if (type === 'ruangan') {
                                peminjamanMap[key].ruangan_id = p.id_sarpras;
                                peminjamanMap[key].types.push('ruangan');
                                peminjamanMap[key].ids.push({ type: 'ruangan', id: p.id_sarpras });
                            } else if (type === 'proyektor') {
                                peminjamanMap[key].proyektor_id = p.id_sarpras;
                                peminjamanMap[key].types.push('proyektor');
                                peminjamanMap[key].ids.push({ type: 'proyektor', id: p.id_sarpras });
                            }
                        });
                    }

                    // Processing Events for Calendar
                    for (const key in peminjamanMap) {
                        const p = peminjamanMap[key];
                        const date = p.tanggal_pinjam;
                        if (!dateGroups[date]) dateGroups[date] = [];

                        // Warna Event Bar (Kalender)
                        let bgColor = '#f3f4f6'; let borderColor = '#d1d5db'; let textColor = '#374151';
                        if (p.types.includes('ruangan') && p.types.includes('proyektor')) {
                            bgColor = '#f5f3ff'; borderColor = '#8b5cf6'; textColor = '#5b21b6';
                        } else if (p.types.includes('ruangan')) {
                            bgColor = '#eff6ff'; borderColor = '#3b82f6'; textColor = '#1e40af';
                        } else if (p.types.includes('proyektor')) {
                            bgColor = '#f0fdf4'; borderColor = '#22c55e'; textColor = '#166534';
                        }

                        events.push({
                            title: p.kegiatan,
                            start: `${p.tanggal_pinjam}T${p.jam_mulai}:00`,
                            end: `${p.tanggal_kembali}T${p.jam_selesai}:00`,
                            backgroundColor: bgColor,
                            borderColor: borderColor,
                            textColor: textColor,
                            extendedProps: {
                                ...p,
                                sarpras_ids: p.ids,
                                sarpras_types: p.types
                            },
                        });
                        dateGroups[date].push(p);
                    }
                }

                // Render Badge (Titik Kecil)
                setTimeout(() => {
                    for (const date in dateGroups) {
                        const dayCell = calendarEl.querySelector(`td[data-date="${date}"]`);
                        if (dayCell && dateGroups[date].length > 0) {
                            renderBadges(dayCell, dateGroups[date]);
                        }
                    }
                }, 100);

                success(events);
            } catch (error) {
                console.error("Error:", error);
                failure(error);
            }
        },

        eventClick(info) {
             // Klik pada Event Bar (Kotak berwarna di kalender)
             showEventDetailModal(info.event);
        },
    });

    calendar.render();

    function renderBadges(dayCell, borrowers) {
        const container = dayCell.querySelector('.custom-badge-container');
        if (!container) return;
        container.innerHTML = '';

        const bothCount = borrowers.filter(b => b.types.length === 2).length;
        const ruanganOnlyCount = borrowers.filter(b => b.types.length === 1 && b.types.includes('ruangan')).length;
        const proyektorOnlyCount = borrowers.filter(b => b.types.length === 1 && b.types.includes('proyektor')).length;

        const createBadge = (text, bgClass, textClass) => {
            const badge = document.createElement('div');
            badge.className = `badge-item ${bgClass} ${textClass}`;
            badge.textContent = text;
            badge.onclick = (e) => {
                e.stopPropagation(); // Mencegah trigger eventClick kalender
                // Klik pada Badge (Memanggil Modal List)
                showBorrowerDetails(borrowers, dayCell.getAttribute('data-date'));
            };
            return badge;
        };

        if (bothCount > 0) {
            const b = createBadge(`${bothCount} Gabungan`, 'bg-gradient-to-r from-blue-500 to-purple-500', 'text-white');
            container.appendChild(b);
        }
        if (ruanganOnlyCount > 0) {
            const b = createBadge(`${ruanganOnlyCount} Ruangan`, 'bg-blue-100', 'text-blue-700');
            b.style.border = '1px solid #bfdbfe';
            container.appendChild(b);
        }
        if (proyektorOnlyCount > 0) {
            const b = createBadge(`${proyektorOnlyCount} Proyektor`, 'bg-green-100', 'text-green-700');
            b.style.border = '1px solid #bbf7d0';
            container.appendChild(b);
        }
    }

    function showEventDetailModal(event) {
        if (typeof Swal === 'undefined') return;

        const props = event.extendedProps;
        if (!props) return;

        // Helpers
        const formatDateIndo = (dStr) => {
            try { return new Date(dStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }); }
            catch { return dStr; }
        };
        const formatTime = (tStr) => String(tStr).substring(0, 5);

        // Label Logic
        let sarprasLabel = '-';
        const types = props.sarpras_types || [];
        if (types.includes('ruangan') && types.includes('proyektor')) sarprasLabel = 'Ruangan + Proyektor';
        else if (types.includes('ruangan')) sarprasLabel = 'Ruangan';
        else if (types.includes('proyektor')) sarprasLabel = 'Proyektor';

        const content = `
            <div class="text-left font-sans">
                <div class="mb-6 pb-4 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900">Informasi Detail</h3>
                    <p class="text-sm text-gray-500 mt-1">Rincian lengkap mengenai pengajuan peminjaman.</p>
                </div>
                <div class="flex flex-col gap-5">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-start border-b border-gray-50 pb-4">
                        <span class="text-gray-500 font-medium text-sm sm:w-1/3">Nama Peminjam</span>
                        <div class="text-gray-900 font-bold text-sm sm:w-2/3 text-right">
                            ${props.peminjam}
                            ${props.email ? `<span class="text-gray-400 font-normal block sm:inline text-xs sm:text-sm">(${props.email})</span>` : ''}
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between sm:items-start border-b border-gray-50 pb-4">
                        <span class="text-gray-500 font-medium text-sm sm:w-1/3">Jenis Kegiatan</span>
                        <span class="text-gray-900 text-sm sm:w-2/3 text-right">${props.kegiatan || '-'}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between sm:items-start border-b border-gray-50 pb-4">
                        <span class="text-gray-500 font-medium text-sm sm:w-1/3">Sarpras</span>
                        <span class="text-gray-900 font-semibold text-sm sm:w-2/3 text-right">${sarprasLabel}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between sm:items-start border-b border-gray-50 pb-4">
                        <span class="text-gray-500 font-medium text-sm sm:w-1/3">Lokasi</span>
                        <span class="text-gray-900 text-sm sm:w-2/3 text-right">${props.lokasi || 'Gedung Utama'}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between sm:items-start pt-1">
                        <span class="text-gray-500 font-medium text-sm sm:w-1/3">Jadwal</span>
                        <div class="text-right sm:w-2/3">
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">MULAI</span>
                            <div class="text-gray-900 font-medium text-sm">
                                <span class="font-bold">${formatDateIndo(props.tanggal_pinjam)}</span> Pukul ${formatTime(props.jam_mulai)}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        Swal.fire({
            html: content,
            showCloseButton: true,
            showConfirmButton: (props.sarpras_ids && props.sarpras_ids.length > 0),
            confirmButtonText: 'Lihat Asset',
            confirmButtonColor: '#3ec3cc',
            width: '600px',
        }).then((result) => {
            if (result.isConfirmed) {
                if (props.ruangan_id) window.location.href = `/sarana-prasarana/detail/ruangan/${props.ruangan_id}`;
                else if (props.proyektor_id) window.location.href = `/sarana-prasarana/detail/proyektor/${props.proyektor_id}`;
            }
        });
    }

    function showBorrowerDetails(borrowers, date) {
        if (typeof Swal === 'undefined') return;

        const dateObj = new Date(date);
        const dateStr = dateObj.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        const formatTime = (time) => String(time).split(':').slice(0, 2).join(':');

        const renderRow = (b, type) => {
            let accentColor = 'border-gray-300';
            let badgeBg = 'bg-gray-100';
            let badgeText = 'text-gray-600';

            if (type === 'ruangan') { accentColor = 'border-blue-500'; badgeBg = 'bg-blue-50'; badgeText = 'text-blue-600'; }
            else if (type === 'proyektor') { accentColor = 'border-green-500'; badgeBg = 'bg-green-50'; badgeText = 'text-green-600'; }
            else if (type === 'gabungan') { accentColor = 'border-purple-500'; badgeBg = 'bg-purple-50'; badgeText = 'text-purple-600'; }

            return `
                <div class="group relative flex flex-col gap-2 p-4 mb-3 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 ${accentColor}"></div>
                    <div class="grid items-start gap-y-3 pl-2">
                    <span class="font-medium text-gray-700 tracking-tight">${b.peminjam}</span>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium">Kegiatan: ${b.kegiatan}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-sm font-medium">Waktu Kegiatan: ${formatTime(b.jam_mulai)} - ${formatTime(b.jam_selesai)}</span>
                        </div>
                    </div>
                </div>
            `;
        };

        const both = borrowers.filter(b => b.types.length === 2);
        const ruangan = borrowers.filter(b => b.types.length === 1 && b.types.includes('ruangan'));
        const proyektor = borrowers.filter(b => b.types.length === 1 && b.types.includes('proyektor'));

        let html = `<div class="text-left max-h-[60vh] overflow-y-auto px-1 pt-2 custom-scrollbar">`;

        if(both.length) {
            html += `<div class="mb-5"><div class="flex items-center gap-2 mb-3 sticky top-0 bg-white py-2 z-10 border-b border-gray-100 backdrop-blur-sm bg-opacity-95"><h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Gabungan (${both.length})</h4></div><div class="pl-1">${both.map(b => renderRow(b, 'gabungan')).join('')}</div></div>`;
        }
        if(ruangan.length) {
            html += `<div class="mb-5"><div class="flex items-center gap-2 mb-3 sticky top-0 bg-white py-2 z-10 border-b border-gray-100 backdrop-blur-sm bg-opacity-95"><h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Ruangan (${ruangan.length})</h4></div><div class="pl-1">${ruangan.map(b => renderRow(b, 'ruangan')).join('')}</div></div>`;
        }
        if(proyektor.length) {
            html += `<div class="mb-5"><div class="flex items-center gap-2 mb-3 sticky top-0 bg-white py-2 z-10 border-b border-gray-100 backdrop-blur-sm bg-opacity-95"><h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Proyektor (${proyektor.length})</h4></div><div class="pl-1">${proyektor.map(b => renderRow(b, 'proyektor')).join('')}</div></div>`;
        }
        html += `</div>`;

        Swal.fire({
            title: `<div class="flex flex-col items-center pt-2 px-2"><span class="text-xl font-bold text-gray-800">${dateStr}</span></div>`,
            html: html,
            width: '500px',
            showConfirmButton: false,
            showCloseButton: false,
        });
    }
});
