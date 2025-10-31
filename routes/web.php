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
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\ProyektorController;

/*
|--------------------------------------------------------------------------
| Rute untuk Publik (Tidak Perlu Login)
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'index'])->name('public.beranda.index');

// Form peminjaman publik
Route::get('/peminjaman-public/create', [PublicController::class, 'createPeminjaman'])->name('public.peminjaman.create');
    Route::post('/peminjaman-public', [PublicController::class, 'storePeminjaman'])->name('public.peminjaman.store');
    Route::get('/peminjaman-public/daftar', [PublicController::class, 'daftarPeminjaman'])->name('public.peminjaman.daftarpeminjaman');

Route::get('/halaman-sarpras', [PublicController::class, 'halamansarpras'])->name('public.user.halamansarpras');
    Route::get('/detail-ruangan/{ruangan}', [PublicController::class, 'detailRuangan'])->name('public.user.detail_ruangan');
    Route::get('/detail-proyektor/{proyektor}', [PublicController::class, 'detailProyektor'])->name('public.user.detail_proyektor');

// Edit & Delete peminjaman publik
Route::get('/peminjaman-public/{peminjaman}/edit', [PublicController::class, 'editPeminjaman'])->name('public.peminjaman.edit');
    Route::put('/peminjaman-public/{peminjaman}', [PublicController::class, 'updatePeminjaman'])->name('public.peminjaman.update');
    Route::delete('/peminjaman-public/{peminjaman}', [PublicController::class, 'destroyPeminjaman'])->name('public.peminjaman.destroy');


// Autentikasi
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [LoginController::class, 'register']);



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

        // Laporan publik
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
            Route::get('/excel', [LaporanController::class, 'exportExcel'])->name('excel');
        });

        // Akun
        Route::resource('akun', AkunController::class)->names('admin.akun');


        // Peminjaman
        Route::get('/peminjaman/index', [PeminjamanController::class, 'index'])->name('admin.peminjaman.index');
        Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('admin.peminjaman.riwayat');
        Route::get('/peminjaman/{id}', [PeminjamanController::class, 'lihat_peminjaman'])->name('admin.peminjaman.lihat_peminjaman');
        Route::patch('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::patch('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::patch('/peminjaman/{id}/complete', [PeminjamanController::class, 'complete'])->name('peminjaman.complete');

        // Prioritas dan Jadwal
        Route::prefix('prioritas')->group(function () {
            Route::get('/proyektor', [PrioritasController::class, 'indexProyektor'])->name('admin.prioritas.proyektor');
            Route::get('/ruangan', [PrioritasController::class, 'indexRuangan'])->name('admin.prioritas.ruangan');
            Route::post('/proyektor/hitung', [PrioritasController::class, 'hitungProyektor'])->name('admin.prioritas.proyektor.hitung');
            Route::post('/ruangan/hitung', [PrioritasController::class, 'hitungRuangan'])->name('admin.prioritas.ruangan.hitung');

            Route::get('/jadwal', [JadwalController::class, 'index'])->name('admin.jadwal.index');
            Route::resource('jadwal', JadwalController::class)->except(['show'])->names('admin.jadwal');
            Route::post('/jadwal/import', [JadwalController::class, 'importStore'])->name('admin.jadwal.import.store');
            Route::get('/jadwal/export', [JadwalController::class, 'export'])->name('admin.jadwal.export');
        });

        Route::get('/sarpras', [SarprasController::class, 'index'])->name('sarpras.index');
            Route::get('/sarpras/ruangan/create', [RuanganController::class, 'tambah_ruangan'])->name('sarpras.ruangan.tambah_ruangan');
            Route::post('/sarpras/ruangan', [RuanganController::class, 'store'])->name('sarpras.ruangan.store');
            Route::get('/sarpras/ruangan/{ruangan}', [RuanganController::class, 'lihat_ruangan'])->name('sarpras.ruangan.lihat_ruangan');
            Route::get('/sarpras/ruangan/{ruangan}/edit', [RuanganController::class, 'edit_ruangan'])->name('sarpras.ruangan.edit_ruangan');
            Route::put('/sarpras/ruangan/{ruangan}', [RuanganController::class, 'update'])->name('sarpras.ruangan.update');
            Route::delete('/sarpras/ruangan/{ruangan}', [RuanganController::class, 'destroy'])->name('sarpras.ruangan.destroy');

            Route::get('/sarpras/proyektor', [ProyektorController::class, 'tambah_proyektor'])->name('sarpras.proyektor.tambah_proyektor');
            Route::post('/sarpras/proyektor', [ProyektorController::class, 'store'])->name('sarpras.proyektor.store');
            Route::get('/sarpras/proyektor/{proyektor}', [ProyektorController::class, 'lihat_proyektor'])->name('sarpras.proyektor.lihat_proyektor');
            Route::get('/sarpras/proyektor/{proyektor}/edit', [ProyektorController::class, 'edit_proyektor'])->name('sarpras.proyektor.edit_proyektor');
            Route::put('/sarpras/proyektor/{proyektor}', [ProyektorController::class, 'update'])->name('sarpras.proyektor.update');
            Route::delete('/sarpras/proyektor/{proyektor}', [ProyektorController::class, 'destroy'])->name('sarpras.proyektor.destroy');
        });
});

/*
|--------------------------------------------------------------------------
| Rute untuk Dosen dan Mahasiswa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Dosen,Mahasiswa'])->group(function () {
    Route::get('/beranda/index', [PublicController::class, 'index'])->name('public.beranda.index.auth');

    Route::get('/akun/profile', [PublicController::class, 'profile'])->name('public.profile');

    Route::get('/peminjaman/create', [PublicController::class, 'createPeminjaman'])->name('public.peminjaman.create.auth');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('public.peminjaman.store.auth');

    Route::get('/halaman-sarpras', [PublicController::class, 'halamansarpras'])->name('public.user.halamansarpras');
    Route::get('/detail-ruangan/{ruangan}', [PublicController::class, 'detailRuangan'])->name('public.user.detail_ruangan');
    Route::get('/detail-proyektor/{proyektor}', [PublicController::class, 'detailProyektor'])->name('public.user.detail_proyektor');

    Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
});
