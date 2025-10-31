<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class DosenMahasiswaRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek dan tambahkan role 'Dosen' jika belum ada
        if (!Role::where('nama_role', 'Dosen')->exists()) {
            Role::create(['nama_role' => 'Dosen']);
        }

        // Cek dan tambahkan role 'Mahasiswa' jika belum ada
        if (!Role::where('nama_role', 'Mahasiswa')->exists()) {
            Role::create(['nama_role' => 'Mahasiswa']);
        }
    }
}