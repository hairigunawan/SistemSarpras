<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DosenMahasiswaRoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder yang baru kita buat
        $this->call([
            AdminSeeder::class,
            StatusSeeder::class,
            LokasiSeeder::class,
            DosenMahasiswaRoleSeeder::class,
            PrioritasSeeder::class,
        ]);
    }
}
