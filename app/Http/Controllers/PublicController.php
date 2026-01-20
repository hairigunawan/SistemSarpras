<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Ruangan;
use App\Models\Proyektor;
use App\Models\Lokasi;
use App\Models\Status;
use App\Models\Prioritas;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\PeminjamanHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Exports\RiwayatPeminjamanExport;
use Maatwebsite\Excel\Facades\Excel;

class PublicController extends Controller
{
    /**
     * Menampilkan halaman landing publik dengan statistik.
     */
    public function index()
    {
        $totalRuangan = Ruangan::count();
        $totalProyektor = Proyektor::count();

        $statusTersedia = Status::where('nama_status', 'Tersedia')->first();
        $idStatusTersedia = $statusTersedia ? $statusTersedia->id_status : null;

        $statusDipinjam = Status::where('nama_status', 'Dipinjam')->first();
        $idStatusDipinjam = $statusDipinjam ? $statusDipinjam->id_status : null;

        $statusPerbaikan = Status::where('nama_status', 'Perbaikan')->first();
        $idStatusPerbaikan = $statusPerbaikan ? $statusPerbaikan->id_status : null;

        $RuanganTersedia = Ruangan::where('id_status', $idStatusTersedia)->count();
        $RuanganTerpakai = $totalRuangan - $RuanganTersedia;
        $RuanganPerbaikan = Ruangan::where('id_status', $idStatusPerbaikan)->count();

        $ProyektorTersedia = Proyektor::where('id_status',  $idStatusTersedia)->count();
        $ProyektorTerpakai = $totalProyektor - $ProyektorTersedia;
        $ProyektorPerbaikan = Proyektor::where('id_status', $idStatusPerbaikan)->count();

        $p = Peminjaman::with(['ruangan'])
            ->where('status_peminjaman', 'Disetujui')
            ->whereNotNull('id_ruangan')
            ->latest('tanggal_pinjam')
            ->take(3)
            ->get()
            ->map(function ($peminjaman) {
                return [
                    'nama' => $peminjaman->ruangan->nama_ruangan ?? 'N/A',
                    'kelas' => $peminjaman->nama_peminjam ?? 'N/A',
                    'matkul' => $peminjaman->jenis_kegiatan ?? 'N/A',
                    'waktu' => $peminjaman->jam_mulai . ' - ' . $peminjaman->jam_selesai,
                ];
            })->toArray();

        return view('public.beranda.index', compact(
            'RuanganTersedia',
            'RuanganTerpakai',
            'RuanganPerbaikan',
            'ProyektorTersedia',
            'ProyektorTerpakai',
            'ProyektorPerbaikan',
            'p',
            'totalRuangan',
            'totalProyektor',
        ));
    }

    public function createPeminjaman(Request $request)
    {
        $selectedRuanganId = $request->input('id_ruangan');
        $selectedProyektorId = $request->input('id_proyektor');
        $selectedSarprasType = null;
        $selectedSarprasId = null;

        if ($selectedRuanganId) {
            $selectedSarprasType = 'ruangan';
            $selectedSarprasId = $selectedRuanganId;
        } elseif ($selectedProyektorId) {
            $selectedSarprasType = 'proyektor';
            $selectedSarprasId = $selectedProyektorId;
        }

        $resources = PeminjamanHelper::getAvailableResources(true);
        $ruanganTersedia = $resources['ruangan']->sortBy('nama_ruangan');
        $proyektorTersedia = $resources['proyektor']->sortBy('nama_proyektor');

        $allRuangan = Ruangan::with('lokasi')->orderBy('nama_ruangan')->get();

        $prioritasOptions = Prioritas::orderBy('nama_prioritas', 'asc')->get();
        $lokasiList = Lokasi::pluck('nama_lokasi', 'id_lokasi');

        return view('public.peminjaman.create', compact(
            'ruanganTersedia',
            'proyektorTersedia',
            'selectedSarprasType',
            'selectedSarprasId',
            'prioritasOptions',
            'lokasiList',
            'allRuangan'
        ));
    }

