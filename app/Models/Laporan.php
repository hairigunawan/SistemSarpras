<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Peminjaman;
use App\Models\Proyektor;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\Lokasi;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Laporan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_laporan';
    protected $fillable = [
        'periode',
        'sarpras_terbanyak',
        'ruangan_tersering',
        'jam_rata_rata',
        'file_laporan',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_laporan');
    }

    // Tampilan utama laporan
    public function HalamanUtama(Request $request)
    {
        // Default diseragamkan ke perbulan
        $periode = $request->get('periode', 'perbulan');

        $dateRange = $this->getDateRange($periode);
        $startDate = $dateRange['startDate'];
        $endDate   = $dateRange['endDate'];

        // Menghapus filter status agar data sesuai dengan total input yang masuk (Permintaan)
        $totalPeminjaman = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->count();

        $avgMinutes = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->whereNotNull('jam_mulai')
            ->whereNotNull('jam_selesai')
            ->get(['jam_mulai', 'jam_selesai'])
            ->map(function ($peminjaman) {
                $start = Carbon::parse(
                    $peminjaman->tanggal_pinjam . ' ' . $peminjaman->jam_mulai
                );
                $end = Carbon::parse(
                    $peminjaman->tanggal_pinjam . ' ' . $peminjaman->jam_selesai
                );
                if ($end->lessThan($start)) {
                    $end->addDay();
                }
                return $end->diffInMinutes($start);
            })
            ->avg() ?? 0;

        $waktuRataRata = round(($avgMinutes ?? 0) / 60, 1);


        $peminjamTeratas = Peminjaman::join('users', 'users.id_akun', '=', 'peminjamans.id_akun')
            ->select(
                'users.nama',
                'users.email',
                DB::raw('count(*) as jumlah')
            )
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('users.nama', 'users.email')
            ->orderByDesc('jumlah')
            ->limit(3)
            ->get();

        $ruanganPopuler = Peminjaman::join('ruangans', 'ruangans.id_ruangan', '=', 'peminjamans.id_ruangan')
            ->join('lokasis', 'lokasis.id_lokasi', '=', 'peminjamans.id_lokasi')
            ->select(
                'ruangans.nama_ruangan as nama',
                'lokasis.nama_lokasi as lokasi',
                DB::raw('count(*) as jumlah'),
                DB::raw("'ruangan' as type")
            )
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('ruangans.nama_ruangan', 'lokasis.nama_lokasi')
            ->orderByDesc('jumlah')
            ->get();

        $proyektorPopuler = Peminjaman::join('proyektors', 'proyektors.id_proyektor', '=', 'peminjamans.id_proyektor')
            ->join('lokasis', 'lokasis.id_lokasi', '=', 'peminjamans.id_lokasi')
            ->select(
                'proyektors.nama_proyektor as nama',
                'proyektors.merk',
                'lokasis.nama_lokasi as lokasi',
                DB::raw('count(*) as jumlah'),
                DB::raw("'proyektor' as type")
            )
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy(
                'proyektors.nama_proyektor',
                'proyektors.merk',
                'lokasis.nama_lokasi'
            )
            ->orderByDesc('jumlah')
            ->get();


        $sarprasTerpopuler = $ruanganPopuler->merge($proyektorPopuler)
            ->sortByDesc('jumlah')
            ->values();

        // Pastikan ada ruangan jika memungkinkan
        if ($sarprasTerpopuler->isNotEmpty()) {
            $hasRuangan = $sarprasTerpopuler->take(3)->contains('type', 'ruangan');

            // Jika 3 teratas tidak ada ruangan, tapi ada data ruangan
            if (!$hasRuangan && $ruanganPopuler->isNotEmpty()) {
                // Ambil 2 teratas (yang pasti proyektor karena sorting)
                $topTwo = $sarprasTerpopuler->take(2);
                // Ambil ruangan teratas
                $topRuangan = $ruanganPopuler->first();
                // Gabungkan
                $sarprasTerpopuler = $topTwo->push($topRuangan);
            } else {
                $sarprasTerpopuler = $sarprasTerpopuler->take(3);
            }
        } else {
            $sarprasTerpopuler = collect([]);
        }

        $topSarprasNama = 'N/A';
        $topSarprasKode = 'N/A';

        if ($sarprasTerpopuler->isNotEmpty()) {
            $firstSarpras = $sarprasTerpopuler->first();
            $topSarprasNama = $firstSarpras['nama'];
            $topSarprasKode = $firstSarpras['lokasi'];
        }

        $periodeLabel = $this->getPeriodeLabel($periode);
        $laporan = Laporan::updateOrCreate(
            ['periode' => $periodeLabel],
            [
                'sarpras_terbanyak' => $topSarprasNama,
                'ruangan_tersering' => $topSarprasKode,
                'jam_rata_rata' => sprintf('%.1f', $waktuRataRata),
            ]
        );

        $PeminjamanHariIni = Peminjaman::whereDate(
            'tanggal_pinjam',
            Carbon::now()->toDateString()
        )
        ->count();

        $status = $request->get('status', 'all');
        $query = Peminjaman::with(['user', 'ruangan', 'proyektor']);

        if ($status !== 'all') {
            $query->where('status_peminjaman', $status);
        }

        $Lokasi = Lokasi::all();
        $peminjaman = $query->latest()->get();

        return view('admin.laporan.index', [
            'PeminjamanHariIni' => $PeminjamanHariIni,
            'peminjaman' => $peminjaman,
            'totalPeminjaman' => $totalPeminjaman,
            'waktuRataRata' => $waktuRataRata,
            'peminjamTeratas' => $peminjamTeratas,
            'sarprasTerpopuler' => $sarprasTerpopuler,
            'laporan' => $laporan,
            'status' => $status,
            'periode' => $periode,
            'Lokasi' => $Lokasi,
            'periodeLabel' => $periodeLabel,
        ]);
    }

    // Export PDF
    public function pdf(Request $request)
    {
        // samakan default
        $periode = $request->get('periode', 'perbulan');
        $dateRange = $this->getDateRange($periode);
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];
        $periodeLabel = $this->getPeriodeLabel($periode);

        // Hitung data untuk PDF
        $totalPeminjaman = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->count();
        $peminjamanHariIni = Peminjaman::whereDate('tanggal_pinjam', Carbon::today())
            ->count();

        $durations = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->whereNotNull('jam_mulai')
            ->whereNotNull('jam_selesai')
            ->get(['jam_mulai', 'jam_selesai'])
            ->map(function ($peminjaman) {
                $start = Carbon::parse($peminjaman->jam_mulai);
                $end = Carbon::parse($peminjaman->jam_selesai);
                return $end->diffInMinutes($start);
            });

        $avgMinutes = $durations->avg() ?? 0;
        $waktuRataRata = $avgMinutes / 60;

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

        $ruanganPopuler = Peminjaman::whereNotNull('id_ruangan')
            ->select('id_ruangan', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('id_ruangan')
            ->orderByDesc('total')
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
            ->values();

        if ($sarprasTerpopuler->isNotEmpty()) {
            $hasRuangan = $sarprasTerpopuler->take(3)->contains('type', 'ruangan');
            if (!$hasRuangan && $ruanganPopuler->isNotEmpty()) {
                $topTwo = $sarprasTerpopuler->take(2);
                $topRuangan = $ruanganPopuler->first();
                $sarprasTerpopuler = $topTwo->push($topRuangan);
            } else {
                $sarprasTerpopuler = $sarprasTerpopuler->take(3);
            }
        }

        $peminjaman = Peminjaman::with(['user', 'ruangan', 'proyektor'])
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->latest()
            ->get();

        $data = [
            'laporan' => Laporan::where('periode', $periodeLabel)->latest()->first(),
            'tanggalCetak' => Carbon::now()->format('d M Y H:i:s'),
            'periode' => $periodeLabel,
            'totalPeminjaman' => $totalPeminjaman,
            'peminjamanHariIni' => $peminjamanHariIni,
            'waktuRataRata' => number_format($waktuRataRata, 1),
            'peminjamTeratas' => $peminjamTeratas,
            'sarprasTerpopuler' => $sarprasTerpopuler,
            'peminjaman' => $peminjaman,
        ];

        $pdf = FacadePdf::loadView('admin.laporan.pdf', $data);
        $filename = 'laporan-' . $periode . '-' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    public function excel(Request $request)
    {
        $periode = $request->get('periode', 'perbulan');
        $dateRange = $this->getDateRange($periode);
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        $totalPeminjaman = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])->count();
        $peminjamanHariIni = Peminjaman::whereDate('tanggal_pinjam', Carbon::today())->count();

        $durations = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->whereNotNull('jam_mulai')
            ->whereNotNull('jam_selesai')
            ->get(['jam_mulai', 'jam_selesai'])
            ->map(function ($peminjaman) {
                $start = Carbon::parse($peminjaman->jam_mulai);
                $end = Carbon::parse($peminjaman->jam_selesai);
                return $end->diffInMinutes($start);
            });

        $avgMinutes = $durations->avg() ?? 0;
        $waktuRataRata = $avgMinutes / 60;

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

        if ($sarprasTerpopuler->isNotEmpty()) {
            $hasRuangan = $sarprasTerpopuler->take(3)->contains('type', 'ruangan');
            if (!$hasRuangan && $ruanganPopuler->isNotEmpty()) {
                $topTwo = $sarprasTerpopuler->take(2);
                $topRuangan = $ruanganPopuler->first();
                $sarprasTerpopuler = $topTwo->push($topRuangan);
            } else {
                $sarprasTerpopuler = $sarprasTerpopuler->take(3);
            }
        }

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

    // Mengembalikan range tanggal yang inklusif (startOfDay/endOfDay)
    private function getDateRange($periode)
    {
        $now = Carbon::now();

        switch ($periode) {
            case 'perbulan':
                $startDate = $now->copy()->startOfMonth()->startOfDay();
                $endDate = $now->copy()->endOfMonth()->endOfDay();
                break;
            case 'persemester':
                $month = $now->month;
                $year = $now->year;

                if ($month <= 6) {
                    $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, 6, 30)->endOfDay();
                } else {
                    $startDate = Carbon::createFromDate($year, 7, 1)->startOfDay();
                    $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();
                }
                break;
            default:
                $startDate = $now->copy()->startOfMonth()->startOfDay();
                $endDate = $now->copy()->endOfMonth()->endOfDay();
        }

        return ['startDate' => $startDate, 'endDate' => $endDate];
    }

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
}
