@extends('layouts.guest')

@section('title', 'Form Peminjaman Sarpras')

@push('styles')
    {{-- Kita tidak lagi menggunakan link CDN Flatpickr, tapi langsung menggunakan style kustom di bawah --}}
<style>
    /* Shadcn/UI Inspired Calendar Trigger */
    .input-calendar-trigger {
        width: 100%;
        background-color: transparent;
        border: 1px solid #d1d5db; /* border-gray-300 */
        border-radius: 0.375rem; /* rounded-md */
        font-size: 0.875rem; /* text-sm */
        color: #374151; /* text-gray-700 */
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .input-calendar-trigger::placeholder {
        color: #9ca3af; /* text-gray-400 */
    }
    .input-calendar-trigger:hover {
        border-color: #9ca3af; /* border-gray-400 */
    }
    .input-calendar-trigger:focus {
        outline: 2px solid transparent;
        outline-offset: 2px;
        box-shadow: 0 0 0 2px #3b82f6; /* ring-2 ring-blue-500 */
        border-color: #3b82f6;
    }

    /* Shadcn/UI Inspired Dark Calendar Theme */
    :root {
        --fp-bg: #09090b; /* Zinc 950 */
        --fp-text: #fafafa; /* Zinc 50 */
        --fp-border: #27272a; /* Zinc 800 */
        --fp-selected: #27272a; /* Zinc 800 */
        --fp-hover: #27272a; /* Zinc 800 */
        --fp-weekday: #a1a1aa; /* Zinc 400 */
        --fp-header-text: #fafafa; /* Zinc 50 */
        --fp-booked-bg: #3b82f6;
        --fp-booked-text: #ffffff;
    }
    .flatpickr-calendar {
        background: var(--fp-bg);
        border: 1px solid var(--fp-border);
        border-radius: 0.5rem; /* rounded-lg */
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        color: var(--fp-text);
        width: auto;
    }
    .flatpickr-months .flatpickr-month {
        color: var(--fp-header-text);
        fill: var(--fp-header-text);
        font-weight: 600;
    }
    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        color: var(--fp-header-text);
        fill: var(--fp-header-text);
        transition: background-color 0.15s;
        border-radius: 0.375rem;
    }
    .flatpickr-months .flatpickr-prev-month:hover,
    .flatpickr-months .flatpickr-next-month:hover {
        background: var(--fp-hover);
    }
    span.flatpickr-weekday {
        color: var(--fp-weekday);
        font-weight: 400;
        font-size: 0.8rem;
    }
    .flatpickr-day {
        color: var(--fp-text);
        border: 1px solid transparent;
        border-radius: 0.375rem; /* rounded-md */
        transition: background-color 0.15s;
    }
    .flatpickr-day:hover,
    .flatpickr-day:focus {
        background: var(--fp-hover);
    }
    .flatpickr-day.today {
        border-color: var(--fp-selected);
    }
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
        background: var(--fp-selected);
        border-color: var(--fp-selected);
        color: var(--fp-header-text);
    }

    /* Styles for booked dates */
    .flatpickr-day.booked {
        background-color: var(--fp-booked-bg) !important;
        color: var(--fp-booked-text) !important;
        border-color: var(--fp-booked-bg) !important;
    }
    .flatpickr-day.flatpickr-disabled.booked {
        background-color: #1e40af !important;
        color: #6b7280 !important;
    }

    .flatpickr-day.prevMonthDay,
    .flatpickr-day.nextMonthDay {
        color: #52525b; /* Zinc 600 */
    }
    .numInput, .flatpickr-current-month input.cur-year {
        color: inherit;
        background: transparent;
        font-weight: 600;
    }
    .flatpickr-current-month input.cur-year:hover {
        background: var(--fp-hover);
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="p-8 sm:p-10">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Formulir Peminjaman Sarpras</h1>
                    <p class="text-gray-500 mt-2">Isi detail di bawah untuk mengajukan peminjaman.</p>
                </div>

                <form action="{{ route('public.peminjaman.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Informasi Peminjam -->
                    <div class="border border-gray-200 p-6 rounded-lg">
                        <div class="flex items-center mb-4">
                            <svg class="h-6 w-6 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-800">Informasi Peminjam</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama_peminjam" class="block text-sm font-medium text-gray-600">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama"
                                       value="{{ Auth::user()->nama ?? old('nama') }}"
                                       class="mt-1 block w-full rounded-md border-gray-200 bg-gray-100 shadow-sm focus:border-gray-300 focus:ring-0 cursor-not-allowed"
                                       readonly>
                            </div>

                            <div>
                                <label for="email_peminjam" class="block text-sm font-medium text-gray-600">Alamat Email</label>
                                <input type="email" name="email" id="email"
                                       value="{{ Auth::user()->userRole->nama_role !== ['Mahasiswa', 'Dosen'] ? Auth::user()->email : '' ?? old('email') }}"
                                       class="mt-1 block w-full rounded-md border-gray-200 bg-gray-100 shadow-sm focus:border-gray-300 focus:ring-0 cursor-not-allowed"
                                       readonly>
                            </div>

                            <div class="md:col-span-2">
                                <label for="telepon_peminjam" class="block text-sm font-medium text-gray-600">Nomor Telepon (WhatsApp)</label>
                                <input type="text" name="telepon_peminjam" id="telepon_peminjam" value="{{ old('telepon_peminjam') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Contoh: 081234567890" required>
                                @error('telepon_peminjam')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Peminjaman -->
                    <div class="border border-gray-200 p-6 rounded-lg">
                        <div class="flex items-center mb-4">
                            <svg class="h-6 w-6 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0h18M-7.5 12h1.5m3 0h1.5m3 0h1.5m-7.5 4.5h1.5m3 0h1.5m3 0h1.5" />
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-800">Detail Peminjaman</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="id_sarpras" class="block text-sm font-medium text-gray-600">Sarana/Prasarana yang Dipinjam</label>
                                {{-- Data jadwal ditambahkan di sini --}}
                                <select name="id_sarpras" id="id_sarpras" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required data-jadwal="{{ json_encode($jadwalPeminjaman ?? []) }}">
                                    <option value="">Pilih Sarpras untuk melihat jadwal</option>
                                    @foreach($sarprasTersedia as $sarpras)
                                        <option value="{{ $sarpras->id_sarpras }}" {{ old('id_sarpras') == $sarpras->id_sarpras ? 'selected' : '' }}>
                                            {{ $sarpras->nama_sarpras }} ({{ $sarpras->jenis }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_sarpras')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-600 mb-1">Tanggal Pinjam</label>
                                <div class="relative">
                                    {{-- TAMBAHKAN 'readonly' DI SINI --}}
                                    <input type="text" name="tanggal_pinjam" id="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}"
                                    class="input-calendar-trigger pl-10 pr-4 py-2 @error('tanggal_pinjam') input-error @enderror" placeholder="Pilih tanggal..." readonly>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="text-gray-400">
                                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                            <line x1="16" x2="16" y1="2" y2="6" />
                                            <line x1="8" x2="8" y1="2" y2="6" />
                                            <line x1="3" x2="21" y1="10" y2="10" />
                                        </svg>
                                    </div>
                                </div>
                                @error('tanggal_pinjam')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_kembali" class="block text-sm font-medium text-gray-600 mb-1">Tanggal Kembali</label>
                                <div class="relative">
                                    {{-- TAMBAHKAN 'readonly' DI SINI --}}
                                    <input type="text" name="tanggal_kembali" id="tanggal_kembali" value="{{ old('tanggal_kembali') }}"
                                    class="input-calendar-trigger pl-10 pr-4 py-2 @error('tanggal_kembali') input-error @enderror" placeholder="Pilih tanggal..." readonly>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="text-gray-400">
                                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                            <line x1="16" x2="16" y1="2" y2="6" />
                                            <line x1="8" x2="8" y1="2" y2="6" />
                                            <line x1="3" x2="21" y1="10" y2="10" />
                                        </svg>
                                    </div>
                                </div>
                                @error('tanggal_kembali')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="jam_mulai" class="block text-sm font-medium text-gray-600">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @error('jam_mulai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="jam_selesai" class="block text-sm font-medium text-gray-600">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @error('jam_selesai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="keterangan" class="block text-sm font-medium text-gray-600">Keterangan/Tujuan Peminjaman</label>
                                <textarea name="keterangan" id="keterangan" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Digunakan untuk kegiatan rapat Himpunan Mahasiswa...">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4 space-x-3">
                        <a href="{{ route('public.beranda.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold transition duration-200 text-sm">
                            Batal
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 text-sm">
                            Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- Memuat file script kustom kita --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Menargetkan elemen-elemen penting pada form
    const sarprasSelect = document.getElementById('id_sarpras');
    const tanggalPinjamInput = document.getElementById('tanggal_pinjam');
    const tanggalKembaliInput = document.getElementById('tanggal_kembali');

    // Jika elemen-elemen form tidak ditemukan di halaman ini, hentikan eksekusi script
    if (!sarprasSelect || !tanggalPinjamInput || !tanggalKembaliInput) {
        return;
    }

    // Ambil data jadwal yang sudah disematkan pada elemen select sebagai data attribute
    const jadwal = JSON.parse(sarprasSelect.dataset.jadwal || '{}');

    let pinjamDatepicker = null;
    let kembaliDatepicker = null;

    /**
     * Fungsi untuk menginisialisasi atau menginisialisasi ulang kalender (flatpickr)
     * @param {string[]} bookedDates - Array berisi tanggal-tanggal yang sudah dipesan (format Y-m-d)
     */
    function initializeDatepickers(bookedDates = []) {
        // Hancurkan instance kalender sebelumnya agar tidak tumpang tindih
        if (pinjamDatepicker) pinjamDatepicker.destroy();
        if (kembaliDatepicker) kembaliDatepicker.destroy();

        // Opsi konfigurasi umum untuk kedua kalender
        const commonOptions = {
            altInput: true,
            altFormat: "j F Y", // Format yang terlihat oleh pengguna (e.g., 13 Oktober 2025)
            dateFormat: "Y-m-d", // Format yang dikirim ke server (e.g., 2025-10-13)
            minDate: "today", // Tanggal paling awal yang bisa dipilih adalah hari ini

            // Fungsi yang dijalankan saat setiap tanggal di kalender dibuat
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const dateStr = fp.formatDate(dayElem.dateObj, "Y-m-d");
                // Jika tanggal ini ada di daftar tanggal yang dipesan, tambahkan class 'booked'
                if (bookedDates.includes(dateStr)) {
                    dayElem.classList.add("booked");
                    dayElem.title = "Sudah dipinjam";
                }
            },
            // Menonaktifkan tanggal yang ada di dalam array `bookedDates`
            disable: bookedDates
        };

        // Inisialisasi kalender untuk 'Tanggal Pinjam'
        pinjamDatepicker = flatpickr(tanggalPinjamInput, {
            ...commonOptions,
            onChange: function(selectedDates, dateStr, instance) {
                // Jika tanggal pinjam diubah, atur tanggal minimal untuk kalender 'Tanggal Kembali'
                if (kembaliDatepicker) {
                    kembaliDatepicker.set('minDate', dateStr || 'today');
                }
            },
        });

        // Inisialisasi kalender untuk 'Tanggal Kembali'
        kembaliDatepicker = flatpickr(tanggalKembaliInput, {
            ...commonOptions,
            // Atur tanggal minimal berdasarkan 'Tanggal Pinjam' yang sudah dipilih
            minDate: pinjamDatepicker.selectedDates[0] ? pinjamDatepicker.formatDate(pinjamDatepicker.selectedDates[0], "Y-m-d") : "today"
        });
    }

    // Tambahkan event listener saat pilihan sarpras berubah
    sarprasSelect.addEventListener('change', function() {
        const selectedSarprasId = this.value;
        const bookedDatesForSelected = jadwal[selectedSarprasId] || [];
        // Perbarui kalender dengan jadwal untuk sarpras yang baru dipilih
        initializeDatepickers(bookedDatesForSelected);
    });

    // Inisialisasi kalender saat halaman pertama kali dimuat
    const initialSarprasId = sarprasSelect.value;
    if (initialSarprasId) {
        const initialBookedDates = jadwal[initialSarprasId] || [];
        initializeDatepickers(initialBookedDates);
    } else {
        // Jika belum ada sarpras yang dipilih, inisialisasi kalender kosong
        initializeDatepickers([]);
    }
});
</script>
@endpush

