<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== TEST REGISTRATION PROCESS ===\n\n";

// Simulasi data dari form registrasi
$testData = [
    'nama' => 'Test User',
    'email' => 'test@example.com',
    'nomor_telepon' => '081234567890',
    'role' => 'Dosen',
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

echo "Data uji registrasi:\n";
print_r($testData);
echo "\n";

// 1. Validasi data
echo "=== VALIDASI DATA ===\n";
$validationErrors = [];

// Validasi nama
if (empty($testData['nama'])) {
    $validationErrors['nama'] = 'Nama wajib diisi';
}

// Validasi email
if (empty($testData['email'])) {
    $validationErrors['email'] = 'Email wajib diisi';
} elseif (!filter_var($testData['email'], FILTER_VALIDATE_EMAIL)) {
    $validationErrors['email'] = 'Email tidak valid';
} elseif (User::where('email', $testData['email'])->exists()) {
    $validationErrors['email'] = 'Email sudah terdaftar';
}

// Validasi nomor telepon
if (empty($testData['nomor_telepon'])) {
    $validationErrors['nomor_telepon'] = 'Nomor telepon wajib diisi';
} elseif (!preg_match('/^08[0-9]{8,12}$/', $testData['nomor_telepon'])) {
    $validationErrors['nomor_telepon'] = 'Nomor telepon harus dimulai dengan 08 dan 10-13 digit';
} elseif (User::where('nomor_telepon', $testData['nomor_telepon'])->exists()) {
    $validationErrors['nomor_telepon'] = 'Nomor telepon sudah terdaftar';
}

// Validasi role
if (empty($testData['role'])) {
    $validationErrors['role'] = 'Role wajib dipilih';
} elseif (!in_array($testData['role'], ['Dosen', 'Mahasiswa'])) {
    $validationErrors['role'] = 'Role tidak valid';
}

// Validasi password
if (empty($testData['password'])) {
    $validationErrors['password'] = 'Password wajib diisi';
} elseif (strlen($testData['password']) < 8) {
    $validationErrors['password'] = 'Password minimal 8 karakter';
} elseif ($testData['password'] !== $testData['password_confirmation']) {
    $validationErrors['password'] = 'Password konfirmasi tidak cocok';
}

if (!empty($validationErrors)) {
    echo "✗ Validasi gagal:\n";
    foreach ($validationErrors as $field => $error) {
        echo "  {$field}: {$error}\n";
    }
    echo "\n";
} else {
    echo "✓ Validasi berhasil\n\n";
}

// 2. Cek role
echo "=== CEK ROLE ===\n";
$role = Role::where('nama_role', $testData['role'])->first();
if (!$role) {
    echo "✗ Role tidak ditemukan: {$testData['role']}\n\n";
    exit;
}
echo "✓ Role ditemukan: ID {$role->id_role}, Nama: {$role->nama_role}\n\n";

// 3. Coba buat user
echo "=== MENCoba BUAT USER ===\n";
try {
    // Mulai transaksi
    DB::beginTransaction();
    
    $user = User::create([
        'nama' => $testData['nama'],
        'email' => $testData['email'],
        'nomor_telepon' => $testData['nomor_telepon'],
        'password' => Hash::make($testData['password']),
        'role_id' => $role->id_role,
    ]);
    
    echo "✓ User berhasil dibuat:\n";
    echo "  ID: {$user->id_akun}\n";
    echo "  Nama: {$user->nama}\n";
    echo "  Email: {$user->email}\n";
    echo "  Nomor Telepon: {$user->nomor_telepon}\n";
    echo "  Role ID: {$user->role_id}\n";
    
    // Cek relasi role
    echo "  Role: " . ($user->userRole ? $user->userRole->nama_role : 'Tidak ditemukan') . "\n";
    
    // Commit transaksi
    DB::commit();
    echo "\n✓ Transaksi berhasil di-commit\n";
    
    // Hapus user setelah test
    $user->delete();
    echo "✓ User dihapus setelah test\n";
    
} catch (Exception $e) {
    // Rollback transaksi
    DB::rollBack();
    echo "\n✗ Error saat membuat user: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    // Cek detail error
    echo "\nDetail error:\n";
    echo "Error code: " . $e->getCode() . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    
    // Cek constraint violation
    if (strpos($e->getMessage(), 'Integrity constraint violation') !== false) {
        echo "\n⚠️  Kemungkinan foreign key constraint violation\n";
        echo "Cek apakah role_id valid ada di tabel roles\n";
    }
}

echo "\n=== SELESAI ===\n";