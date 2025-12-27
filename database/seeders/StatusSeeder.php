<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Dipinjam', 'Tersedia', 'Dipakai', 'Diperbaiki', 'Rusak'];

        foreach ($statuses as $statusName) {
            Status::updateOrCreate(
                ['nama_status' => $statusName],
                ['nama_status' => $statusName]
            );
        }
    }
}
