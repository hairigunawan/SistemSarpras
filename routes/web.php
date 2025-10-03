<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\PublicController; // Asumsi ada controller untuk halaman publik

/*
|--------------------------------------------------------------------------
| Rute untuk Publik (Tidak Perlu Login)
|--------------------------------------------------------------------------
| Siapa pun bisa mengakses rute ini.
*/

Route::get('/', function () {
    // Arahkan ke halaman landing, BUKAN dashboard admin
    return view('public.landing');
})->name('landing');

// Rute untuk form peminjaman publik
Route::get('/peminjaman-public', [PublicController::class, 'createPeminjaman'])->name('public.peminjaman.create');
Route::post('/peminjaman-public', [PublicController::class, 'storePeminjaman'])->name('public.peminjaman.store');


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
| Rute KHUSUS ADMIN
|--------------------------------------------------------------------------
| Hanya pengguna dengan peran 'Admin' yang bisa mengakses ini.
*/
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('/akun', AkunController::class)->names('admin.akun');
});


/*
|--------------------------------------------------------------------------
| Rute untuk MANAJEMEN (Admin & Staff)
|--------------------------------------------------------------------------
| Hanya 'Admin' dan 'Staff' yang bisa mengakses ini.
*/
Route::middleware(['auth', 'role:Admin,Staff'])->group(function () {
    // Mengelola semua data sarpras
    Route::resource('/sarpras', SarprasController::class);
    // Melihat SEMUA peminjaman untuk approval
    Route::get('/inventory', [SarprasController::class, 'inventory'])->name('sarpras.inventory');

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::patch('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
});


/*
|--------------------------------------------------------------------------
| Rute untuk PEMINJAM (Dosen & Mahasiswa)
|--------------------------------------------------------------------------
| Hanya 'Dosen' dan 'Mahasiswa' yang bisa mengakses ini.
*/
Route::middleware(['auth', 'role:Dosen,Mahasiswa'])->group(function () {
    // Form untuk mengajukan peminjaman
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    // Proses pengiriman form
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    // Melihat riwayat peminjaman PRIBADI
    // Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
});
