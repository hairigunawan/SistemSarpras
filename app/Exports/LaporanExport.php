<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Laporan;
use Carbon\Carbon;

class LaporanExport implements FromArray, WithHeadings
{
    // Return rows for the Excel file
    public function array(): array
    {
        $laporan = Laporan::latest()->first();

        $row = [
            'periode' => $laporan->periode ?? '',
            'sarpras_terbanyak' => $laporan->sarpras_terbanyak ?? '',
            'ruangan_tersering' => $laporan->ruangan_tersering ?? '',
            'jam_selesai' => $laporan->jam_selesai ?? '',
            'generated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        return [array_values($row)];
    }

    // Headings for the sheet
    public function headings(): array
    {
        return [
            'Periode',
            'Sarpras Terbanyak',
            'Ruangan Tersering',
            'Jam Selesai (avg)',
            'Generated At',
        ];
    }
}
