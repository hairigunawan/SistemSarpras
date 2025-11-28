<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        // Menggunakan Scope 'filter' yang sudah dibuat di Model
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'proyektor'])
            ->filter($request->only(['status', 'search']))
            ->latest()
            ->get();

        $role = optional(Auth::user()->userRole)->nama_role ?? '';
        $status = $request->get('status', 'all');

        return view('admin.peminjaman.index', compact('peminjaman', 'role', 'status'));
    }

    public function store(Request $request)
    {
        // Validasi HTTP tetap di Controller (atau pindahkan ke FormRequest class terpisah)
        $validated = $request->validate([
            'id_ruangan'       => 'nullable|exists:ruangans,id_ruangan',
            'id_proyektor'     => 'nullable|exists:proyektors,id_proyektor',
            'tanggal_pinjam'   => 'required|date|after_or_equal:today',
            'jam_mulai'        => 'required|date_format:H:i',
            'jam_selesai'      => 'required|date_format:H:i|after:jam_mulai',
            'jumlah_peserta'   => 'required|integer|min:1',
            'jenis_kegiatan'   => 'required|string|max:500',
        ]);

        // Validasi Logic Sarpras (Bisa juga dipindah ke Custom Validation Rule)
        if (empty($validated['id_ruangan']) && empty($validated['id_proyektor'])) {
            return back()->withErrors(['id_sarpras' => 'Pilih ruangan atau proyektor.'])->withInput();
        }
        if (!empty($validated['id_ruangan']) && !empty($validated['id_proyektor'])) {
            return back()->withErrors(['id_sarpras' => 'Hanya boleh memilih salah satu sarpras.']);
        }

        try {
            // PANGGIL LOGIKA BISNIS DARI MODEL (OOP: Model bertindak)
            Peminjaman::submit($validated);

            return redirect()->route('admin.peminjaman.index')
                ->with('success', 'Pengajuan dikirim.');
        } catch (\Exception $e) {
            // Tangkap error bisnis (seperti bentrok)
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function approve(Request $request, $id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // ENKAPSULASI: Controller hanya menyuruh "approve", tidak perlu tahu caranya
            $peminjaman->approve();

            return $this->jsonResponse(true, 'Peminjaman berhasil disetujui.');
        } catch (\Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 500);
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $request->validate(['alasan_penolakan' => 'required|string|max:500']);

            $peminjaman = Peminjaman::findOrFail($id);
            $peminjaman->reject($request->alasan_penolakan);

            return $this->jsonResponse(true, 'Peminjaman berhasil ditolak.');
        } catch (\Exception $e) {
            return $this->jsonResponse(false, $e->getMessage(), 500);
        }
    }

    public function complete($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            $peminjaman->complete();

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
        $mainPeminjaman = Peminjaman::with(['ruangan', 'proyektor', 'user'])->findOrFail($id);

        // Logika pencarian kandidat konflik juga bisa dipindah ke Model jika ingin lebih bersih
        // Tapi untuk sekarang kita biarkan, atau gunakan scopeIsConflicting yang sudah dibuat.

        return view('admin.peminjaman.lihat_peminjaman', [
            'mainPeminjaman' => $mainPeminjaman,
            // Ini contoh penggunaan logic di controller yang masih boleh,
            // tapi idealnya dipindah ke Service/Model method 'getConflictingApps()'
            'rankedPeminjaman' => []
        ]);
    }

    public function showRejectForm($id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        return view('admin.peminjaman.reject_form', compact('peminjaman'));
    }

    public function approvedDates($type, $idSarpras)
    {
        $approvedPeminjaman = Peminjaman::with(['user', 'ruangan', 'proyektor'])
            ->where('status_peminjaman', 'Disetujui')
            ->when($type === 'ruangan', function ($query) use ($idSarpras) {
                return $query->where('id_ruangan', $idSarpras);
            })
            ->when($type === 'proyektor', function ($query) use ($idSarpras) {
                return $query->where('id_proyektor', $idSarpras);
            })
            ->get();

        $approvedDetails = [];
        foreach ($approvedPeminjaman as $peminjaman) {
            $date = $peminjaman->tanggal_pinjam;
            if (!isset($approvedDetails[$date])) {
                $approvedDetails[$date] = [];
            }

            $sarprasType = null;
            $sarprasId = null;
            if ($peminjaman->id_ruangan) {
                $sarprasType = 'ruangan';
                $sarprasId = $peminjaman->id_ruangan;
            } elseif ($peminjaman->id_proyektor) {
                $sarprasType = 'proyektor';
                $sarprasId = $peminjaman->id_proyektor;
            }

            $approvedDetails[$date][] = [
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'nama_peminjam' => $peminjaman->user->nama ?? 'N/A', // Ambil nama peminjam dari relasi user
                'jenis_kegiatan' => $peminjaman->jenis_kegiatan,
                'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
                'tanggal_kembali' => $peminjaman->tanggal_pinjam, // Asumsi tanggal kembali sama dengan tanggal pinjam untuk event harian
                'jam_mulai' => $peminjaman->jam_mulai,
                'jam_selesai' => $peminjaman->jam_selesai,
                'jumlah_peserta' => $peminjaman->jumlah_peserta,
                'sarpras_type' => $sarprasType,
                'id_sarpras' => $sarprasId,
            ];
        }

        return response()->json(['approvedDetails' => $approvedDetails]);
    }

    function riwayat_peminjaman()
    {
        $userId = Auth::id();

        $peminjaman = Peminjaman::with(['ruangan', 'proyektor'])
            ->where('id_akun', $userId)
            ->orderBy('created_at', 'desc')
            ->get();


        return view('public.peminjaman.riwayat_peminjaman', compact('peminjaman'));
    }
}
