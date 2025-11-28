<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class RiwayatPeminjamanExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $peminjaman;
    protected $user;
    protected $tanggalCetak;

    public function __construct($peminjaman, $user, $tanggalCetak)
    {
        $this->peminjaman = $peminjaman;
        $this->user = $user;
        $this->tanggalCetak = $tanggalCetak;
    }

    public function array(): array
    {
        $data = [
            ['LAPORAN RIWAYAT PEMINJAMAN SARANA PRASARANA'],
            ['Nama Peminjam', $this->user->nama ?? 'N/A'],
            ['Email Peminjam', $this->user->email ?? 'N/A'],
            ['Tanggal Cetak', $this->tanggalCetak],
            ['', ''],
            ['DETAIL PEMINJAMAN'],
        ];

        foreach ($this->peminjaman as $i => $item) {
            $data[] = [
                $i + 1,
                $item->ruangan->nama_ruangan ?? $item->proyektor->nama_proyektor ?? '-',
                $item->proyektor->merk ?? $item->ruangan->lokasi->nama_lokasi ?? '-',
                Carbon::parse($item->tanggal_pinjam)->translatedFormat('d F Y'),
                date('H:i', strtotime($item->jam_mulai)) . ' - ' . date('H:i', strtotime($item->jam_selesai)) . ' WIB',
                $item->jenis_kegiatan,
                $item->status_peminjaman,
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Sarana / Prasarana',
            'Lokasi / Merk',
            'Tanggal Pinjam',
            'Waktu',
            'Keperluan',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // Title
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // User Info
        $sheet->mergeCells('B2:G2');
        $sheet->mergeCells('B3:G3');
        $sheet->mergeCells('B4:G4');

        // Section Headers Styling
        $sheet->mergeCells('A6:G6');
        $sheet->getStyle("A6:G6")
            ->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('2C3E50');
        $sheet->getStyle("A6:G6")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'))->setBold(true);
        $sheet->getStyle("A6:G6")->getAlignment()->setHorizontal('center');

        // Header Style for data table
        $sheet->getStyle('A7:G7')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '3498db']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Borders + Zebra rows for data table
        for ($i = 1; $i <= $highestRow; $i++) {
            $sheet->getStyle("A{$i}:G{$i}")
                ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            if ($i > 7 && $i % 2 == 0) { // Apply zebra styling to data rows only
                $sheet->getStyle("A{$i}:G{$i}")
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F4F6F6');
            }
        }

        return [];
    }
}