<?php

use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Role;
use App\Models\Status;
use App\Models\Ruangan;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

uses(WithoutMiddleware::class);

beforeEach(function () {
    // Clean up
    DB::table('peminjamans')->truncate();
    DB::table('users')->truncate();
    DB::table('roles')->truncate();
    DB::table('statuses')->truncate();
    DB::table('ruangans')->truncate();
    DB::table('lokasis')->truncate();

    // Create Roles
    DB::table('roles')->insert([
        ['id_role' => 1, 'nama_role' => 'Admin'],
        ['id_role' => 2, 'nama_role' => 'Mahasiswa'],
    ]);

    // Create Statuses
    DB::table('statuses')->insert([
        ['id_status' => 1, 'nama_status' => 'Tersedia'],
        ['id_status' => 2, 'nama_status' => 'Dipakai'],
        ['id_status' => 3, 'nama_status' => 'Dipinjam'],
        ['id_status' => 4, 'nama_status' => 'Perbaikan'],
    ]);

    // Create Lokasi
    DB::table('lokasis')->insert([
        ['id_lokasi' => 1, 'nama_lokasi' => 'Gedung A'],
    ]);

    // Create Ruangan
    DB::table('ruangans')->insert([
        ['id_ruangan' => 1, 'nama_ruangan' => 'Ruang 101', 'id_status' => 1, 'lokasi_id' => 1, 'kapasitas' => 30],
    ]);

    // Create Admin User
    $this->admin = User::create([
        'nama' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role_id' => 1,
        'nomor_telepon' => '081234567890',
    ]);
});

test('admin can approve peminjaman on the same date', function () {
    $peminjaman = Peminjaman::create([
        'id_akun' => $this->admin->id_akun,
        'id_ruangan' => 1,
        'tanggal_pinjam' => now()->toDateString(),
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:00',
        'jumlah_peserta' => 10,
        'jenis_kegiatan' => 'Rapat',
        'status_peminjaman' => 'Menunggu',
        'nama_peminjam' => 'Admin Test',
        'email_peminjam' => 'admin@test.com',
    ]);

    $response = $this->actingAs($this->admin)
        ->patchJson(route('peminjaman.approve', $peminjaman->id_peminjaman));

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('peminjamans', [
        'id_peminjaman' => $peminjaman->id_peminjaman,
        'status_peminjaman' => 'Disetujui',
    ]);
});

test('admin cannot approve peminjaman on a future date', function () {
    $peminjaman = Peminjaman::create([
        'id_akun' => $this->admin->id_akun,
        'id_ruangan' => 1,
        'tanggal_pinjam' => now()->addDay()->toDateString(),
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:00',
        'jumlah_peserta' => 10,
        'jenis_kegiatan' => 'Rapat',
        'status_peminjaman' => 'Menunggu',
        'nama_peminjam' => 'Admin Test',
        'email_peminjam' => 'admin@test.com',
    ]);

    $response = $this->actingAs($this->admin)
        ->patchJson(route('peminjaman.approve', $peminjaman->id_peminjaman));

    $response->assertStatus(500)
        ->assertJson(['success' => false]);
    
    // Check if error message contains expected text
    expect($response->json('message'))->toContain('Peminjaman hanya dapat disetujui pada tanggal peminjaman (hari ini).');

    $this->assertDatabaseHas('peminjamans', [
        'id_peminjaman' => $peminjaman->id_peminjaman,
        'status_peminjaman' => 'Menunggu',
    ]);
});

test('admin cannot approve peminjaman on a past date', function () {
    $peminjaman = Peminjaman::create([
        'id_akun' => $this->admin->id_akun,
        'id_ruangan' => 1,
        'tanggal_pinjam' => now()->subDay()->toDateString(),
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:00',
        'jumlah_peserta' => 10,
        'jenis_kegiatan' => 'Rapat',
        'status_peminjaman' => 'Menunggu',
        'nama_peminjam' => 'Admin Test',
        'email_peminjam' => 'admin@test.com',
    ]);

    $response = $this->actingAs($this->admin)
        ->patchJson(route('peminjaman.approve', $peminjaman->id_peminjaman));

    $response->assertStatus(500)
        ->assertJson(['success' => false]);
    
    expect($response->json('message'))->toContain('Peminjaman hanya dapat disetujui pada tanggal peminjaman (hari ini).');

    $this->assertDatabaseHas('peminjamans', [
        'id_peminjaman' => $peminjaman->id_peminjaman,
        'status_peminjaman' => 'Menunggu',
    ]);
});
