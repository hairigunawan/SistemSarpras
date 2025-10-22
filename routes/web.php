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

// Routes for editing and deleting public peminjaman
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
| Rute untuk Semua User yang Sudah Login
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // PEMINDAHAN ROUTE LOGOUT KE SINI
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::resource('jadwal', JadwalController::class);
});


/*
|--------------------------------------------------------------------------
| Rute untuk Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard.index');

    Route::resource('/akun', AkunController::class)
        ->names('admin.akun');

    Route::get('/sarpras', [SarprasController::class, 'index'])
        ->name('admin.sarpras.index');

    Route::get('/sarpras/create', [SarprasController::class, 'tambah_sarpras'])
        ->name('admin.sarpras.tambah_sarpras');
    

    Route::post('/sarpras', [SarprasController::class, 'store'])
        ->name('admin.sarpras.store');

   // Lihat
    Route::get('/sarpras/{sarpras}/lihat_sarpras', [SarprasController::class, 'lihat_sarpras'])
        ->name('admin.sarpras.lihat_sarpras');

    // Edit
    Route::get('/sarpras/{sarpras}/edit_sarpras', [SarprasController::class, 'edit_sarpras'])
    ->name('admin.sarpras.edit_sarpras');

    // Update
    Route::put('/sarpras/{sarpras}/update', [SarprasController::class, 'update'])
    ->name('admin.sarpras.update');

    // Hapus
    Route::delete('/sarpras/{sarpras}/destroy', [SarprasController::class, 'destroy'])
    ->name('admin.sarpras.destroy');


    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    // PERBAIKAN: Mengubah metode dari POST menjadi PATCH agar sesuai dengan form
    Route::patch('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::patch('/peminjaman/{id}/complete', [PeminjamanController::class, 'complete'])->name('peminjaman.complete');

    
//prioritas
Route::prefix('admin/prioritas')->middleware(['web','auth','role:Admin'])->group(function () {
    // Halaman index (GET)
    Route::get('/proyektor', [PrioritasController::class, 'indexProyektor'])
        ->name('admin.prioritas.proyektor');

    Route::get('/ruangan', [PrioritasController::class, 'indexRuangan'])
        ->name('admin.prioritas.ruangan');

    // Aksi hitung (POST) -> langsung render hasil view
    Route::post('/proyektor/hitung', [PrioritasController::class, 'hitungProyektor'])
        ->name('admin.prioritas.proyektor.hitung');

    Route::post('/ruangan/hitung', [PrioritasController::class, 'hitungRuangan'])
        ->name('admin.prioritas.ruangan.hitung');
});

//jadwal
Route::middleware(['auth'])->group(function () {
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal/import', [JadwalController::class, 'importStore'])->name('jadwal.import.store');
});
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
    Route::get('/peminjaman/create', [PublicController::class, 'createPeminjaman'])->name('public.peminjaman.create.auth');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('public.peminjaman.store.auth');

    // Riwayat peminjaman pribadi
    Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
});

// Catatan: Route di bawah ini duplikat dengan yang di atas, mungkin bisa dihapus jika tidak diperlukan.
Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('public.peminjaman.riwayat');