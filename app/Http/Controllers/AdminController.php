<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\Peminjaman;
use App\Models\Proyektor;
use App\Helpers\ProyektorStatusHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Helper method untuk mendapatkan date range berdasarkan periode
     */
    private function getDateRange($periode)
    {
        $now = Carbon::now();

        switch ($periode) {
            case 'minggu':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                break;
            case 'bulan':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;
            case 'semester':
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
     * Get periode label untuk ditampilkan di UI
     */
    private function getPeriodeLabel($periode)
    {
        $now = Carbon::now();

        switch ($periode) {
            case 'minggu':
                return 'Minggu Ini (' . $now->format('d M Y') . ')';
            case 'bulan':
                return 'Bulan ' . $now->format('F Y');
            case 'semester':
                $month = $now->month;
                $year = $now->year;
                $semester = $month <= 6 ? 1 : 2;
                return "Semester {$semester} {$year}";
            default:
                return 'Bulan ' . $now->format('F Y');
        }
    }

    /**
     * Get chart data untuk dashboard dengan opsi periode
     */
    private function getChartData($periode = 'minggu')
    {
        $dateRange = $this->getDateRange($periode);
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        $chartData = [];

        if ($periode === 'minggu') {
            // Data per hari dalam minggu
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            for ($i = 0; $i < 7; $i++) {
                $date = $startDate->copy()->addDays($i);
                $ruanganCount = Peminjaman::whereNotNull('id_ruangan')
                    ->whereDate('tanggal_pinjam', $date)
                    ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
                    ->count();

                $proyektorCount = Peminjaman::whereNotNull('id_proyektor')
                    ->whereDate('tanggal_pinjam', $date)
                    ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
                    ->count();

                $chartData[] = [
                    'label' => $days[$i],
                    'ruangan' => $ruanganCount,
                    'proyektor' => $proyektorCount,
                ];
            }
        } else if ($periode === 'bulan') {
            // Data per minggu dalam bulan (max 5 minggu)
            $weeks = 5;
            for ($i = 0; $i < $weeks; $i++) {
                $weekStart = $startDate->copy()->addWeeks($i);
                $weekEnd = $weekStart->copy()->endOfWeek();

                if ($weekStart > $endDate) {
                    break;
                }

                if ($weekEnd > $endDate) {
                    $weekEnd = $endDate;
                }

                $ruanganCount = Peminjaman::whereNotNull('id_ruangan')
                    ->whereBetween('tanggal_pinjam', [$weekStart, $weekEnd])
                    ->count();

                $proyektorCount = Peminjaman::whereNotNull('id_proyektor')
                    ->whereBetween('tanggal_pinjam', [$weekStart, $weekEnd])
                    ->count();

                $chartData[] = [
                    'label' => 'Minggu ' . ($i + 1),
                    'ruangan' => $ruanganCount,
                    'proyektor' => $proyektorCount,
                ];
            }
        } else if ($periode === 'semester') {
            // Data per bulan dalam semester (6 bulan)
            // Fix: Update the $months array to include all 12 months
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $startMonth = $startDate->month;

            for ($i = 0; $i < 6; $i++) {
                $monthDate = $startDate->copy()->addMonths($i);
                $monthStart = $monthDate->copy()->startOfMonth();
                $monthEnd = $monthDate->copy()->endOfMonth();

                if ($monthEnd > $endDate) {
                    $monthEnd = $endDate;
                }

                $ruanganCount = Peminjaman::whereNotNull('id_ruangan')
                    ->whereBetween('tanggal_pinjam', [$monthStart, $monthEnd])
                    ->count();

                $proyektorCount = Peminjaman::whereNotNull('id_proyektor')
                    ->whereBetween('tanggal_pinjam', [$monthStart, $monthEnd])
                    ->count();

                $chartData[] = [
                    'label' => $months[($startMonth + $i - 1) % 12],
                    'ruangan' => $ruanganCount,
                    'proyektor' => $proyektorCount,
                ];
            }
        }

        return $chartData;
    }

    /**
     * Get top sarpras berdasarkan periode
     */
    private function getTopSarpras($periode = 'bulan')
    {
        $dateRange = $this->getDateRange($periode);
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        // Top 3 ruangan
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
                    'jumlah' => $item->total,
                    'type' => 'ruangan',
                ];
            });

        // Top 3 proyektor
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
                    'jumlah' => $item->total,
                    'type' => 'proyektor',
                ];
            });

        return [
            'ruangan' => $ruanganPopuler,
            'proyektor' => $proyektorPopuler,
        ];
    }

    /**
     * Get top peminjam berdasarkan periode
     */
    private function getTopPeminjam($periode = 'bulan')
    {
        $dateRange = $this->getDateRange($periode);
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        return Peminjaman::select('id_akun', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('id_akun')
            ->orderByDesc('total')
            ->take(3)
            ->get()
            ->map(function ($peminjam) {
                $user = User::find($peminjam->id_akun);
                return [
                    'nama' => $user->nama ?? 'N/A',
                    'jumlah' => $peminjam->total,
                ];
            });
    }

    /**
     * Menampilkan halaman dashboard admin dengan data ringkasan dan chart.
     */
    public function dashboard(Request $request)
    {
        // Get periode dari request, default ke 'bulan'
        $periode = $request->get('periode', 'bulan');

        // Validasi periode
        if (!in_array($periode, ['minggu', 'bulan', 'semester'])) {
            $periode = 'bulan';
        }

        // Menghitung jumlah data untuk ditampilkan di card statistik
        $jumlah_akun = User::count();
        $jumlah_sarpras = Ruangan::count() + Proyektor::count();
        $peminjaman_menunggu = Peminjaman::where('status_peminjaman', 'Menunggu')->count();
        $peminjaman_disetujui = Peminjaman::where('status_peminjaman', 'Disetujui')->count();

        // Statistik ruangan
        $ruanganTersedia = Ruangan::whereHas('status', function ($query) {
            $query->where('nama_status', 'Tersedia');
        })->count();
        $ruanganTerpakai = Ruangan::whereHas('status', function ($query) {
            $query->where('nama_status', 'Dipinjam');
        })->count();
        $ruanganPerbaikan = Ruangan::whereHas('status', function ($query) {
            $query->where('nama_status', 'Perbaikan');
        })->count();

        // Perbarui status proyektor berdasarkan peminjaman aktif
        ProyektorStatusHelper::updateProyektorStatus();

        // Statistik proyektor
        $proyektorTersedia = Proyektor::whereHas('status', function ($query) {
            $query->where('nama_status', 'Tersedia');
        })->count();
        $proyektorTerpakai = Proyektor::whereHas('status', function ($query) {
            $query->where('nama_status', 'Dipinjam');
        })->count();
        $proyektorPerbaikan = Proyektor::whereHas('status', function ($query) {
            $query->where('nama_status', 'Perbaikan');
        })->count();

        // Mengambil data peminjaman terbaru untuk ditampilkan di tabel
        $peminjaman_terbaru = Peminjaman::with('user', 'ruangan', 'proyektor')->latest()->take(5)->get();

        // Get chart data berdasarkan periode
        $chartData = $this->getChartData($periode);
        $topSarpras = $this->getTopSarpras($periode);
        $topPeminjam = $this->getTopPeminjam($periode);
        $periodeLabel = $this->getPeriodeLabel($periode);

        return view('admin.dashboard.index', compact(
            'jumlah_akun',
            'jumlah_sarpras',
            'peminjaman_menunggu',
            'peminjaman_disetujui',
            'ruanganTersedia',
            'ruanganTerpakai',
            'ruanganPerbaikan',
            'proyektorTersedia',
            'proyektorTerpakai',
            'proyektorPerbaikan',
            'peminjaman_terbaru',
            'chartData',
            'topSarpras',
            'topPeminjam',
            'periode',
            'periodeLabel'
        ));
    }

    /**
     * Method index untuk menampilkan halaman dashboard admin
     */
    public function index(Request $request)
    {
        return $this->dashboard($request);
    }
}
