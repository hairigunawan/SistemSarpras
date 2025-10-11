<?php
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class LaporanExport implements FromView
{
    public function view(): View
    {
        return view('admin.laporan.xlsx', [
            'laporan' => Laporan::latest()->first(),
            'tanggal' => now()->format('d M Y'),
        ]);
    }
}