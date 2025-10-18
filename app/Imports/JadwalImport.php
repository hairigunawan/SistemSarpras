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
            'kode_mk' => $row['kode_mk'],
            'nama_kelas' => $row['nama_kelas'],
            'kelas_mahasiswa' => $row['kelas_mahasiswa'],
            'hari' => $row['hari'],
            'jam_mulai' => $row['jam_mulai'],
            'jam_selesai' => $row['jam_selesai'],
            'ruangan' => $row['ruangan'],
            'daya_tampung' => $row['daya_tampung'],
        ]);
    }
}
