<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Sarpras;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Menampilkan form untuk mengajukan peminjaman publik.
     */
    public function createPeminjaman()
    {
        // Ambil sarpras yang statusnya 'Tersedia' untuk dipilih
        $sarprasTersedia = Sarpras::where('status', 'Tersedia')->get();
        return view('public.peminjaman.create', compact('sarprasTersedia'));
    }

    /**
     * Menyimpan data pengajuan peminjaman publik baru.
     */
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'email_peminjam' => 'required|email|max:255',
            'telepon_peminjam' => 'required|string|max:20',
            'id_sarpras' => 'required|exists:sarpras,id_sarpras',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keterangan' => 'nullable|string',
        ]);

        // Tambahan: Logika untuk cek jadwal bentrok bisa ditambahkan di sini

        Peminjaman::create([
            'id_akun' => null, // Public peminjaman
            'id_sarpras' => $request->id_sarpras,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan' => $request->keterangan,
            'nama_peminjam' => $request->nama_peminjam,
            'email_peminjam' => $request->email_peminjam,
            'telepon_peminjam' => $request->telepon_peminjam,
            'status' => 'Menunggu', // Status default saat pengajuan
        ]);

        // Redirect ke halaman sukses atau landing
        return redirect()->route('landing')->with('success', 'Pengajuan peminjaman berhasil dikirim. Mohon tunggu konfirmasi via email.');
    }
}
