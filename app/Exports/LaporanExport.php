<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class LaporanExport implements WithMultipleSheets
{
    private $periode;
    private $data;

    public function __construct($periode, $data)
    {
        $this->periode = $periode;
        $this->data = $data ?? [];
    }

    public function sheets(): array
    {
        return [
            new RingkasanSheet($this->periode, $this->data),
            new PeminjamSheet($this->data),
            new SarprasSheet($this->data),
        ];
    }
}

/* ===========================
   SHEET 1: RINGKASAN
   =========================== */

class RingkasanSheet implements FromArray, WithStyles, ShouldAutoSize
{
    private $periode;
    private $data;

    public function __construct($periode, $data)
    {
        $this->periode = $periode;
        $this->data = $data;
    }

    public function array(): array
    {
        return [
            ['LAPORAN PEMINJAMAN SARANA PRASARANA'],
            ['Periode', $this->getPeriodeLabel()],
            ['Tanggal Cetak', Carbon::now()->format('d M Y H:i:s')],
            ['', ''],
            ['RINGKASAN DATA'],
            ['Total Peminjaman', $this->data['totalPeminjaman']],
            ['Peminjaman Hari Ini', $this->data['peminjamanHariIni']],
            ['Rata-Rata Waktu Penggunaan', $this->data['waktuRataRata'] . ' jam'],
        ];
    }

    private function getPeriodeLabel()
    {
        $now = Carbon::now();

        if ($this->periode === 'persemester') {
            $semester = $now->month <= 6 ? 1 : 2;
            return "Semester {$semester} {$now->year}";
        }
        return $now->format('F Y');
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // Bold Title
        $sheet->mergeCells('A1:B1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Section Headers Styling
        foreach ([5] as $row) {
            $sheet->getStyle("A{$row}:B{$row}")
                ->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('2C3E50');
            $sheet->getStyle("A{$row}:B{$row}")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'))->setBold(true);
        }

        // Data Styling (Borders)
        for ($i = 1; $i <= $highestRow; $i++) {
            $sheet->getStyle("A{$i}:B{$i}")
                ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $sheet->getStyle("A{$i}:B{$i}")->getAlignment()->setVertical('center');
            $sheet->getStyle("A{$i}:B{$i}")->getAlignment()->setWrapText(true);
        }

        return [];
    }
}


class PeminjamSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return ['No', 'Nama Peminjam', 'Email', 'Jumlah Peminjaman'];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->data['peminjamTeratas'] as $i => $p) {
            $rows[] = [
                $i + 1,
                $p['nama'],
                $p['email'],
                $p['jumlah']
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        // Header Style
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '3498db']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Borders + Zebra rows
        $lastRow = $sheet->getHighestRow();

        for ($i = 1; $i <= $lastRow; $i++) {
            $sheet->getStyle("A{$i}:D{$i}")
                ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            if ($i % 2 == 0 && $i > 1) {
                $sheet->getStyle("A{$i}:D{$i}")
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F4F6F6');
            }
        }

        return [];
    }
}

/* ===========================
   SHEET 3: SARPRAS TERPOPULER
   =========================== */

class SarprasSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return ['No', 'Nama Sarpras', 'Tipe', 'Lokasi', 'Jumlah'];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->data['sarprasTerpopuler'] as $i => $s) {
            $rows[] = [
                $i + 1,
                $s['nama'],
                ucfirst($s['type']),
                $s['lokasi'],
                $s['jumlah'],
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '3498db']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        $lastRow = $sheet->getHighestRow();

        for ($i = 1; $i <= $lastRow; $i++) {
            $sheet->getStyle("A{$i}:E{$i}")
                ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            if ($i % 2 == 0 && $i > 1) {
                $sheet->getStyle("A{$i}:E{$i}")
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F4F6F6');
            }
        }

        return [];
    }
}
