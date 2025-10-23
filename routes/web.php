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
use App\Http\Controllers\PrioritasController;

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

// Edit & Delete peminjaman publik
Route::get('/peminjaman-public/{peminjaman}/edit', [PublicController::class, 'editPeminjaman'])
    ->name('public.peminjaman.edit');
Route::put('/peminjaman-public/{peminjaman}', [PublicController::class, 'updatePeminjaman'])
    ->name('public.peminjaman.update');
Route::delete('/peminjaman-public/{peminjaman}', [PublicController::class, 'destroyPeminjaman'])
    ->name('public.peminjaman.destroy');

// Sarana & Prasarana (Publik)
Route::get('/halaman-sarpras', [PublicController::class, 'halamansarpras'])
    ->name('public.user.halamansarpras');
Route::get('/sarpras/{id}', [PublicController::class, 'detail_sarpras'])
    ->name('public.user.detail_sarpras');

// Autentikasi
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [LoginController::class, 'register']);

// Laporan publik
Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
    Route::get('/pdf', [LaporanController::class, 'exportPdf'])->name('Pdf');
    Route::get('/excel', [LaporanController::class, 'exportExcel'])->name('xlsx');
});

// Logout (hanya untuk user login)
Route::middleware(['auth'])->post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rute untuk Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard.index');

    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

        // Akun
        Route::resource('akun', AkunController::class)->names('admin.akun');

        // Sarpras
        Route::get('/sarpras', [SarprasController::class, 'index'])->name('admin.sarpras.index');
        Route::get('/sarpras/tambah_sarpras', [SarprasController::class, 'tambah_sarpras'])->name('admin.sarpras.tambah_sarpras');
        Route::post('/sarpras', [SarprasController::class, 'store'])->name('admin.sarpras.store');
        Route::get('/sarpras/{sarpras}/lihat_sarpras', [SarprasController::class, 'lihat_sarpras'])->name('admin.sarpras.lihat_sarpras');
        Route::get('/sarpras/{sarpras}/edit_sarpras', [SarprasController::class, 'edit_sarpras'])->name('admin.sarpras.edit_sarpras');
        Route::put('/sarpras/{sarpras}/update', [SarprasController::class, 'update'])->name('admin.sarpras.update');
        Route::delete('/sarpras/{sarpras}/destroy', [SarprasController::class, 'destroy'])->name('admin.sarpras.destroy');

        // Peminjaman
        Route::get('/peminjaman/index', [PeminjamanController::class, 'index'])->name('admin.peminjaman.index');
        Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('admin.peminjaman.riwayat');
        Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('admin.peminjaman.show');
        Route::patch('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::patch('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::patch('/peminjaman/{id}/complete', [PeminjamanController::class, 'complete'])->name('peminjaman.complete');

        // Prioritas dan Jadwal
        Route::prefix('prioritas')->group(function () {
            Route::get('/proyektor', [PrioritasController::class, 'indexProyektor'])->name('admin.prioritas.proyektor');
            Route::get('/ruangan', [PrioritasController::class, 'indexRuangan'])->name('admin.prioritas.ruangan');
            Route::post('/proyektor/hitung', [PrioritasController::class, 'hitungProyektor'])->name('admin.prioritas.proyektor.hitung');
            Route::post('/ruangan/hitung', [PrioritasController::class, 'hitungRuangan'])->name('admin.prioritas.ruangan.hitung');

            Route::resource('jadwal', JadwalController::class)->except(['show']);
            Route::post('/jadwal/import', [JadwalController::class, 'importStore'])->name('jadwal.import.store');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Rute untuk Dosen dan Mahasiswa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Dosen,Mahasiswa'])->group(function () {
    Route::get('/beranda/index', [PublicController::class, 'index'])->name('public.beranda.index.auth');

    Route::get('/peminjaman/create', [PublicController::class, 'createPeminjaman'])->name('public.peminjaman.create.auth');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('public.peminjaman.store.auth');

    Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
});
