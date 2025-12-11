<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lokasi;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasis = ['Gedung Teknik Informatika', 'Gedung Adriansyah 1', 'Gedung Adriansyah 2'];

        foreach ($lokasis as $lokasiName) {
            if (!Lokasi::where('nama_lokasi', $lokasiName)->exists()) {
                Lokasi::create(['nama_lokasi' => $lokasiName]);
            }
        }
    }
}
