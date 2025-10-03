<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $peminjaman = $query->latest()->get();

        return view('admin.peminjaman.index', compact('peminjaman', 'status'));
    }
    /**
     * Menampilkan form untuk mengajukan peminjaman.
     */
    public function create()
    {
        // Ambil sarpras yang statusnya 'Tersedia' untuk dipilih
        $sarprasTersedia = Sarpras::where('status', 'Tersedia')->get();
        return view('peminjaman.create', compact('sarprasTersedia'));
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
        $peminjaman->update(['status' => 'Disetujui']);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'Ditolak']);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil ditolak.');
    }

    // Tambahan: Anda perlu membuat method dan route untuk riwayat peminjaman
    // public function riwayat()
    // {
    //     $peminjaman = Peminjaman::where('id_akun', Auth::id())->latest()->get();
    //     return view('peminjaman.riwayat', compact('peminjaman'));
    // }
}
