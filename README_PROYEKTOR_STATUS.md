# Perbaikan Status Proyektor yang Tersedia Padahal Sudah Dipinjam

## Masalah
Proyektor masih menampilkan status "Tersedia" padahal sudah ada peminjaman aktif yang disetujui.

## Solusi
Dibuatkan helper class `ProyektorStatusHelper` untuk memperbarui status proyektor secara otomatis berdasarkan peminjaman aktif.

### File yang Dibuat/Dimodifikasi:

1. **app/Helpers/ProyektorStatusHelper.php**
   - Berisi fungsi untuk memperbarui status proyektor
   - `updateProyektorStatus()`: Memperbarui status semua proyektor
   - `checkProyektorStatus($idProyektor)`: Memperbarui status proyektor tertentu

2. **app/Console/Commands/UpdateProyektorStatus.php**
   - Command artisan untuk memperbarui status proyektor manual
   - Cara penggunaan: `php artisan proyektor:update-status`

3. **Controller yang Dimodifikasi:**
   - `app/Http/Controllers/ProyektorController.php` (method `lihat_proyektor`)
   - `app/Http/Controllers/SarprasController.php` (method `index`)
   - `app/Http/Controllers/AdminController.php` (method `dashboard`)
   - `app/Http/Controllers/PublicController.php` (method `create`, `createPeminjaman`, `halamansarpras`, `detail_sarpras`)

### Cara Penggunaan:

1. **Otomatis (disarankan):**
   - Status proyektor akan diperbarui otomatis setiap kali halaman yang terkait dibuka
   - Tidak perlu interaksi manual

2. **Manual (jika diperlukan):**
   - Jalankan command artisan: `php artisan proyektor:update-status`
   - Ini akan memperbarui status semua proyektor berdasarkan peminjaman aktif

### Logika Kerja:
1. Mengecek apakah ada peminjaman dengan status 'Disetujui' atau 'Dipinjam' untuk proyektor tertentu
2. Jika ada peminjaman aktif (tanggal kembali >= hari ini atau tanggal kembali = hari ini tapi jam selesai >= jam sekarang), status proyektor diubah menjadi 'Dipakai'
3. Jika tidak ada peminjaman aktif, status proyektor diubah menjadi 'Tersedia'

### Status Peminjaman yang Dianggap Aktif:
- 'Disetujui'
- 'Dipinjam'

### Status Proyektor:
- 'Tersedia' - Tidak ada peminjaman aktif
- 'Dipakai' - Ada peminjaman aktif
- 'Diperbaiki' - Status manual dari admin
- 'Tidak Tersedia' - Status manual dari admin
