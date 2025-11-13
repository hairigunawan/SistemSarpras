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
use Carbon\Carbon;
use App\Helpers\PeminjamanHelper;
use App\Helpers\ProyektorStatusHelper;

class PublicController extends Controller
{
    /**
     * Menampilkan halaman landing publik dengan statistik.
     */
    public function index()
    {
        $idStatusTersedia = Status::where('nama_status', 'Tersedia')->first()->id_status ?? null;
        $idStatusDipinjam = Status::where('nama_status', 'Dipinjam')->first()->id_status ?? null;
        $idStatusPerbaikan = Status::where('nama_status', 'Perbaikan')->first()->id_status ?? null;

        $RuanganTersedia = Ruangan::where('id_status', $idStatusTersedia)->count();
        $RuanganTerpakai = Ruangan::where('id_status', $idStatusDipinjam)->count();
        $RuanganPerbaikan = Ruangan::where('id_status', $idStatusPerbaikan)->count();

        $ProyektorTersedia = Proyektor::where('id_status',  $idStatusTersedia)->count();
        $ProyektorTerpakai = Proyektor::where('id_status', $idStatusDipinjam)->count();
        $ProyektorPerbaikan = Proyektor::where('id_status', $idStatusPerbaikan)->count();

        $labs = Peminjaman::with(['ruangan'])
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
            'labs'
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

        // Perbarui status proyektor berdasarkan peminjaman aktif
        ProyektorStatusHelper::updateProyektorStatus();

        // Menggunakan helper untuk mendapatkan sumber daya yang tersedia
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

    public function create()
    {
        // Perbarui status proyektor berdasarkan peminjaman aktif
        ProyektorStatusHelper::updateProyektorStatus();

        // Menggunakan helper untuk mendapatkan sumber daya yang tersedia
        $resources = PeminjamanHelper::getAvailableResources(true);
        $ruanganTersedia = $resources['ruangan']->sortBy('nama_ruangan');
        $proyektorTersedia = $resources['proyektor']->sortBy('nama_proyektor');

        $selectedSarprasType = request('sarpras_type');
        $selectedSarprasId = request('sarpras_id');

        return view('public.peminjaman.create', compact('ruanganTersedia', 'proyektorTersedia', 'selectedSarprasType', 'selectedSarprasId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'jenis_kegiatan' => 'required|string',
            'jumlah_peserta' => 'required|integer|min:1',
            'sarpras_type' => 'required|in:ruangan,proyektor',
            'sarpras_id' => 'required|integer',
            'id_lokasi' => 'nullable|integer',
        ]);

        // Cek konflik menggunakan helper
        $conflictRequest = new \stdClass();
        $conflictRequest->tanggal_pinjam = $request->tanggal_pinjam;
        $conflictRequest->tanggal_kembali = $request->tanggal_kembali;
        $conflictRequest->jam_mulai = $request->jam_mulai;
        $conflictRequest->jam_selesai = $request->jam_selesai;
        $conflictRequest->include_waiting = true;

        if ($request->sarpras_type === 'ruangan') {
            $conflictRequest->id_ruangan = $request->sarpras_id;
        } else {
            $conflictRequest->id_proyektor = $request->sarpras_id;
        }

        if (PeminjamanHelper::checkConflict($conflictRequest)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ruangan dan Proyektor yang dipilih sudah dipinjam atau sedang menunggu persetujuan pada waktu tersebut.');
        }

        $peminjaman = new Peminjaman();
        $peminjaman->nama_peminjam = $request->nama_peminjam;
        $peminjaman->tanggal_pinjam = $request->tanggal_pinjam;
        $peminjaman->tanggal_kembali = $request->tanggal_kembali;
        $peminjaman->jam_mulai = $request->jam_mulai;
        $peminjaman->jam_selesai = $request->jam_selesai;
        $peminjaman->jenis_kegiatan = $request->jenis_kegiatan;
        $peminjaman->jumlah_peserta = $request->jumlah_peserta;
        $peminjaman->status_peminjaman = 'Menunggu';
        $peminjaman->id_akun = Auth::id();

        if ($request->sarpras_type === 'ruangan') {
            $peminjaman->id_ruangan = $request->sarpras_id;
        } else {
            $peminjaman->id_proyektor = $request->sarpras_id;
        }

        if ($request->id_lokasi) {
            $peminjaman->id_lokasi = $request->id_lokasi;
        }

        $peminjaman->save();

        return redirect()->route('public.peminjaman.daftarpeminjaman')->with('success', 'Peminjaman berhasil diajukan. Menunggu persetujuuan admin.');
    }

    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'id_ruangan' => 'nullable|exists:ruangans,id_ruangan',
            'id_proyektor' => 'nullable|exists:proyektors,id_proyektor',
            'lokasi_id' => 'required_with:id_proyektor|nullable|exists:lokasis,id_lokasi',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'nomor_whatsapp' => 'required|string|max:15',
            'jumlah_peserta' => 'required|integer|min:1',
            'jenis_kegiatan' => 'required|string|max:500',
        ]);

        // Menyiapkan data untuk disimpan
        $dataToSave = [
            'id_akun' => Auth::id(),
            'lokasi_id' => $request->lokasi_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'jumlah_peserta' => $request->jumlah_peserta,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'nama_peminjam' => Auth::user()->nama,
            'email_peminjam' => Auth::user()->email,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'status_peminjaman' => 'Menunggu',
        ];

        // Menambahkan ID sumber daya
        if (!empty($request->id_ruangan)) {
            $dataToSave['id_ruangan'] = $request->id_ruangan;
            $dataToSave['id_lokasi'] = Ruangan::find($request->id_ruangan)->lokasi_id;
        }
        if (!empty($request->id_proyektor)) {
            $dataToSave['id_proyektor'] = $request->id_proyektor;
            $dataToSave['id_lokasi'] = $request->lokasi_id;
        }


        // Cek konflik menggunakan helper
        $conflictRequest = new \stdClass();
        $conflictRequest->tanggal_pinjam = $request->tanggal_pinjam;
        $conflictRequest->tanggal_kembali = $request->tanggal_kembali;
        $conflictRequest->jam_mulai = $request->jam_mulai;
        $conflictRequest->jam_selesai = $request->jam_selesai;
        $conflictRequest->include_waiting = true;

        if ( $request->id_ruangan ) {
            $conflictRequest->id_ruangan = $request->id_ruangan;
        }
        if ( $request->id_proyektor ) {
            $conflictRequest->id_proyektor = $request->id_proyektor;
        }

        if (PeminjamanHelper::checkConflict($conflictRequest)) {
            $conflictItems = [];
            if (($request->id_ruangan)) {
                $conflictItems[] = 'Ruangan';
            }
            if (($request->id_proyektor)) {
                $conflictItems[] = 'Proyektor';
            }
            $bentrokMessage = implode(' dan ', $conflictItems) . ' yang dipilih sudah dipinjam atau sedang menunggu persetujuan pada waktu tersebut.';
            return back()->withErrors(['bentrok' => $bentrokMessage])->withInput();
        }

        Peminjaman::create($dataToSave);

        $successMessage = 'Peminjaman berhasil diajukan.';
        if (!empty($request->id_ruangan) && !empty($request->id_proyektor)) {
            $successMessage = 'Peminjaman ruangan dan proyektor berhasil diajukan.';
        } elseif (!empty($request->id_ruangan)) {
            $successMessage = 'Peminjaman ruangan berhasil diajukan.';
        } elseif (!empty($request->id_proyektor)) {
            $successMessage = 'Peminjaman proyektor berhasil diajukan.';
        }

        return redirect()->route('public.peminjaman.daftarpeminjaman')->with('success', $successMessage);
    }

    public function daftarpeminjaman()
    {
        $userId = Auth::id();
        $peminjaman = Peminjaman::where('id_akun', $userId)->with(['ruangan', 'proyektor'])->latest()->get();
        return view('public.peminjaman.daftarpeminjaman', compact('peminjaman'));
    }

    public function halamansarpras(Request $request)
     {
        // Perbarui status proyektor berdasarkan peminjaman aktif
        ProyektorStatusHelper::updateProyektorStatus();

        // Mengambil data ruangan dengan relasi status dan lokasi
        $ruangans = Ruangan::with('status', 'lokasi')->get();

        // Mengambil data proyektor dengan relasi status
        $proyektors = Proyektor::with('status')->get();

        return view('public.sarana_perasarana.halamansarpras', compact('ruangans', 'proyektors'));
    }

    /**
     * Menampilkan detail ruangan untuk publik
     */

    public function detail_sarpras($type = null, $id = null)
    {
        if ($type && $id) {
            if ($type === 'ruangan') {
                $sarpras = Ruangan::with(['status', 'lokasi'])->findOrFail($id);
                // Cari peminjaman terkait ruangan ini
                $mainPeminjaman = Peminjaman::where('id_ruangan', $id)
                    ->whereIn('status_peminjaman', ['Menunggu', 'Dipinjam'])
                    ->latest()
                    ->first();
                // Ambil feedback untuk ruangan ini
                $feedbacks = Feedback::with('user')
                    ->where('id_ruangan', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            } elseif ($type === 'proyektor') {
                $sarpras = Proyektor::with('status')->findOrFail($id);
                // Perbarui status proyektor berdasarkan peminjaman aktif
                ProyektorStatusHelper::checkProyektorStatus($id);
                // Refresh data proyektor setelah status diperbarui
                $sarpras = Proyektor::with('status')->findOrFail($id);
                // Cari peminjaman terkait proyektor ini
                $mainPeminjaman = Peminjaman::where('id_proyektor', $id)
                    ->whereIn('status_peminjaman', ['Menunggu', 'Dipinjam'])
                    ->latest()
                    ->first();
                // Ambil feedback untuk proyektor ini
                $feedbacks = Feedback::with('user')
                    ->where('id_proyektor', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                abort(404, 'Sarana tidak ditemukan.');
            }

            // Ambil status dari sumber daya untuk digunakan di view
            $resourceStatus = $sarpras->status->nama_status ?? 'Tersedia';

            return view('public.sarana_perasarana.detail_sarpras', compact('sarpras', 'type', 'mainPeminjaman', 'resourceStatus', 'feedbacks'));
        }

        $ruangans = Ruangan::with(['status', 'lokasi'])->get();
        $proyektors = Proyektor::with('status')->get();

        return view('public.sarana_perasarana.halamansarpras', compact('ruangans', 'proyektors'));
    }



    public function destroyPeminjaman(Peminjaman $peminjaman)
    {
        // Ensure only the owner can delete
        if (Auth::id() !== $peminjaman->id_akun) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus peminjaman ini.');
        }

        // Hapus peminjaman jika statusnya masih Menunggu
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
}
