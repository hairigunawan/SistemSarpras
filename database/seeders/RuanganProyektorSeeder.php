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

        // Create Ruangan (Rooms) — updated list sesuai lampiran pengguna
        $ruanganData = [
            ['nama_ruangan' => 'A01 - Java', 'kode_ruangan' => 'A01', 'kapasitas' => 20, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A02 - CI', 'kode_ruangan' => 'A02', 'kapasitas' => 20, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A03 - HTML', 'kode_ruangan' => 'A03', 'kapasitas' => 25, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A04 - Cisco', 'kode_ruangan' => 'A04', 'kapasitas' => 25, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A05 - MySQL', 'kode_ruangan' => 'A05', 'kapasitas' => 20, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A06 - PHP', 'kode_ruangan' => 'A06', 'kapasitas' => 20, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A07 - Android', 'kode_ruangan' => 'A07', 'kapasitas' => 20, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A08 - Bootstrap', 'kode_ruangan' => 'A08', 'kapasitas' => 20, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A09 - Lab. Komputer C', 'kode_ruangan' => 'A09', 'kapasitas' => 30, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A10 - Lab. Komputer A', 'kode_ruangan' => 'A10', 'kapasitas' => 30, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A11 - Lab. Komputer B', 'kode_ruangan' => 'A11', 'kapasitas' => 30, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A12 - Lab. Jaringan', 'kode_ruangan' => 'A12', 'kapasitas' => 28, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A13 - Lab. Komputer D', 'kode_ruangan' => 'A13', 'kapasitas' => 30, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A14 - jQuery', 'kode_ruangan' => 'A14', 'kapasitas' => 18, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A15 - C++', 'kode_ruangan' => 'A15', 'kapasitas' => 18, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A16 - Laboratorium Guido Van Rossum', 'kode_ruangan' => 'A16', 'kapasitas' => 24, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A17 - Laboratorium Steve Jobs', 'kode_ruangan' => 'A17', 'kapasitas' => 24, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A18 - Laboratorium Bill Gates', 'kode_ruangan' => 'A18', 'kapasitas' => 24, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A19 - Laboratorium Kenneth Thompson', 'kode_ruangan' => 'A19', 'kapasitas' => 24, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A20 - Laboratorium Linus Torvalds', 'kode_ruangan' => 'A20', 'kapasitas' => 24, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A21 - Lab. Komputer E', 'kode_ruangan' => 'A21', 'kapasitas' => 30, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A22 - Posko TA', 'kode_ruangan' => 'A22', 'kapasitas' => 12, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A29 - Phyton', 'kode_ruangan' => 'A29', 'kapasitas' => 16, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A30 - Ruang Teknisi Lab Kenneth Thompson', 'kode_ruangan' => 'A30', 'kapasitas' => 8, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A31 - Ruang Seminar Kenneth Thompson', 'kode_ruangan' => 'A31', 'kapasitas' => 40, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A32 - Ruang Teknisi Lab Linus Torvalds', 'kode_ruangan' => 'A32', 'kapasitas' => 8, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A33 - Ruang Seminar Lab Linus Torvalds', 'kode_ruangan' => 'A33', 'kapasitas' => 40, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A34 - Ruang Teknisi Lab Steve Jobs', 'kode_ruangan' => 'A34', 'kapasitas' => 8, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A35 - Ruang Seminar Lab Bill Gates', 'kode_ruangan' => 'A35', 'kapasitas' => 40, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'A36 - Ruang Teknisi Lab Bill Gates', 'kode_ruangan' => 'A36', 'kapasitas' => 8, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
            ['nama_ruangan' => 'D26 - Ruang Kelas 3 (Hima TI)', 'kode_ruangan' => 'D26', 'kapasitas' => 35, 'lokasi_id' => $lokasiFirst->id_lokasi, 'id_status' => $statusTersedia->id_status],
        ];

        foreach ($ruanganData as $data) {
            Ruangan::create($data);
        }

        $this->command->info('Ruangan berhasil dibuat');

        // Create 10 Proyektor (Projectors)
        $proyektorData = [
            [
                'nama_proyektor' => 'Proyektor Epson EB-2150W',
                'kode_proyektor' => 'TI01',
                'merk' => 'Epson',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor Sony VPL-FHZ70',
                'kode_proyektor' => 'TI02',
                'merk' => 'Sony',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor Panasonic PT-RZ970',
                'kode_proyektor' => 'TI03',
                'merk' => 'Panasonic',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor BenQ LU9715',
                'kode_proyektor' => 'TI04',
                'merk' => 'BenQ',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor Optoma EH412',
                'kode_proyektor' => 'TI05',
                'merk' => 'Optoma',
                'id_status' => $statusTersedia->id_status,
            ],
            [
                'nama_proyektor' => 'Proyektor Canon LX-MW500',
                'kode_proyektor' => 'TI06',
                'merk' => 'Canon',
                'id_status' => $statusTersedia->id_status,
            ],
        ];

        foreach ($proyektorData as $data) {
            Proyektor::create($data);
        }

        $this->command->info('Proyektor berhasil dibuat');
    }
}
