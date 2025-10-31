<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lokasi::create(['nama_lokasi' => 'Gedung Teknik Informatika']);
        Lokasi::create(['nama_lokasi' => 'Gedung Adriansyah 1']);
        Lokasi::create(['nama_lokasi' => 'Gedung Adriansyah 2']);
    }
}
