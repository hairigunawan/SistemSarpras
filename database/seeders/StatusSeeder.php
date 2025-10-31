<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Tersedia', 'Dipakai', 'Diperbaiki', 'Rusak'];

        foreach ($statuses as $statusName) {
            if (!Status::where('nama_status', $statusName)->exists()) {
                Status::create(['nama_status' => $statusName]);
            }
        }
    }
}
