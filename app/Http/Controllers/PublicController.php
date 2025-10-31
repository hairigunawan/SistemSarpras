<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Ruangan;
use App\Models\Proyektor;
use App\Models\Status;
use App\Models\Prioritas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        $idStatusTersedia = Status::where('nama_status', 'Tersedia')->first()->id_status ?? null;

        $ruanganTersedia = Ruangan::where('id_status', $idStatusTersedia)->orderBy('nama_ruangan', 'asc')->get();
        $proyektorTersedia = Proyektor::where('id_status', $idStatusTersedia)->orderBy('nama_proyektor', 'asc')->get();
        $prioritasOptions = Prioritas::orderBy('nama_prioritas', 'asc')->get();

        return view('public.peminjaman.create', compact(
            'ruanganTersedia',
            'proyektorTersedia',
            'selectedSarprasType',
            'selectedSarprasId',
            'prioritasOptions'
        ));
    }


    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'id_ruangan' => 'nullable|exists:ruangans,id_ruangan',
            'id_proyektor' => 'nullable|exists:proyektors,id_proyektor',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'nomor_whatsapp' => 'required|string|max:15',
            'jumlah_peserta' => 'required|integer|min:1',
            'jenis_kegiatan' => 'required|string|max:500',
        ]);

        if (empty($request->id_ruangan) && empty($request->id_proyektor)) {
            return back()->withErrors(['id_sarpras' => 'Pilih salah satu Ruangan atau Proyektor.'])->withInput();
        }
        if (!empty($request->id_ruangan) && !empty($request->id_proyektor)) {
            return back()->withErrors(['id_sarpras' => 'Hanya boleh memilih satu Ruangan atau Proyektor.'])->withInput();
        }

        $isRuangan = !empty($request->id_ruangan);

        $isBentrok = Peminjaman::where(function ($query) use ($request, $isRuangan) {
            if ($isRuangan) {
                $query->where('id_ruangan', $request->id_ruangan);
            } else {
                $query->where('id_proyektor', $request->id_proyektor);
            }
        })
            ->whereIn('status_peminjaman', ['Disetujui', 'Menunggu'])
            ->where(function ($query) use ($request) {
                $pinjam_mulai = "{$request->tanggal_pinjam} {$request->jam_mulai}";
                $pinjam_selesai = "{$request->tanggal_kembali} {$request->jam_selesai}";

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
            'id_ruangan' => $request->id_ruangan,
            'id_proyektor' => $request->id_proyektor,
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
        ]);

        return redirect()->route('public.peminjaman.daftarpeminjaman')->with('success', 'Peminjaman berhasil diajukan.');
    }

    // App/Http/Controllers/PublicController.php
    public function getApprovedDates($type, $id)
    {
        try {
            $query = Peminjaman::with(['user']);

            if ($type === 'ruangan') {
                $query->where('id_ruangan', $id)->with('ruangan');
            } elseif ($type === 'proyektor') {
                $query->where('id_proyektor', $id)->with('proyektor');
            } else {
                return response()->json(['error' => 'Invalid type specified.'], 400);
            }

            $peminjaman = $query->where('status_peminjaman', 'Disetujui')
                ->get();

            $approvedDetails = [];

            foreach ($peminjaman as $item) {
                $startDate = Carbon::parse($item->tanggal_pinjam);
                $endDate = Carbon::parse($item->tanggal_kembali);

                for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                    $dateKey = $date->format('Y-m-d');

                    $approvedDetails[$dateKey][] = [
                        'peminjam_nama' => $item->user->name ?? $item->nama_peminjam,
                        'jam_mulai' => Carbon::parse($item->jam_mulai)->format('H:i'),
                        'jam_selesai' => Carbon::parse($item->jam_selesai)->format('H:i'),
                        'jenis_kegiatan' => $item->jenis_kegiatan,
                        'jumlah_peserta' => $item->jumlah_peserta
                    ];
                }
            }

            return response()->json(['approvedDetails' => $approvedDetails]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function daftarpeminjaman()
    {
        $peminjaman = Peminjaman::latest()->get();
        return view('public.peminjaman.daftarpeminjaman', compact('peminjaman'));
    }

   public function halamansarpras(Request $request)
    {
        // Mengambil data ruangan dengan relasi status dan lokasi
        $ruangans = Ruangan::with('status', 'lokasi')->get();

        // Mengambil data proyektor dengan relasi status
        $proyektors = Proyektor::with('status')->get();

        return view('public.user.halamansarpras', compact('ruangans', 'proyektors'));
    }

    /**
     * Menampilkan detail ruangan untuk publik
     */
    public function detailRuangan(Ruangan $ruangan)
    {
        $ruangan->load('status', 'lokasi');
        return view('public.user.detail_ruangan', compact('ruangan'));
    }

    /**
     * Menampilkan detail proyektor untuk publik
     */
    public function detailProyektor(Proyektor $proyektor)
    {
        $proyektor->load('status');
        return view('public.user.detail_proyektor', compact('proyektor'));
    }
    // ✅ Detail sarpras
    public function detail_sarpras($type, $id)
    {
        if ($type === 'ruangan') {
            $sarpras = Ruangan::with('lokasi', 'status')->findOrFail($id);
        } elseif ($type === 'proyektor') {
            $sarpras = Proyektor::with('status')->findOrFail($id);
        } else {
            abort(404, 'Sarana tidak ditemukan.');
        }

        return view('public.user.detail_sarpras', compact('sarpras', 'type'));
    }


    public function destroyPeminjaman(Peminjaman $peminjaman)
    {
        // Ensure only the owner can delete
        if (Auth::id() !== $peminjaman->id_akun) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus peminjaman ini.');
        }

        $peminjaman->delete();

        return redirect()->route('public.peminjaman.daftarpeminjaman')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function showProfile()
    {
        $user = Auth::user();
        // Pastikan bahwa nama yang digunakan adalah 'nama' bukan 'name'
        return view('public.profile', compact('user'));
    }
}
