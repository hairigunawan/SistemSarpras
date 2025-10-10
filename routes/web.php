<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\PublicController; // Asumsi ada controller untuk halaman publik
use App\Http\Controllers\DashboardPeminjamanController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Rute untuk Publik (Tidak Perlu Login)
|--------------------------------------------------------------------------
| Siapa pun bisa mengakses rute ini.
*/

Route::get('/', [PublicController::class, 'index'])->name('public.branda.index');

// Rute untuk form peminjaman publik
// Formulir peminjaman publik
Route::get('/peminjaman-public/create', [PublicController::class, 'createPeminjaman'])
    ->name('public.peminjaman.create');

// Proses penyimpanan data peminjaman publik
Route::post('/peminjaman-public', [PublicController::class, 'storePeminjaman'])
    ->name('public.peminjaman.store');

Route::get('/peminjaman-public/daftar', [PublicController::class, 'daftarPeminjaman'])
    ->name('public.peminjaman.daftarpeminjaman');


Route::get('/sarana-prasarana', [PublicController::class, 'saranaPrasarana'])
    ->name('user.sarpras');


// Rute untuk autentikasi sosial
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

// Rute untuk autentikasi login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


//Laporan
Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/export/pdf', [App\Http\Controllers\LaporanController::class, 'exportPdf'])->name('laporan.pdf');
Route::get('/laporan/export/excel', [App\Http\Controllers\LaporanController::class, 'exportExcel'])->name('laporan.excel');


Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
    Route::get('/pdf', [LaporanController::class, 'exportPdf'])->name('Pdf');
    Route::get('/excel', [LaporanController::class, 'exportExcel'])->name('xlsx');
});


// Rute register kustom (non-Fortify)
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [LoginController::class, 'register']);

/*
|--------------------------------------------------------------------------
| Rute Autentikasi (Dibuat oleh Laravel Breeze/Fortify)
|--------------------------------------------------------------------------
| Halaman login, register, dll. Biasanya sudah diatur otomatis.
*/
// require __DIR__.'/auth.php'; // Aktifkan jika Anda menggunakan Breeze


/*
|--------------------------------------------------------------------------
| Rute untuk SEMUA PENGGUNA YANG SUDAH LOGIN
|--------------------------------------------------------------------------
| Cukup login, tidak peduli apa perannya.
*/
Route::middleware(['auth'])->group(function () {
    // Contoh: Halaman profil yang bisa diakses semua peran
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

/*
|--------------------------------------------------------------------------
| Rute untuk MANAJEMEN (Admin & Staff)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.index');
    Route::resource('/akun', AkunController::class)->names('admin.akun');

    Route::get('/sarpras', [SarprasController::class, 'index'])->name('admin.sarpras.index');
    // Menampilkan form tambah sarpras
    Route::get('/sarpras/tambah_sarpras', [SarprasController::class, 'tambah_sarpras'])->name('admin.sarpras.tambah_sarpras');
    // Menyimpan data sarpras baru
    Route::post('/sarpras', [SarprasController::class, 'store'])->name('admin.sarpras.store');
    // Menampilkan detail sarpras berdasarkan ID
    Route::get('/sarpras/{id}/lihat_sarpras', [SarprasController::class, 'lihat_sarpras'])->name('admin.sarpras.lihat_sarpras');
    // Menampilkan form edit sarpras
    Route::get('/sarpras/{id}/edit_sarpras', [SarprasController::class, 'edit_sarpras'])->name('admin.sarpras.edit_sarpras');
    // Memperbarui data sarpras
    Route::put('/sarpras/{id}', [SarprasController::class, 'update'])->name('admin.sarpras.update');
    // Menghapus data sarpras
    Route::delete('/sarpras/{id}', [SarprasController::class, 'destroy'])->name('admin.sarpras.destroy');

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::patch('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::patch('/peminjaman/{id}/complete', [PeminjamanController::class, 'complete'])->name('peminjaman.complete');
});


/*
|--------------------------------------------------------------------------
| Rute untuk PEMINJAM (Dosen & Mahasiswa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Dosen,Mahasiswa'])->group(function () {
    Route::get('/beranda/index', [PublicController::class, 'index'])->name('public.beranda.index');

    // Form untuk mengajukan peminjaman
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('public.peminjaman.create');
    // Proses pengiriman form
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('public.peminjaman.store');
    // Melihat riwayat peminjaman PRIBADI

    Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
});

    Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('public.peminjaman.riwayat');
