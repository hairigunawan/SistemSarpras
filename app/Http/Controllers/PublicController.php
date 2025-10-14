<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\CarbonPeriod;

class PublicController extends Controller
{
    /**
     * Menampilkan halaman landing publik dengan statistik.
     */
    public function index()
    {
        $RuanganTersedia = Sarpras::where('jenis_sarpras', 'Ruangan')->where('status', 'Tersedia')->count();
        $RuanganTerpakai = Sarpras::where('jenis_sarpras', 'Ruangan')->where('status', 'Dipinjam')->count();
        $RuanganPerbaikan = Sarpras::where('jenis_sarpras', 'Ruangan')->where('status', 'Perbaikan')->count();

        $ProyektorTersedia = Sarpras::where('jenis_sarpras', 'Proyektor')->where('status', 'Tersedia')->count();
        $ProyektorTerpakai = Sarpras::where('jenis_sarpras', 'Proyektor')->where('status', 'Dipinjam')->count();
        $ProyektorPerbaikan = Sarpras::where('jenis_sarpras', 'Proyektor')->where('status', 'Perbaikan')->count();

        $labs = Peminjaman::with(['sarpras'])
            ->where('status', 'Disetujui')
            ->whereHas('sarpras', function ($query) {
                $query->where('jenis_sarpras', 'Ruangan');
            })
            ->latest('tanggal_pinjam')
            ->take(3)
            ->get()
            ->map(function ($peminjaman) {
                return [
                    'nama' => $peminjaman->sarpras->nama_sarpras,
                    'kelas' => $peminjaman->nama_peminjam ?? 'N/A',
                    'matkul' => $peminjaman->keterangan ?? 'N/A',
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

    /**
     * Menampilkan form untuk mengajukan peminjaman publik.
     */
    public function createPeminjaman(Request $request)
    {
        $selectedSarprasId = $request->input('id_sarpras');
        $sarprasTersedia = Sarpras::where('status', 'Tersedia')->orderBy('nama_sarpras', 'asc')->get();
        $jadwalUntukSarpras = [];

        if ($selectedSarprasId) {
            $peminjamanDisetujui = Peminjaman::where('id_sarpras', $selectedSarprasId)
                ->where('status', 'Disetujui')
                ->get();

            foreach ($peminjamanDisetujui as $peminjaman) {
                $period = CarbonPeriod::create($peminjaman->tanggal_pinjam, $peminjaman->tanggal_kembali);
                foreach ($period as $date) {
                    $jadwalUntukSarpras[] = $date->isoFormat('dddd, D MMMM Y');
                }
            }
            $jadwalUntukSarpras = array_unique($jadwalUntukSarpras);
            sort($jadwalUntukSarpras);
        }

        return view('public.peminjaman.create', compact('sarprasTersedia', 'selectedSarprasId', 'jadwalUntukSarpras'));
    }


    /**
     * Menyimpan data pengajuan peminjaman publik baru.
     */
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'nomor_whatsapp' => 'required|string|max:20|regex:/^08[0-9]{8,12}$/',
            'id_sarpras' => 'required|exists:sarpras,id_sarpras',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'jumlah_peserta' => 'required|integer|min:1', // Validasi untuk jumlah peserta
            'keterangan' => 'nullable|string',
        ]);

        $isBentrok = Peminjaman::where('id_sarpras', $request->id_sarpras)
            ->whereIn('status', ['Disetujui', 'Menunggu'])
            ->where(function ($query) use ($request) {
                $pinjam_mulai = $request->tanggal_pinjam . ' ' . $request->jam_mulai;
                $pinjam_selesai = $request->tanggal_kembali . ' ' . $request->jam_selesai;

                $query->where(function ($q) use ($pinjam_mulai) {
                    $q->whereRaw("CONCAT(tanggal_kembali, ' ', jam_selesai) > ?", [$pinjam_mulai]);
                })->where(function ($q) use ($pinjam_selesai) {
                    $q->whereRaw("CONCAT(tanggal_pinjam, ' ', jam_mulai) < ?", [$pinjam_selesai]);
                });
            })
            ->exists();

        if ($isBentrok) {
            return back()->withErrors([
                'tanggal_pinjam' => 'Jadwal yang Anda pilih bentrok dengan peminjaman lain. Silakan pilih tanggal atau jam yang berbeda.'
            ])->withInput();
        }

        Peminjaman::create([
            'id_akun' => Auth::id(),
            'id_sarpras' => $request->id_sarpras,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'jumlah_peserta' => $request->jumlah_peserta, // Menyimpan jumlah peserta
            'keterangan' => $request->keterangan,
            'nama_peminjam' => Auth::user()->nama,
            'email_peminjam' => Auth::user()->email,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('public.peminjaman.daftarpeminjaman')
            ->with('success', 'Peminjaman berhasil dikirim dan sedang diproses.');
    }

    public function daftarpeminjaman()
    {
        $peminjaman = Peminjaman::latest()->get();
        return view('public.peminjaman.daftarpeminjaman', compact('peminjaman'));
    }

    public function saranaPrasarana()
    {
        $halamansarpras = \App\Models\Halamansarpras::latest()->get();
        return view('public.user.halamansarpras', compact('halamansarpras'));
    }
}
