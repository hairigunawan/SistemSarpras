<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
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

class RingkasanSheet implements FromArray, WithStyles, ShouldAutoSize, WithTitle
{
    private $periode;
    private $data;

    public function __construct($periode, $data)
    {
        $this->periode = $periode;
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Ringkasan Laporan';
    }

    public function array(): array
    {
        return [
            ['LAPORAN PEMINJAMAN SARANA PRASARANA'],
            ['Sistem Informasi Peminjaman'],
            [''],
            ['INFO LAPORAN'],
            ['Periode', ': ' . $this->getPeriodeLabel()],
            ['Tanggal Cetak', ': ' . Carbon::now()->format('d F Y H:i')],
            [''],
            ['RINGKASAN STATISTIK'],
            ['Total Seluruh Peminjaman', $this->data['totalPeminjaman'] . ' Transaksi'],
            ['Peminjaman Hari Ini', $this->data['peminjamanHariIni'] . ' Transaksi'],
            ['Rata-Rata Durasi', $this->data['waktuRataRata'] . ' Jam'],
        ];
    }

    private function getPeriodeLabel()
    {
        $now = Carbon::now();
        if ($this->periode === 'persemester') {
            $semester = $now->month <= 6 ? 'I (Ganjil)' : 'II (Genap)';
            return "Semester {$semester} - {$now->year}";
        }
        return $now->translatedFormat('F Y');
    }

    public function styles(Worksheet $sheet)
    {
        // Title Styling
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('A2:B2');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('2C3E50'));
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('7F8C8D'));

        // Section Headers (INFO & RINGKASAN)
        foreach ([4, 8] as $row) {
            $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '2C3E50']],
            ]);
        }

        // Content Styling
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A4:B$lastRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A1:B$lastRow")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Padding Effect
        $sheet->getDefaultRowDimension()->setRowHeight(20);

        return [];
    }
}

class PeminjamSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Top Peminjam';
    }

    public function headings(): array
    {
        return ['NO', 'NAMA PEMINJAM', 'ALAMAT EMAIL', 'TOTAL PINJAM'];
    }

    public function array(): array
    {
        return collect($this->data['peminjamTeratas'])->map(fn($p, $i) => [
            $i + 1,
            $p['nama'],
            $p['email'],
            $p['jumlah']
        ])->toArray();
    }

    public function styles(Worksheet $sheet)
    {
        // Header
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '2980B9']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        $lastRow = $sheet->getHighestRow();

        // Table Body
        $sheet->getStyle("A1:D$lastRow")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'BDC3C7']]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ]);

        // Zebra Rows + Alignment
        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle("A{$i}:D{$i}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('ECF0F1');
            }
            $sheet->getStyle("A$i")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D$i")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }
}

class SarprasSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Sarpras Terpopuler';
    }

    public function headings(): array
    {
        return ['NO', 'NAMA SARPRAS', 'KATEGORI', 'LOKASI RUANGAN', 'FREKUENSI'];
    }

    public function array(): array
    {
        return collect($this->data['sarprasTerpopuler'])->map(fn($s, $i) => [
            $i + 1,
            $s['nama'],
            ucfirst($s['type']),
            $s['lokasi'],
            $s['jumlah']
        ])->toArray();
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '27AE60']], // Warna Hijau untuk Sarpras
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:E$lastRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle("A{$i}:E{$i}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F9F9F9');
            }
            $sheet->getStyle("A$i")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E$i")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }
}