    // STORE PUBLIC (KONSISTEN DENGAN PEMINJAMANCONTROLLER)
    public function storePeminjaman(Request $request)
    {
        try {
            // Mapping lokasi jika menggunakan ruangan
            if (!empty($request->id_ruangan)) {
                $lokasiId = Ruangan::find($request->id_ruangan)->lokasi_id;
                $request->merge(['id_lokasi' => $lokasiId]);
            }

            Peminjaman::submit($request);

            $successMessage = 'Peminjaman berhasil diajukan. Menunggu persetujuan admin.';
            return redirect()->route('public.peminjaman.daftarpeminjaman')->with('success', $successMessage);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
    public function daftarpeminjaman()
    {
        $peminjaman = Peminjaman::with(['ruangan', 'proyektor'])
            ->latest()
            ->get();

        // Code lainnya tetap sama...
        $p = Peminjaman::with(['ruangan'])
            ->where('status_peminjaman', 'Disetujui')
            ->whereNotNull('id_ruangan')
            ->latest('tanggal_pinjam')
            ->take(3)
            ->get()
            ->map(function ($peminjaman) {
                return [
                    'nama' => $peminjaman->ruangan->nama_ruangan ?? 'N/A',
                    'kelas' => $peminjaman->nama_peminjam ?? 'N/A',
                    'matkul' => $peminjaman->jenis_kegiatan ?? 'N/A',
                    'waktu' => $peminjaman->jam_mulai . ' - ' . $peminjaman->jam_selesai,
                ];
            })->toArray();

        return view('public.peminjaman.index', compact('peminjaman', 'p'));
    }

    public function halamansarpras(Request $request)
    {
        // 1. Ambil Input Filter
        $jenisSarprasFilter = $request->query('jenis_sarpras', 'all');
        $lokasiRuanganFilter = $request->query('lokasi_ruangan', 'all');

        $r = collect();
        $p = collect();

        if ($jenisSarprasFilter === 'all' || $jenisSarprasFilter === 'ruangan') {
            $queryRuangan = Ruangan::with('status', 'lokasi');

            if ($jenisSarprasFilter === 'ruangan' && $lokasiRuanganFilter !== 'all') {
                $queryRuangan->whereHas('lokasi', function ($q) use ($lokasiRuanganFilter) {
                    $q->where('id_lokasi', $lokasiRuanganFilter);
                });
            }

            $r = $queryRuangan->latest()->paginate(15);
        }

        if ($jenisSarprasFilter === 'all' || $jenisSarprasFilter === 'proyektor') {
            $queryProyektor = Proyektor::with('status');

            $namaStatus = $request->input('nama_status');
            if ($namaStatus) {
                $statusId = Status::where('nama_status', $namaStatus)->value('id_status');
                if ($statusId) {
                    $queryProyektor->where('id_status', $statusId);
                }
            }

            $search = $request->input('search');
            if ($search) {
                $queryProyektor->where('nama_proyektor', 'ilike', "%{$search}%");
            }

            $p = $queryProyektor->latest()->paginate(15);
        }

        $lokasis = Lokasi::orderBy('nama_lokasi', 'asc')->get()->unique('nama_lokasi');
        $statuses = Status::all();

        if ($jenisSarprasFilter === 'proyektor') {
            $lokasiRuanganFilter = 'all';
        }

        return view('public.sarana_perasarana.halamansarpras', compact(
            'r',
            'p',
            'lokasis',
            'statuses',
            'jenisSarprasFilter',
            'lokasiRuanganFilter'
        ));
    }

    public function detail_sarpras($type = null, $id = null)
    {
        if ($type && $id) {
            if ($type === 'ruangan') {
                $sarpras = Ruangan::with(['status', 'lokasi'])->findOrFail($id);
                $mainPeminjaman = Peminjaman::where('id_ruangan', $id)
                    ->whereIn('status_peminjaman', ['Menunggu', 'Dipinjam'])
                    ->latest()
                    ->first();
                $feedbacks = Feedback::with('user')
                    ->where('id_ruangan', $id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } elseif ($type === 'proyektor') {
                $sarpras = Proyektor::with('status')->findOrFail($id);
                $this->checkProyektorStatus($id);
                $sarpras = Proyektor::with('status')->findOrFail($id);
                $mainPeminjaman = Peminjaman::where('id_proyektor', $id)
                    ->whereIn('status_peminjaman', ['Menunggu', 'Dipinjam'])
                    ->latest()
                    ->first();
                $feedbacks = Feedback::with('user')
                    ->where('id_proyektor', $id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } else {
                abort(404, 'Sarana tidak ditemukan.');
            }

            $resourceStatus = $sarpras->status->nama_status ?? 'Tersedia';
            return view('public.sarana_perasarana.detail_sarpras', compact('sarpras', 'type', 'mainPeminjaman', 'resourceStatus', 'feedbacks'));
        }

        $r = Ruangan::with(['status', 'lokasi'])->latest()->paginate(15);
        $p = Proyektor::with('status')->latest()->paginate(15);
        
        // Default filter values for the view
        $jenisSarprasFilter = 'all';
        $lokasiRuanganFilter = 'all';
        $lokasis = Lokasi::orderBy('nama_lokasi', 'asc')->get()->unique('nama_lokasi');
        $statuses = Status::all();

        return view('public.sarana_perasarana.halamansarpras', compact('r', 'p', 'jenisSarprasFilter', 'lokasiRuanganFilter', 'lokasis', 'statuses'));
    }

    public function destroyPeminjaman(Peminjaman $peminjaman)
    {
        if (Auth::id() !== $peminjaman->id_akun) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus peminjaman ini.');
        }

        if ($peminjaman->status_peminjaman === 'Menunggu') {
            $peminjaman->delete();
            return redirect()->route('public.peminjaman.daftarpeminjaman')
                ->with('success', 'Peminjaman berhasil dihapus.');
        } else {
            return redirect()->route('public.peminjaman.daftarpeminjaman')
                ->with('error', 'Peminjaman tidak dapat dihapus karena sudah disetujui atau selesai.');
        }
    }

    public function showProfile(Request $request)
    {
        $user = Auth::user();
        $statusFilter = $request->query('status', 'all');

        $query = Peminjaman::with(['ruangan', 'proyektor'])
            ->where('id_akun', $user->id_akun)
            ->latest();

        if ($statusFilter !== 'all') {
            $query->where('status_peminjaman', $statusFilter);
        }

        $peminjaman = $query->get();

        return view('public.profile.index', compact('user', 'peminjaman'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('public.profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nomor_telepon' => 'required|string|regex:/^08[0-9]{8,12}$/|unique:users,nomor_telepon,' . $user->id_akun . ',id_akun',
        ], [
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'nomor_telepon.string' => 'Nomor telepon harus berupa teks.',
            'nomor_telepon.regex' => 'Format nomor telepon tidak valid. Harus dimulai dengan 08 dan diikuti 10-12 digit angka.',
            'nomor_telepon.unique' => 'Nomor telepon ini sudah digunakan oleh akun lain.',
        ]);

        $user->nomor_telepon = $request->nomor_telepon;
        $user->save();

        return redirect()->route('public.profile.index')->with('success', 'Nomor telepon berhasil diperbarui!');
    }

    public function riwayat_peminjaman()
    {

        $peminjaman = Peminjaman::where('id_akun', Auth::id())->with(['ruangan', 'proyektor'])->latest()->get();

        $p = Peminjaman::with(['ruangan'])
            ->where('status_peminjaman', 'Disetujui')
            ->whereNotNull('id_ruangan')
            ->latest('tanggal_pinjam')
            ->take(3)
            ->get()
            ->map(function ($peminjaman) {
                return [
                    'nama' => $peminjaman->ruangan->nama_ruangan ?? 'N/A',
                    'kelas' => $peminjaman->nama_peminjam ?? 'N/A',
                    'matkul' => $peminjaman->jenis_kegiatan ?? 'N/A',
                    'waktu' => $peminjaman->jam_mulai . ' - ' . $peminjaman->jam_selesai,
                ];
            })->toArray();

        return view('public.peminjaman.riwayat_peminjaman', compact('peminjaman', 'p'));
    }
    public function downloadPdf()
    {
        $peminjaman = Peminjaman::where('id_akun', Auth::id())->with(['ruangan', 'proyektor'])->latest()->get();
        $user = Auth::user();
        $tanggalCetak = Carbon::now()->translatedFormat('d F Y H:i:s');

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('peminjaman', 'user', 'tanggalCetak'));
        return $pdf->download('riwayat_peminjaman_' . $user->nama . '.pdf');
    }

    public function exportExcel()
    {
        $peminjaman = Peminjaman::where('id_akun', Auth::id())->with(['ruangan', 'proyektor'])->latest()->get();
        $user = Auth::user();
        $tanggalCetak = Carbon::now()->translatedFormat('d F Y H:i:s');

        $export = new RiwayatPeminjamanExport($peminjaman, $user, $tanggalCetak);
        $filename = 'riwayat_peminjaman_' . $user->nama . '_' . date('Y-m-d') . '.xlsx';
        return Excel::download($export, $filename);
    }

    public function printRiwayat()
    {
        $peminjaman = Peminjaman::where('id_akun', Auth::id())->with(['ruangan', 'proyektor'])->latest()->get();
        $user = Auth::user();
        $tanggalCetak = Carbon::now()->translatedFormat('d F Y H:i:s');

        return view('admin.laporan.pdf', compact('peminjaman', 'user', 'tanggalCetak'));
    }

    public function tentang_kami()
    {
        return view('public.tentang_kami.index');
    }
}
