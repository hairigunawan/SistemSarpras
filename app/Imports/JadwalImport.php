<?php

namespace App\Imports;

use App\Models\Jadwal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Validation\Rule;

class JadwalImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // 1. FILTER: Cek apakah baris ini "Ghost Row" / Baris Kosong / Sampah
        // Jika nama_kelas DAN hari kosong, kita anggap ini baris kosong yang harus di-skip.
        // Excel sering meninggalkan baris dengan format tapi tanpa data di akhir file.
        if (empty($row['nama_kelas']) && empty($row['hari'])) {
            return null;
        }

        // 2. VALIDASI MANUAL: Jika lolos filter di atas, berarti ini dianggap DATA.
        // Kita harus pastikan kolom wajib terisi, karena di rules() kita buat nullable (agar filter no.1 jalan).
        $missingFields = [];
        if (empty($row['nama_kelas'])) $missingFields[] = 'Nama Kelas';
        if (empty($row['hari'])) $missingFields[] = 'Hari';
        if (empty($row['ruangan'])) $missingFields[] = 'Ruangan';
        if (empty($row['kelas_mahasiswa'])) $missingFields[] = 'Kelas Mahasiswa';
        if (empty($row['jam_mulai'])) $missingFields[] = 'Jam Mulai';
        if (empty($row['jam_selesai'])) $missingFields[] = 'Jam Selesai';

        if (!empty($missingFields)) {
            // Kita throw Exception agar ditangkap Controller dan ditampilkan ke user
            // Kita sertakan data kolom pertama (misal Kode MK atau Nama Kelas) untuk identifikasi
            $identifier = $row['nama_kelas'] ?? ($row['kode_mk'] ?? 'Tanpa Nama');
            throw new \Exception("Data tidak lengkap untuk jadwal '{$identifier}'. Kolom yang kurang: " . implode(', ', $missingFields));
        }

        // Filter baris sampah/kosong parsial (misal hanya ada jam tapi tidak ada nama kelas)
        // (Logika lama, sudah tercover di atas, tapi kita biarkan flow lanjut)
        
        return new Jadwal([
            'kode_mk'           => $row['kode_mk'] ?? null,
            'sistem_kuliah'     => $row['sistem_kuliah'] ?? null,
            'nama_kelas'        => $row['nama_kelas'] ?? null,
            'kelas_mahasiswa'   => $row['kelas_mahasiswa'] ?? null,
            'sebaran_kelas'     => $row['sebaran_kelas'] ?? null,
            'hari'              => $row['hari'] ?? null,
            'ruangan'           => $row['ruangan'] ?? null,
            'daya_tampung'      => $row['daya_tampung'] ?? 0,

            // Transformasi waktu
            'jam_mulai'         => $this->transformTime($row['jam_mulai'] ?? null),
            'jam_selesai'       => $this->transformTime($row['jam_selesai'] ?? null),
        ]);
    }

    public function rules(): array
    {
        // Kita buat semua nullable di sini agar validasi awal tidak me-reject "Ghost Row" (Baris 9 error).
        // Validasi kelengkapan data dipindah ke dalam fungsi model() di atas.
        return [
            // 'kode_mk' => 'required',
            'nama_kelas' => 'nullable',
            'hari' => 'nullable',
            'ruangan' => 'nullable',
            'kelas_mahasiswa' => 'nullable',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'daya_tampung' => 'nullable|numeric',
            'sistem_kuliah' => 'nullable',
            'sebaran_kelas' => 'nullable',
        ];
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
            // Kita coba parse dengan Carbon untuk memastikan format H:i:s
            try {
                return \Carbon\Carbon::parse($value)->format('H:i:s');
            } catch (\Exception $e) {
                return '00:00:00'; 
            }
        } catch (\Exception $e) {
            return '00:00:00';
        }
    }
}
