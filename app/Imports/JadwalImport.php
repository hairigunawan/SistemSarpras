<?php

namespace App\Imports;

use App\Models\Jadwal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date; // 1. Wajib tambahkan ini

class JadwalImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Jadwal([
            'kode_mk'           => $row['kode_mk'] ?? null,
            'nama_kelas'        => $row['nama_kelas'] ?? null,
            'kelas_mahasiswa'   => $row['kelas_mahasiswa'] ?? null,
            'hari'              => $row['hari'] ?? null,
            'ruangan'           => $row['ruangan'] ?? null,
            'daya_tampung'      => $row['daya_tampung'] ?? null,
            'sebaran_mahasiswa' => $row['sebaran_mahasiswa'] ?? null,

            // 2. Panggil fungsi transformTime untuk jam
            'jam_mulai'         => $this->transformTime($row['jam_mulai'] ?? null),
            'jam_selesai'       => $this->transformTime($row['jam_selesai'] ?? null),
        ]);
    }

    /**
     * Fungsi Helper untuk Mengubah Format Waktu Excel ke Format SQL
     */
    private function transformTime($value)
    {
        if (!$value) {
            return '00:00:00';
        }

        try {
            // Cek apakah formatnya angka (Excel Time Serial number)
            // Contoh: 0.5 di excel = 12:00:00
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('H:i:s');
            }

            // Jika formatnya sudah string text (misal: "12:00"), kembalikan apa adanya
            return $value;
        } catch (\Exception $e) {
            return '00:00:00';
        }
    }
}
