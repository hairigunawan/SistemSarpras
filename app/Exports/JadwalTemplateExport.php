<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JadwalTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        return [
            [
                'IF1234',       // kode_mk
                'Reguler',      // sistem_kuliah
                'Pemrograman Web', // nama_kelas
                'A',            // kelas_mahasiswa
                'Semester 3',   // sebaran_kelas
                'Senin',        // hari
                'Lab 1',        // ruangan
                '40',           // daya_tampung
                '08:00',        // jam_mulai
                '10:00',        // jam_selesai
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'kode_mk',
            'sistem_kuliah',
            'nama_kelas',
            'kelas_mahasiswa',
            'sebaran_kelas',
            'hari',
            'ruangan',
            'daya_tampung',
            'jam_mulai',
            'jam_selesai',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
