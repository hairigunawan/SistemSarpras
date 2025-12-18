<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use App\Models\Proyektor;
use App\Models\Status;
use App\Models\Lokasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuanganProyektorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get default status and lokasi
        $statusTersedia = Status::where('nama_status', 'Tersedia')->first();
        $lokasiFirst = Lokasi::first();

        if (!$statusTersedia || !$lokasiFirst) {
            $this->command->warn('Status "Tersedia" atau Lokasi tidak ditemukan. Silahkan jalankan seeder lain terlebih dahulu.');
            return;
        }

        // Create 10 Ruangan (Rooms)
        $ruanganData = [
            [
                'nama_ruangan' => 'Ruang Meeting A',
                'kode_ruangan' => 'RM-001',
                'kapasitas' => 20,
                'lokasi_id' => $lokasiFirst->id_lokasi,
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_ruangan' => 'Ruang Meeting B',
                'kode_ruangan' => 'RM-002',
                'kapasitas' => 15,
                'lokasi_id' => $lokasiFirst->id_lokasi,
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_ruangan' => 'Ruang Meeting C',
                'kode_ruangan' => 'RM-003',
                'kapasitas' => 30,
                'lokasi_id' => $lokasiFirst->id_lokasi,
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_ruangan' => 'Ruang Seminar',
                'kode_ruangan' => 'RS-001',
                'kapasitas' => 50,
                'lokasi_id' => $lokasiFirst->id_lokasi,
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_ruangan' => 'Ruang Kelas 1',
                'kode_ruangan' => 'RK-001',
                'kapasitas' => 40,
                'lokasi_id' => $lokasiFirst->id_lokasi,
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_ruangan' => 'Ruang Kelas 2',
                'kode_ruangan' => 'RK-002',
                'kapasitas' => 40,
                'lokasi_id' => $lokasiFirst->id_lokasi,
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_ruangan' => 'Ruang Kelas 3',
                'kode_ruangan' => 'RK-003',
                'kapasitas' => 35,
                'lokasi_id' => $lokasiFirst->id_lokasi,
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_ruangan' => 'Ruang Lab Komputer',
                'kode_ruangan' => 'RL-001',
                'kapasitas' => 25,
                'lokasi_id' => $lokasiFirst->id_lokasi,
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_ruangan' => 'Ruang Presentasi',
                'kode_ruangan' => 'RP-001',
                'kapasitas' => 100,
                'lokasi_id' => $lokasiFirst->id_lokasi,
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_ruangan' => 'Ruang Diskusi',
                'kode_ruangan' => 'RD-001',
                'kapasitas' => 12,
                'lokasi_id' => $lokasiFirst->id_lokasi,
                'id_status' => $statusTersedia->id_status,
            ],
        ];

        foreach ($ruanganData as $data) {
            Ruangan::create($data);
        }

        $this->command->info('✓ 10 Ruangan berhasil dibuat');

        // Create 10 Proyektor (Projectors)
        $proyektorData = [
            [
                'nama_proyektor' => 'Proyektor Epson EB-2150W',
                'kode_proyektor' => 'PJ-001',
                'merk' => 'Epson',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor Sony VPL-FHZ70',
                'kode_proyektor' => 'PJ-002',
                'merk' => 'Sony',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor Panasonic PT-RZ970',
                'kode_proyektor' => 'PJ-003',
                'merk' => 'Panasonic',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor BenQ LU9715',
                'kode_proyektor' => 'PJ-004',
                'merk' => 'BenQ',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor Optoma EH412',
                'kode_proyektor' => 'PJ-005',
                'merk' => 'Optoma',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor Canon LX-MW500',
                'kode_proyektor' => 'PJ-006',
                'merk' => 'Canon',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor Epson EB-2250U',
                'kode_proyektor' => 'PJ-007',
                'merk' => 'Epson',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor Christie DHD850',
                'kode_proyektor' => 'PJ-008',
                'merk' => 'Christie',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor Infocus INF5105',
                'kode_proyektor' => 'PJ-009',
                'merk' => 'InFocus',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor Epson EB-1795F',
                'kode_proyektor' => 'PJ-010',
                'merk' => 'Epson',
                'id_status' => $statusTersedia->id_status,
            ],
        ];

        foreach ($proyektorData as $data) {
            Proyektor::create($data);
        }

        $this->command->info('✓ 10 Proyektor berhasil dibuat');
        $this->command->info('✓ Total data dummy berhasil ditambahkan!');
    }
}
