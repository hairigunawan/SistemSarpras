<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Pdf\Facades\PDF;
use App\Models\Peminjaman;
use App\Models\Laporan;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use DB;

class LaporanController extends Controller
{
    public function Index()
    {
        $totalpeminjaman = peminjaman::count();
        $peminjamanHariIni = Peminjaman::whereDate('tanggal_pinjam', Carbon::today())->count();
        $waktuRataRata = Peminjaman::selectRaw('AVG(TIMESTAMPDIFF(HOUR, jam_mulai, jam_selesai)) as avg_jam')
            ->value('avg_jam') ?? 0;

        $peminjamTeratas = Peminjaman::select('id_akun')
            ->selectRaw('count(*) as total')
            ->groupBy('id_akun')
            ->orderByDesc('total')
            ->take(3)
            ->get();

        $sarprasTerpopuler = Peminjaman::select('id_sarpras')
            ->selectRaw('count(*) as total')
            ->groupBy('id_sarpras')
            ->orderByDesc('total')
            ->take(3)
            ->get();

        $topSarpras = $sarprasTerpopuler->first();
        $sarprasNama = $topSarpras && $topSarpras->sarpras ? $topSarpras->sarpras->nama_sarpras : '-';
        $ruanganKode = $topSarpras && $topSarpras->sarpras ? $topSarpras->sarpras->kode_ruangan : '-';

         $laporan = Laporan::updateOrCreate(
            ['periode' => Carbon::now()->format('F Y')],
                [
                'sarpras_terbanyak' => $sarprasNama,
                'ruangan_tersering' => $ruanganKode,
                'jam_selesai' => sprintf('%.1f', $rataJam ?? 0),
                ]
            );

            return view('admin.laporan.index', [
            'totalPeminjaman' => $totalpeminjaman,
            'peminjamanHariIni' => $peminjamanHariIni,
            'waktuRataRata' => $waktuRataRata,
            'peminjamTeratas' => $peminjamTeratas,
            'sarprasTerpopuler' => $sarprasTerpopuler,
            'laporan' => $laporan,
            ]);
        }
        public function exportPdf()
        {
        $data = [
            'laporan' => Laporan::latest()->first(),
            'tanggal' => Carbon::now()->format('d M Y'),
        ];
        $pdf = FacadePdf::loadView('admin.laporan.pdf', $data);
        return $pdf->download('laporan-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
        {
        $data = [
            'laporan' => Laporan::latest()->first(),
            'tanggal' => Carbon::now()->format('d M Y'),
        ];
        return Excel::download(new Laporan, 'admin.laporan.xlsx' . date('Y-m-d') . '.xlsx');
        }
    }