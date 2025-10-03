<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Roles terlebih dahulu
        $adminRole = Role::firstOrCreate(['nama_role' => 'Admin']);
        Role::firstOrCreate(['nama_role' => 'Staff']);
        Role::firstOrCreate(['nama_role' => 'Dosen']);
        Role::firstOrCreate(['nama_role' => 'Mahasiswa']);

        // 2. Buat User Admin
        // Menggunakan firstOrCreate untuk menghindari duplikat jika seeder dijalankan lagi
        User::firstOrCreate(
            [
                'email' => 'admin@gmail.com' // Kunci unik untuk pengecekan
            ],
            [
                'nama' => 'Admin',
                'password' => Hash::make('password'), // Ganti dengan password yang aman
                'nomor_whatsapp' => '081234567890',
                'role_id' => $adminRole->id_role, // Assign role admin
            ]
        );
    }
}
