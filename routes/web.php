<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\UserInventoryController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardPeminjamanController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\JadwalController;

/*
|--------------------------------------------------------------------------
| Rute untuk Publik (Tidak Perlu Login)
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'index'])->name('public.beranda.index');

// Form peminjaman publik
Route::get('/peminjaman-public/create', [PublicController::class, 'createPeminjaman'])
    ->name('public.peminjaman.create');
Route::post('/peminjaman-public', [PublicController::class, 'storePeminjaman'])
    ->name('public.peminjaman.store');
Route::get('/peminjaman-public/daftar', [PublicController::class, 'daftarPeminjaman'])
    ->name('public.peminjaman.daftarpeminjaman');

// Sarana & Prasarana (Publik)
Route::get('/sarana-prasarana', [UserInventoryController::class, 'index'])
    ->name('public.user.halamansarpras');
Route::get('/sarana-prasarana/{id}', [UserInventoryController::class, 'show'])
    ->name('public.user.halamansarpras.show');

// Autentikasi
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register manual
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [LoginController::class, 'register']);

// Laporan publik (kalau perlu)
Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
    Route::get('/pdf', [LaporanController::class, 'exportPdf'])->name('Pdf');
    Route::get('/excel', [LaporanController::class, 'exportExcel'])->name('xlsx');
});

/*
|--------------------------------------------------------------------------
| Rute untuk Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.index');
    Route::resource('/akun', AkunController::class)->names('admin.akun');

    Route::get('/sarpras', [SarprasController::class, 'index'])->name('admin.sarpras.index');
    Route::get('/sarpras/tambah_sarpras', [SarprasController::class, 'tambah_sarpras'])->name('admin.sarpras.tambah_sarpras');
    Route::post('/sarpras', [SarprasController::class, 'store'])->name('admin.sarpras.store');
    Route::get('/sarpras/{id}/lihat_sarpras', [SarprasController::class, 'lihat_sarpras'])->name('admin.sarpras.lihat_sarpras');
    Route::get('/sarpras/{id}/edit_sarpras', [SarprasController::class, 'edit_sarpras'])->name('admin.sarpras.edit_sarpras');
    Route::put('/sarpras/{id}', [SarprasController::class, 'update'])->name('admin.sarpras.update');
    Route::delete('/sarpras/{id}', [SarprasController::class, 'destroy'])->name('admin.sarpras.destroy');

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::patch('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::patch('/peminjaman/{id}/complete', [PeminjamanController::class, 'complete'])->name('peminjaman.complete');
});

/*
|--------------------------------------------------------------------------
| Rute untuk Dosen & Mahasiswa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Dosen,Mahasiswa'])->group(function () {
    // Halaman beranda khusus user login (kalau berbeda)
    Route::get('/beranda/index', [PublicController::class, 'index'])
        ->name('public.beranda.index.auth');

    // Form peminjaman
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('public.peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('public.peminjaman.store');

    // Riwayat peminjaman pribadi
    Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
});

Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('public.peminjaman.riwayat');

// Jadwal
Route::middleware(['auth'])->group(function () {
    Route::resource('jadwal', JadwalController::class);
});
