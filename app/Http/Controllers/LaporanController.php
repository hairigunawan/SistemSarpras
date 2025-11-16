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
    /**
     * Helper method untuk mendapatkan date range berdasarkan periode
     */
    private function getDateRange($periode)
    {
        $now = Carbon::now();

        switch ($periode) {
            case 'perbulan':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;
            case 'persemester':
                $month = $now->month;
                $year = $now->year;

                if ($month <= 6) {
                    $startDate = Carbon::createFromDate($year, 1, 1);
                    $endDate = Carbon::createFromDate($year, 6, 30);
                } else {
                    $startDate = Carbon::createFromDate($year, 7, 1);
                    $endDate = Carbon::createFromDate($year, 12, 31);
                }
                break;
            default:
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
        }

        return ['startDate' => $startDate, 'endDate' => $endDate];
    }

    /**
     * Helper method untuk mendapatkan periode label
     */
    private function getPeriodeLabel($periode)
    {
        $now = Carbon::now();

        switch ($periode) {
            case 'perbulan':
                return $now->format('F Y');
            case 'persemester':
                $month = $now->month;
                $year = $now->year;
                $semester = $month <= 6 ? 1 : 2;
                return "Semester {$semester} {$year}";
            default:
                return $now->format('F Y');
        }
    }

    public function index(Request $request)
    {
        $periode = $request->get('periode', 'perbulan');
        $dateRange = $this->getDateRange($periode);
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        // Hitung total peminjaman dengan filter periode
        $totalPeminjaman = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])->count();

        // Hitung waktu rata-rata peminjaman dengan filter periode
        $waktuRataRata = Peminjaman::selectRaw("
            AVG(
                TIMESTAMPDIFF(
                    HOUR,
                    CONCAT(tanggal_pinjam, ' ', jam_mulai),
                    CONCAT(tanggal_pinjam, ' ', jam_selesai)
                )
            ) as avg_jam
        ")
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->value('avg_jam') ?? 0;


        // Hitung peminjam teratas (top 3) dengan filter periode
        $peminjamTeratas = Peminjaman::select('id_akun', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('id_akun')
            ->orderByDesc('total')
            ->take(3)
            ->get()
            ->map(function ($peminjam) {
                $user = User::find($peminjam->id_akun);
                return [
                    'nama' => $user->nama ?? 'N/A',
                    'email' => $user->email ?? 'N/A',
                    'jumlah' => $peminjam->total,
                ];
            });

        // Hitung sarpras terpopuler (top 3) dengan filter periode
        $sarprasTerpopuler = collect();

        // Hitung untuk ruangan
        $ruanganPopuler = Peminjaman::whereNotNull('id_ruangan')
            ->select('id_ruangan', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('id_ruangan')
            ->orderByDesc('total')
            ->take(3)
            ->get()
            ->map(function ($item) {
                $ruangan = Ruangan::find($item->id_ruangan);
                return [
                    'nama' => $ruangan->nama_ruangan ?? 'N/A',
                    'lokasi' => $ruangan->lokasi->nama_lokasi ?? 'N/A',
                    'jumlah' => $item->total,
                    'type' => 'ruangan',
                ];
            });

        // Hitung untuk proyektor
        $proyektorPopuler = Peminjaman::whereNotNull('id_proyektor')
            ->select('id_proyektor', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('id_proyektor')
            ->orderByDesc('total')
            ->take(3)
            ->get()
            ->map(function ($item) {
                $proyektor = Proyektor::find($item->id_proyektor);
                return [
                    'nama' => $proyektor->nama_proyektor ?? 'N/A',
                    'lokasi' => $proyektor->lokasi->nama_lokasi ?? 'N/A',
                    'jumlah' => $item->total,
                    'type' => 'proyektor',
                ];
            });

        // Gabungkan dan urutkan berdasarkan jumlah, ambil top 3
        $sarprasTerpopuler = $ruanganPopuler->merge($proyektorPopuler)
            ->sortByDesc('jumlah')
            ->take(3)
            ->values();

        // Ambil data untuk laporan
        $topSarprasNama = 'N/A';
        $topSarprasKode = 'N/A';

        if ($sarprasTerpopuler->isNotEmpty()) {
            $firstSarpras = $sarprasTerpopuler->first();
            $topSarprasNama = $firstSarpras['nama'];
            $topSarprasKode = $firstSarpras['lokasi'];
        }

        // Update atau create laporan dengan periode
        $periodeLabel = $this->getPeriodeLabel($periode);
        $laporan = Laporan::updateOrCreate(
            ['periode' => $periodeLabel],
            [
                'sarpras_terbanyak' => $topSarprasNama,
                'ruangan_tersering' => $topSarprasKode,
                'jam_selesai' => sprintf('%.1f', $waktuRataRata ?? 0),
            ]
        );

        // Hitung peminjaman hari ini
        $PeminjamanHariIni = Peminjaman::whereDate('tanggal_pinjam', Carbon::today())->count();

        // Ambil status dari request dan query peminjaman
        $status = $request->get('status', 'all');
        $query = Peminjaman::with(['user', 'ruangan', 'proyektor']);

        // Jika status bukan 'all', filter berdasarkan status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $peminjaman = $query->latest()->get();

        return view('admin.laporan.index', [
            'PeminjamanHariIni' => $PeminjamanHariIni,
            'peminjamanHariIniCount' => $PeminjamanHariIni,
            'peminjaman' => $peminjaman,
            'totalPeminjaman' => $totalPeminjaman,
            'waktuRataRata' => $waktuRataRata,
            'peminjamTeratas' => $peminjamTeratas,
            'sarprasTerpopuler' => $sarprasTerpopuler,
            'laporan' => $laporan,
            'status' => $status,
            'periode' => $periode,
            'periodeLabel' => $this->getPeriodeLabel($periode),
        ]);
    }

    public function exportPdf(Request $request)
    {
        $periode = $request->get('periode', 'perbulan');
        $dateRange = $this->getDateRange($periode);
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];
        $periodeLabel = $this->getPeriodeLabel($periode);

        // Hitung data untuk PDF
        $totalPeminjaman = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])->count();
        $peminjamanHariIni = Peminjaman::whereDate('tanggal_pinjam', Carbon::today())->count();
        $waktuRataRata = Peminjaman::selectRaw('AVG(TIMESTAMPDIFF(HOUR, jam_mulai, jam_selesai)) as avg_jam')
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->value('avg_jam') ?? 0;

        // Peminjam teratas
        $peminjamTeratas = Peminjaman::select('id_akun', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('id_akun')
            ->orderByDesc('total')
            ->take(3)
            ->get()
            ->map(function ($peminjam) {
                $user = User::find($peminjam->id_akun);
                return [
                    'nama' => $user->nama ?? 'N/A',
                    'email' => $user->email ?? 'N/A',
                    'jumlah' => $peminjam->total,
                ];
            });

        // Sarpras terpopuler
        $ruanganPopuler = Peminjaman::whereNotNull('id_ruangan')
            ->select('id_ruangan', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('id_ruangan')
            ->orderByDesc('total')
            ->take(3)
            ->get()
            ->map(function ($item) {
                $ruangan = Ruangan::find($item->id_ruangan);
                return [
                    'nama' => $ruangan->nama_ruangan ?? 'N/A',
                    'lokasi' => $ruangan->lokasi->nama_lokasi ?? 'N/A',
                    'jumlah' => $item->total,
                    'type' => 'ruangan',
                ];
            });

        $proyektorPopuler = Peminjaman::whereNotNull('id_proyektor')
            ->select('id_proyektor', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('id_proyektor')
            ->orderByDesc('total')
            ->take(3)
            ->get()
            ->map(function ($item) {
                $proyektor = Proyektor::find($item->id_proyektor);
                return [
                    'nama' => $proyektor->nama_proyektor ?? 'N/A',
                    'lokasi' => $proyektor->lokasi->nama_lokasi ?? 'N/A',
                    'jumlah' => $item->total,
                    'type' => 'proyektor',
                ];
            });

        $sarprasTerpopuler = $ruanganPopuler->merge($proyektorPopuler)
            ->sortByDesc('jumlah')
            ->take(3)
            ->values();

        $data = [
            'laporan' => Laporan::where('periode', $periodeLabel)->latest()->first(),
            'tanggal' => Carbon::now()->format('d M Y H:i:s'),
            'periode' => $periodeLabel,
            'totalPeminjaman' => $totalPeminjaman,
            'peminjamanHariIni' => $peminjamanHariIni,
            'waktuRataRata' => number_format($waktuRataRata, 1),
            'peminjamTeratas' => $peminjamTeratas,
            'sarprasTerpopuler' => $sarprasTerpopuler,
        ];

        $pdf = FacadePdf::loadView('admin.laporan.pdf', $data);
        $filename = 'laporan-' . $periode . '-' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $periode = $request->get('periode', 'perbulan');
        $dateRange = $this->getDateRange($periode);
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        // Hitung data untuk Excel
        $totalPeminjaman = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])->count();
        $peminjamanHariIni = Peminjaman::whereDate('tanggal_pinjam', Carbon::today())->count();
        $waktuRataRata = Peminjaman::selectRaw('AVG(TIMESTAMPDIFF(HOUR, jam_mulai, jam_selesai)) as avg_jam')
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->value('avg_jam') ?? 0;

        // Peminjam teratas
        $peminjamTeratas = Peminjaman::select('id_akun', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('id_akun')
            ->orderByDesc('total')
            ->take(3)
            ->get()
            ->map(function ($peminjam) {
                $user = User::find($peminjam->id_akun);
                return [
                    'nama' => $user->nama ?? 'N/A',
                    'email' => $user->email ?? 'N/A',
                    'jumlah' => $peminjam->total,
                ];
            });

        // Sarpras terpopuler
        $ruanganPopuler = Peminjaman::whereNotNull('id_ruangan')
            ->select('id_ruangan', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('id_ruangan')
            ->orderByDesc('total')
            ->take(3)
            ->get()
            ->map(function ($item) {
                $ruangan = Ruangan::find($item->id_ruangan);
                return [
                    'nama' => $ruangan->nama_ruangan ?? 'N/A',
                    'lokasi' => $ruangan->lokasi->nama_lokasi ?? 'N/A',
                    'jumlah' => $item->total,
                    'type' => 'ruangan',
                ];
            });

        $proyektorPopuler = Peminjaman::whereNotNull('id_proyektor')
            ->select('id_proyektor', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('id_proyektor')
            ->orderByDesc('total')
            ->take(3)
            ->get()
            ->map(function ($item) {
                $proyektor = Proyektor::find($item->id_proyektor);
                return [
                    'nama' => $proyektor->nama_proyektor ?? 'N/A',
                    'lokasi' => $proyektor->lokasi->nama_lokasi ?? 'N/A',
                    'jumlah' => $item->total,
                    'type' => 'proyektor',
                ];
            });

        $sarprasTerpopuler = $ruanganPopuler->merge($proyektorPopuler)
            ->sortByDesc('jumlah')
            ->take(3)
            ->values();

        $data = [
            'totalPeminjaman' => $totalPeminjaman,
            'peminjamanHariIni' => $peminjamanHariIni,
            'waktuRataRata' => number_format($waktuRataRata, 1),
            'peminjamTeratas' => $peminjamTeratas,
            'sarprasTerpopuler' => $sarprasTerpopuler,
        ];

        $export = new LaporanExport($periode, $data);
        $filename = 'laporan-' . $periode . '-' . date('Y-m-d') . '.xlsx';
        return Excel::download($export, $filename);
    }
}
