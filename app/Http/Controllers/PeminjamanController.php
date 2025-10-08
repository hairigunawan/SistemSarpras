<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan halaman daftar peminjaman untuk approval
        $status = $request->get('status', 'all'); // Default to 'all' if no status provided

        $query = Peminjaman::with(['user', 'sarpras']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->has(['nama', 'jenis']) && $request->nama != '') {
            $query->where('nama_peminjam', $request->nama);
        }

        if ($request->has('search') && $request->search) {
            $query->whereHas('sarpras', function($q) use ($request) {
                $q->where('nama_sarpras', 'like', '%' . $request->search . '%');
            });
        }

        $peminjaman = $query->latest()->get();

        return view('admin.peminjaman.index', compact('peminjaman', 'status'));
    }

    /**
     * Menyimpan data pengajuan peminjaman baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_sarpras' => 'required|exists:sarpras,id_sarpras',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keterangan' => 'nullable|string',
        ]);

        // Tambahan: Logika untuk cek jadwal bentrok bisa ditambahkan di sini

        Peminjaman::create([
            'id_akun' => Auth::id(),
            'id_sarpras' => $request->id_sarpras,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan' => $request->keterangan,
            'status' => 'Menunggu', // Status default saat pengajuan
        ]);

        // Redirect ke halaman riwayat peminjaman user
        return redirect()->route('peminjaman.riwayat')->with('success', 'Pengajuan peminjaman berhasil dikirim. Mohon tunggu konfirmasi.');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['user', 'sarpras'])->findOrFail($id);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Create start and end datetime for the current peminjaman
        $start1 = Carbon::parse($peminjaman->tanggal_pinjam . ' ' . $peminjaman->jam_mulai);
        $end1 = Carbon::parse($peminjaman->tanggal_kembali . ' ' . $peminjaman->jam_selesai);

        // Find conflicting peminjaman by the same user with overlapping time
        $conflicting = Peminjaman::where('id_akun', $peminjaman->id_akun)
            ->where('status', 'Menunggu')
            ->where('id_peminjaman', '!=', $id)
            ->get()
            ->filter(function($p) use ($start1, $end1) {
                $start2 = Carbon::parse($p->tanggal_pinjam . ' ' . $p->jam_mulai);
                $end2 = Carbon::parse($p->tanggal_kembali . ' ' . $p->jam_selesai);
                return $start1->lt($end2) && $start2->lt($end1);
            });

        // Reject conflicting peminjaman with reason
        foreach ($conflicting as $conflict) {
            $conflict->update([
                'status' => 'Ditolak',
                'alasan_penolakan' => 'Peminjaman ditolak karena bentrok dengan peminjaman yang disetujui pada waktu yang sama.',
            ]);
        }

        // Approve the current peminjaman
        $peminjaman->update(['status' => 'Disetujui']);

        // Update status sarpras menjadi 'Dipinjam'
        $peminjaman->sarpras->update(['status' => 'Dipinjam']);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'Ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function complete($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'Selesai']);

        // Update status sarpras kembali ke 'Tersedia'
        $peminjaman->sarpras->update(['status' => 'Tersedia']);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diselesaikan.');
    }

    // Tambahan: Anda perlu membuat method dan route untuk riwayat peminjaman
    public function riwayat()
    {
        $peminjaman = Peminjaman::where('id_akun', Auth::id())->latest()->get();
        return view('public.peminjaman.riwayat', compact('peminjaman'));
    }
    public function daftareminjaman()
    {
        $user = Auth::user();

        // Hitung total ruangan & proyektor yang pernah dipinjam
        $totalRuangan = Peminjaman::where('id_akun', $user->id_akun ?? null)
            ->whereHas('sarpras', function ($q) {
                $q->where('jenis', 'ruangan');
            })->count();

        $totalProyektor = Peminjaman::where('id_akun', $user->id_akun ?? null)
            ->whereHas('sarpras', function ($q) {
                $q->where('jenis', 'proyektor');
            })->count();

        // Daftar peminjaman aktif user
        $peminjamanAktif = Peminjaman::where('id_akun', $user->id_akun ?? null)
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('peminjaman.peminjaman.daftarpeminjaman', compact('totalRuangan', 'totalProyektor', 'peminjamanAktif'));
    }
}
