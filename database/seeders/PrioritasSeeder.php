<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrioritasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Prioritas::create(['nama_prioritas' => 'Sangat Mendesak', 'nilai_prioritas' => 5]);
        \App\Models\Prioritas::create(['nama_prioritas' => 'Mendesak', 'nilai_prioritas' => 4]);
        \App\Models\Prioritas::create(['nama_prioritas' => 'Cukup Mendesak', 'nilai_prioritas' => 3]);
        \App\Models\Prioritas::create(['nama_prioritas' => 'Tidak Mendesak', 'nilai_prioritas' => 2]);
        \App\Models\Prioritas::create(['nama_prioritas' => 'Sangat Tidak Mendesak', 'nilai_prioritas' => 1]);
    }
}
