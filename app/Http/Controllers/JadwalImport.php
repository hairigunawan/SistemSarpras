<?php

namespace App\Imports;

use App\Models\Jadwal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class JadwalImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Jadwal([
            'kode_mk'           => $row['kode_mk'] ?? null,
            'nama_kelas'        => $row['nama_kelas'] ?? null,
            'kelas_mahasiswa'   => $row['kelas_mahasiswa'] ?? null,
            'hari'              => $row['hari'] ?? null,
            'jam_mulai'         => $row['jam_mulai'] ?? null,
            'jam_selesai'       => $row['jam_selesai'] ?? null,
            'ruangan'           => $row['ruangan'] ?? null,
            'daya_tampung'      => $row['daya_tampung'] ?? null,
            'sebaran_mahasiswa' => $row['sebaran_mahasiswa'] ?? null,
        ]);
    }
}
