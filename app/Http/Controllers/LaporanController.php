<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Peminjaman;
use App\Models\Proyektor;
use App\Models\Ruangan;
use App\Models\Laporan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $totalpeminjaman = Peminjaman::count();
        $peminjamanHariIni = Peminjaman::whereDate('tanggal_pinjam', Carbon::today())->count();
        $waktuRataRata = Peminjaman::selectRaw('AVG(TIMESTAMPDIFF(HOUR, jam_mulai, jam_selesai)) as avg_jam')
            ->value('avg_jam') ?? 0;

        $peminjamTeratas = Peminjaman::select('id_akun', DB::raw('count(*) as total'))
            ->groupBy('id_akun')
            ->orderByDesc('total')
            ->with('user') // Memuat relasi user
            ->take(3)
            ->get()
            ->map(function ($peminjam) {
                return [
                    'nama' => Auth::user()->name ?? 'N/A',
                    'email' => Auth::user()->email ?? 'N/A',
                    'jumlah' => $peminjam->total,
                ];
            });

        $sarprasTerpopuler = Peminjaman::select('id_ruangan', 'id_proyektor', DB::raw('count(*) as total'))
            ->groupBy('id_ruangan', 'id_proyektor')
            ->orderByDesc('total')
            ->take(3)
            ->get()
            ->map(function ($sarpras) {
                $nama = 'N/A';
                $lokasi = 'N/A';
                if ($sarpras->id_ruangan) {
                    $ruangan = Ruangan::find($sarpras->id_ruangan);
                    $nama = $ruangan->nama_ruangan ?? 'N/A';
                    $lokasi = $ruangan->lokasi->nama_lokasi ?? 'N/A';
                } elseif ($sarpras->id_proyektor) {
                    $proyektor = Proyektor::find($sarpras->id_proyektor);
                    $nama = $proyektor->nama_proyektor ?? 'N/A';
                    $lokasi = $proyektor->lokasi->nama_lokasi ?? 'N/A';
                }
                return [
                    'nama' => $nama,
                    'lokasi' => $lokasi,
                    'jumlah' => $sarpras->total,
                ];
            });

        $topSarprasNama = 'N/A';
        $topSarprasKode = 'N/A';

        if ($sarprasTerpopuler->isNotEmpty()) {
            $firstSarpras = $sarprasTerpopuler->first();
            $topSarprasNama = $firstSarpras['nama'];
            $topSarprasKode = $firstSarpras['lokasi'];
        }

        $laporan = Laporan::updateOrCreate(
            ['periode' => Carbon::now()->format('F Y')],
            [
                'sarpras_terbanyak' => $topSarprasNama,
                'ruangan_tersering' => $topSarprasKode,
                'jam_selesai' => sprintf('%.1f', $waktuRataRata ?? 0),
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
        return Excel::download(new LaporanExport, 'laporan-' . date('Y-m-d') . '.xlsx');
    }
}
