<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PrioritasController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\ProyektorController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\FeedbackController;



Route::get('/', [PublicController::class, 'index'])->name('public.beranda.index');

// Form peminjaman publik
Route::get('/peminjaman-public/create', [PublicController::class, 'createPeminjaman'])->name('public.peminjaman.create');
    Route::post('/peminjaman-public', [PublicController::class, 'storePeminjaman'])->name('public.peminjaman.store');
    Route::get('/peminjaman-public/daftar', [PublicController::class, 'daftarPeminjaman'])->name('public.peminjaman.daftarpeminjaman');

Route::get('/public/halaman-sarpras', [PublicController::class, 'halamansarpras'])->name('public.sarana_perasarana.halamansarpras');
Route::get('public//halaman-sarpras/{type}/{id}', [PublicController::class, 'detail_sarpras'])
    ->name('public.sarana_perasarana.detail_sarpras');


// Autentikasi
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [LoginController::class, 'register']);

Route::middleware(['auth'])->post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::middleware(['auth', 'role:Admin'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard.index');

    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

        // Laporan publik
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/pdf', [LaporanController::class, 'exportPdf'])->name('pdf');
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

        // Prioritas
        Route::prefix('prioritas')->group(function () {
            Route::get('/proyektor', [PrioritasController::class, 'indexProyektor'])->name('admin.prioritas.proyektor');
            Route::get('/ruangan', [PrioritasController::class, 'indexRuangan'])->name('admin.prioritas.ruangan');
            Route::post('/proyektor/hitung', [PrioritasController::class, 'hitungProyektor'])->name('admin.prioritas.proyektor.hitung');
            Route::post('/ruangan/hitung', [PrioritasController::class, 'hitungRuangan'])->name('admin.prioritas.ruangan.hitung');

            //Jadwal
            Route::get('/jadwal', [JadwalController::class, 'index'])->name('admin.jadwal.index');
            Route::resource('jadwal', JadwalController::class)->except(['show'])->names('admin.jadwal');
            Route::post('/jadwal/import', [JadwalController::class, 'importStore'])->name('admin.jadwal.import.store');
            Route::get('/jadwal/export', [JadwalController::class, 'export'])->name('admin.jadwal.export');
        });

        //Sarpras
        Route::get('/sarpras', [SarprasController::class, 'index'])->name('admin.sarpras.index');

        //Ruangan
        Route::get('/sarpras/ruangan/create', [RuanganController::class, 'tambah_ruangan'])->name('sarpras.ruangan.tambah_ruangan');
        Route::post('/sarpras/ruangan/store', [RuanganController::class, 'store'])->name('sarpras.ruangan.store');
        Route::get('/sarpras/ruangan/{ruangan}', [RuanganController::class, 'lihat_ruangan'])->name('sarpras.ruangan.lihat_ruangan');
        Route::get('/sarpras/ruangan/{ruangan}/edit', [RuanganController::class, 'edit_ruangan'])->name('sarpras.ruangan.edit_ruangan');
        Route::put('/sarpras/ruangan/{ruangan}', [RuanganController::class, 'update'])->name('sarpras.ruangan.update');
        Route::delete('/sarpras/ruangan/{ruangan}', [RuanganController::class, 'destroy'])->name('sarpras.ruangan.destroy');

        //Proyektor
        Route::get('/sarpras/proyektor', [ProyektorController::class, 'tambah_proyektor'])->name('sarpras.proyektor.tambah_proyektor');
        Route::post('/sarpras/proyektor', [ProyektorController::class, 'store'])->name('sarpras.proyektor.store');
        Route::get('/sarpras/proyektor/{proyektor}', [ProyektorController::class, 'lihat_proyektor'])->name('sarpras.proyektor.lihat_proyektor');
        Route::get('/sarpras/proyektor/{proyektor}/edit', [ProyektorController::class, 'edit_proyektor'])->name('sarpras.proyektor.edit_proyektor');
        Route::put('/sarpras/proyektor/{proyektor}', [ProyektorController::class, 'update'])->name('sarpras.proyektor.update');
        Route::delete('/sarpras/proyektor/{proyektor}', [ProyektorController::class, 'destroy'])->name('sarpras.proyektor.destroy');
    });
});

Route::middleware(['auth', 'role:Dosen,Mahasiswa'])->group(function () {
    //Beranda
    Route::get('/beranda/index', [PublicController::class, 'index'])->name('public.beranda.index.auth');

    //Profile
    Route::get('/profile/profile', [PublicController::class, 'index'])->name('public.profile.index');

    //Peminjaman
    Route::get('/peminjaman/create', [PublicController::class, 'createPeminjaman'])->name('public.peminjaman.create.auth');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('public.peminjaman.store.auth');
    Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');

    //Sarana dan Perasarana
    Route::get('/halaman-sarpras', [PublicController::class, 'halamansarpras'])->name('public.sarana_perasarana.halamansarpras');
    Route::get('/sarpras/{type}/{id}', [PublicController::class, 'detail_sarpras'])
        ->name('public.sarana_perasarana.detail_sarpras');

    // Feedback
    Route::prefix('feedback')->name('public.feedback.')->group(function () {
        Route::get('/', [FeedbackController::class, 'index'])->name('index');
        Route::post('/store', [FeedbackController::class, 'store'])->name('store');
        Route::delete('/{feedback}', [FeedbackController::class, 'destroy'])->name('destroy');
    });
});
