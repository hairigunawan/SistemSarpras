<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\Proyektor;
use App\Models\Lokasi;
use App\Models\Status;
use App\Models\Peminjaman;
use App\Models\Role;

class PeminjamanSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_submit_peminjaman_for_both_ruangan_and_proyektor()
    {
        $this->withoutMiddleware();

        // Setup data
        // Role
        $role = Role::create(['nama_role' => 'Mahasiswa']);

        // User
        $user = User::factory()->create([
            'role_id' => $role->id_role,
        ]);

        // Lokasi
        $lokasi = Lokasi::create(['nama_lokasi' => 'Gedung A']);

        // Status
        $status = Status::create(['nama_status' => 'Tersedia']);

        // Ruangan
        $ruangan = Ruangan::create([
            'nama_ruangan' => 'Ruangan 101',
            'kapasitas' => 30,
            'lokasi_id' => $lokasi->id_lokasi,
            'id_status' => $status->id_status,
            'gambar' => 'dummy.jpg',
            'kode_ruangan' => 'R101'
        ]);

        // Proyektor
        $proyektor = Proyektor::create([
            'nama_proyektor' => 'Proyektor P1',
            'merk' => 'Epson',
            'id_status' => $status->id_status,
            'kode_proyektor' => 'P001',
            'gambar' => 'dummy.jpg'
        ]);

        $this->actingAs($user);

        // Submit data
        $response = $this->post(route('public.peminjaman.store.auth'), [
            'id_ruangan' => $ruangan->id_ruangan,
            'id_proyektor' => $proyektor->id_proyektor,
            'id_ruangan_proyektor' => null, // Case where user doesn't select separate room for projector
            'id_lokasi' => $lokasi->id_lokasi,
            'tanggal_pinjam' => now()->addDay()->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'jumlah_peserta' => 10,
            'jenis_kegiatan' => 'Kuliah',
        ]);

        // Check for redirection (success)
        $response->assertRedirect();
        
        // Assert Database
        $this->assertDatabaseHas('peminjamans', [
            'id_akun' => $user->id_akun,
            'id_ruangan' => $ruangan->id_ruangan,
            'id_proyektor' => $proyektor->id_proyektor,
        ]);
    }

    public function test_id_ruangan_overwritten_if_empty()
    {
         $this->withoutMiddleware();
         // Role
        $role = Role::create(['nama_role' => 'Mahasiswa']);

        // User
        $user = User::factory()->create([
            'role_id' => $role->id_role,
        ]);

        // Lokasi
        $lokasi = Lokasi::create(['nama_lokasi' => 'Gedung A']);

        // Status
        $status = Status::create(['nama_status' => 'Tersedia']);

        // Ruangan for projector
        $ruanganProyektor = Ruangan::create([
            'nama_ruangan' => 'Ruangan Lab',
            'kapasitas' => 30,
            'lokasi_id' => $lokasi->id_lokasi,
            'id_status' => $status->id_status,
             'gambar' => 'dummy.jpg',
              'kode_ruangan' => 'RLAB'
        ]);

        // Proyektor
        $proyektor = Proyektor::create([
            'nama_proyektor' => 'Proyektor P1',
            'merk' => 'Epson',
            'id_status' => $status->id_status,
            'gambar' => 'dummy.jpg',
            'kode_proyektor' => 'P001'
        ]);

        $this->actingAs($user);

        // Submit data WITHOUT id_ruangan, but WITH id_ruangan_proyektor
        $response = $this->post(route('public.peminjaman.store.auth'), [
            'id_ruangan' => null,
            'id_proyektor' => $proyektor->id_proyektor,
            'id_ruangan_proyektor' => $ruanganProyektor->id_ruangan,
            'id_lokasi' => $lokasi->id_lokasi,
            'tanggal_pinjam' => now()->addDay()->format('Y-m-d'),
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'jumlah_peserta' => 10,
            'jenis_kegiatan' => 'Rapat',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('peminjamans', [
            'id_akun' => $user->id_akun,
            'id_ruangan' => $ruanganProyektor->id_ruangan, // Should be set from id_ruangan_proyektor
            'id_proyektor' => $proyektor->id_proyektor,
        ]);
    }
}
