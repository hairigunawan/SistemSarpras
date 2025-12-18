<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        return Peminjaman::HalamanUtama($request);
    }

    public function store(Request $request)
    {
        try {
            Peminjaman::submit($request);

            return redirect()->route('public.peminjaman.riwayat_peminjaman')
                ->with('success', 'Pengajuan peminjaman berhasil diajukan. Menunggu persetujuan admin.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function approve(Request $request, $id)
    {
        try {
            $p = Peminjaman::findOrFail($id);

            if ($p->tanggal_pinjam != now()->toDateString()) {
                throw new \Exception('Peminjaman hanya dapat disetujui pada tanggal peminjaman (hari ini).');
            }

            $p->approve();

            return $this->jsonResponse(true, 'Peminjaman berhasil disetujui.');
        } catch (\Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 500);
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $request->validate(['alasan_penolakan' => 'required|string|max:500']);

            $p = Peminjaman::findOrFail($id);
            $p->reject($request->alasan_penolakan);

            return $this->jsonResponse(true, 'Peminjaman berhasil ditolak.');
        } catch (\Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 500);
        }
    }

    public function complete($id)
    {
        try {
            $p = Peminjaman::findOrFail($id);
            $p->complete();

            return $this->jsonResponse(true, 'Peminjaman berhasil diselesaikan.');
        } catch (\Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 500);
        }
    }


    // --- Helper Controller untuk response JSON/Redirect dinamis ---
    private function jsonResponse($success, $message, $code = 200)
    {
        $type = $success ? 'success' : 'error';

        if (request()->expectsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'redirect' => route('admin.peminjaman.index')
            ], $success ? 200 : $code);
        }

        return redirect()->route('admin.peminjaman.index')->with($type, $message);
    }

    // Method lainnya seperti lihat_peminjaman, approvedDates biarkan seperti aslinya
    // atau refactor dengan cara serupa (menggunakan Accessor/Scope).

    public function lihat_peminjaman($id)
    {
        $p = Peminjaman::with(['ruangan', 'proyektor', 'user'])->findOrFail($id);

        return view('admin.peminjaman.lihat_peminjaman', [
            'mainPeminjaman' => $p,
            'rankedPeminjaman' => []
        ]);
    }

    public function addCatatanAdmin(Request $request, $id)
    {
        try {
            $request->validate([
                'catatan_admin' => 'nullable|string|max:1000',
            ]);

            $peminjaman = Peminjaman::findOrFail($id);
            $peminjaman->update(['catatan_admin' => $request->catatan_admin]);

            return back()->with('success', 'Catatan admin berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function showRejectForm($id_peminjaman)
    {
        $p = Peminjaman::findOrFail($id_peminjaman);
        return view('admin.peminjaman.reject_form', compact('p'));
    }

    public function approvedDates($type, $idSarpras)
    {
        return Peminjaman::Approv($type, $idSarpras);
    }

    function riwayat_peminjaman()
    {
        return Peminjaman::Riwayat();
    }
}
