# Sistem Informasi Sarana dan Prasarana (SIMSARPRAS)

Sistem Informasi Manajemen Sarana dan Prasarana adalah aplikasi berbasis web yang dibangun menggunakan framework Laravel. Sistem ini dirancang untuk memudahkan pengelolaan, peminjaman, dan penjadwalan fasilitas (ruangan dan proyektor) di lingkungan kampus/instansi.

## Fitur Utama

### 1. Manajemen Pengguna (User Management)
- **Multi-Role**: Mendukung peran **Admin**, **Dosen**, dan **Mahasiswa**.
- **Otentikasi Aman**: Login, Register, Verifikasi Email, dan Reset Password menggunakan OTP.
- **Login Sosial**: Mendukung login menggunakan akun Google.
- **Profil Pengguna**: Pengguna dapat mengelola profil pribadi mereka.
- **Manajemen Akun (Admin)**: Admin dapat menambah, mengubah, dan menghapus akun pengguna.

### 2. Manajemen Sarana & Prasarana
- **Kelola Aset**: Admin dapat mengelola data Ruangan dan Proyektor (Tambah, Edit, Hapus, Lihat Detail).
- **Status Ketersediaan**: Pemantauan status fasilitas secara real-time.
- **Pencarian & Filter**: Memudahkan pencarian fasilitas berdasarkan kriteria tertentu.

### 3. Sistem Peminjaman (Booking System)
- **Pengajuan Peminjaman**: Formulir peminjaman yang mudah digunakan untuk Dosen dan Mahasiswa.
- **Alur Persetujuan**: Admin dapat menyetujui, menolak, atau menandai peminjaman sebagai selesai.
- **Cek Ketersediaan**: Sistem otomatis mengecek ketersediaan jadwal untuk mencegah bentrok.
- **Riwayat Peminjaman**: Pengguna dapat melihat status dan riwayat peminjaman mereka.
- **Catatan Admin**: Admin dapat memberikan catatan khusus pada setiap peminjaman.

### 4. Sistem Pendukung Keputusan (SPK) & Prioritas
- **Prioritas Peminjaman**: Sistem membantu menentukan prioritas peminjaman fasilitas (Ruangan & Proyektor) menggunakan metode SPK (seperti AHP/SAW).
- **Manajemen Kriteria**: Admin dapat mengatur kriteria dan bobot penilaian prioritas.
- **Perhitungan Otomatis**: Kalkulasi otomatis untuk merekomendasikan persetujuan peminjaman.

### 5. Penjadwalan (Scheduling)
- **Manajemen Jadwal**: Pengelolaan jadwal penggunaan ruangan/proyektor rutin.
- **Import/Export**: Fitur untuk import dan export data jadwal (Excel).

### 6. Pelaporan & Laporan (Reporting)
- **Laporan Peminjaman**: Admin dapat mencetak laporan peminjaman dalam format PDF dan Excel.
- **Riwayat Ekspor**: Pengguna dapat mengunduh riwayat peminjaman mereka sendiri.

### 7. Feedback & Notifikasi
- **Feedback**: Pengguna dapat memberikan masukan atau umpan balik terkait fasilitas.
- **Notifikasi**: Sistem notifikasi (Email/WhatsApp - *tergantung konfigurasi*) untuk status peminjaman.

## Teknologi yang Digunakan

- **Backend**: Laravel Framework (PHP)
- **Frontend**: Blade Templates, Tailwind CSS
- **Database**: MySQL / MariaDB
- **Authentication**: Laravel UI / Custom Auth
- **Fitur Lain**: DomPDF (untuk PDF), Maatwebsite Excel (untuk Excel), Socialite (untuk Google Login).

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL Database

## Cara Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/sistem-sarpras.git
   cd sistem-sarpras
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database serta kredensial lainnya (Google Client ID, Mail Server, dll).
   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi & Seeding Database**
   ```bash
   php artisan migrate --seed
   ```

6. **Build Frontend Assets**
   ```bash
   npm run build
   ```
   *(Atau `npm run dev` untuk mode pengembangan)*

7. **Jalankan Server**
   ```bash
   php artisan serve
   ```

Akses aplikasi melalui browser di `http://localhost:8000`.

## Lisensi

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
Aplikasi ini dikembangkan untuk kebutuhan manajemen internal dan mengikuti lisensi yang berlaku pada framework yang digunakan.