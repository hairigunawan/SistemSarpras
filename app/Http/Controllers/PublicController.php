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
use App\Helpers\PeminjamanHelper;
use App\Helpers\ProyektorStatusHelper;
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

        ProyektorStatusHelper::updateProyektorStatus();

        $resources = PeminjamanHelper::getAvailableResources(true);
        $ruanganTersedia = $resources['ruangan']->sortBy('nama_ruangan');
        $proyektorTersedia = $resources['proyektor']->sortBy('nama_proyektor');

        $prioritasOptions = Prioritas::orderBy('nama_prioritas', 'asc')->get();
        $lokasiList = Lokasi::pluck('nama_lokasi', 'id_lokasi');

        return view('public.peminjaman.create', compact(
            'ruanganTersedia',
            'proyektorTersedia',
            'selectedSarprasType',
            'selectedSarprasId',
            'prioritasOptions',
            'lokasiList'
        ));
    }

    // STORE PUBLIC (REFACTORED TO USE MODEL LOGIC)
    public function storePeminjaman(Request $request)
    {
        $validated = $request->validate([
            'id_ruangan' => 'nullable|exists:ruangans,id_ruangan',
            'id_proyektor' => 'nullable|exists:proyektors,id_proyektor',
            'lokasi_id' => 'required_with:id_proyektor|nullable|exists:lokasis,id_lokasi',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'jumlah_peserta' => 'required|integer|min:1',
            'jenis_kegiatan' => 'required|string|max:500',
        ]);

        // Persiapkan data agar sesuai dengan format yang diterima Model
        $dataToSubmit = $validated;

        // Logic mapping lokasi dan sarpras tambahan
        if (!empty($validated['id_ruangan'])) {
            $dataToSubmit['id_lokasi'] = Ruangan::find($validated['id_ruangan'])->lokasi_id;
        } elseif (!empty($validated['id_proyektor'])) {
            $dataToSubmit['id_lokasi'] = $validated['lokasi_id'];
        }

        try {
            // MENGGUNAKAN Peminjaman::submit()
            // Ini akan otomatis mengecek Role User & Status 'Disetujui'
            Peminjaman::submit($dataToSubmit);

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
        // Update status otomatis (jika diperlukan)
        ProyektorStatusHelper::updateProyektorStatus();

        // 1. Ambil Input Filter
        $jenisSarprasFilter = $request->query('jenis_sarpras', 'all');
        $lokasiRuanganFilter = $request->query('lokasi_ruangan', 'all');

        // 2. Siapkan wadah kosong (Collection) agar tidak error di View jika tidak ada data
        $ruangans = collect();
        $proyektors = collect();

        // 3. Logika Filter RUANGAN
        // Jalankan jika user memilih 'all' atau 'ruangan'
        if ($jenisSarprasFilter === 'all' || $jenisSarprasFilter === 'ruangan') {
            $queryRuangan = Ruangan::with('status', 'lokasi');

            // PERBAIKAN DI SINI:
            // Gunakan whereHas untuk memfilter berdasarkan relasi 'lokasi'
            if ($jenisSarprasFilter === 'ruangan' && $lokasiRuanganFilter !== 'all') {
                $queryRuangan->whereHas('lokasi', function ($q) use ($lokasiRuanganFilter) {
                    // 'id_lokasi' ini merujuk ke primary key di tabel lokasis (bukan ruangans)
                    $q->where('id_lokasi', $lokasiRuanganFilter);
                });
            }

            $ruangans = $queryRuangan->get();
        }

        // 4. Logika Filter PROYEKTOR
        // Jalankan jika user memilih 'all' atau 'proyektor'
        if ($jenisSarprasFilter === 'all' || $jenisSarprasFilter === 'proyektor') {
            // Proyektor ditampilkan.
            // Catatan: Jika logika bisnis Anda mengharuskan proyektor sembunyi saat lokasi dipilih,
            // tambahkan kondisi: && $lokasiRuanganFilter === 'all'
            $proyektors = Proyektor::with('status')->get();
        }

        // 5. Data Pendukung untuk Dropdown
        $lokasis = Lokasi::orderBy('nama_lokasi', 'asc')->get()->unique('nama_lokasi');

        // Reset filter lokasi ke 'all' jika user pindah ke tab 'proyektor' (untuk UI saja)
        if ($jenisSarprasFilter === 'proyektor') {
            $lokasiRuanganFilter = 'all';
        }

        return view('public.sarana_perasarana.halamansarpras', compact(
            'ruangans',
            'proyektors',
            'lokasis',
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
                    ->get();
            } elseif ($type === 'proyektor') {
                $sarpras = Proyektor::with('status')->findOrFail($id);
                ProyektorStatusHelper::checkProyektorStatus($id);
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

        $ruangans = Ruangan::with(['status', 'lokasi'])->get();
        $proyektors = Proyektor::with('status')->get();
        return view('public.sarana_perasarana.halamansarpras', compact('ruangans', 'proyektors'));
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

    public function showProfile()
    {
        $user = Auth::user();
        return view('public.profile.index', compact('user'));
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
}
