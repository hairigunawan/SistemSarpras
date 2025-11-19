<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\FonnteService; // <<— WAJIB TAMBAH

class PeminjamanController extends Controller
{
    /**
     * INDEX
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Peminjaman::with(['user', 'ruangan', 'proyektor']);

        if ($status !== 'all') {
            $query->where('status_peminjaman', $status);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('ruangan', fn($qr) => $qr->where('nama_ruangan', 'like', "%$search%"))
                    ->orWhereHas('proyektor', fn($qp) => $qp->where('nama_proyektor', 'like', "%$search%"));
            });
        }

        $role = optional(Auth::user()->userRole)->nama_role ?? '';
        $peminjaman = $query->latest()->get();

        return view('admin.peminjaman.index', compact('peminjaman', 'role', 'status'));
    }

    /**
     * DETAIL PEMINJAMAN
     */
    public function lihat_peminjaman($id)
    {
        $mainPeminjaman = Peminjaman::with(['ruangan', 'proyektor', 'user'])
            ->findOrFail($id);

        $conflicts = Peminjaman::where(function ($q) use ($mainPeminjaman) {
                if ($mainPeminjaman->id_ruangan) {
                    $q->where('id_ruangan', $mainPeminjaman->id_ruangan);
                } else {
                    $q->where('id_proyektor', $mainPeminjaman->id_proyektor);
                }
            })
            ->where('id_peminjaman', '!=', $id)
            ->whereIn('status_peminjaman', ['Menunggu', 'Disetujui'])
            ->where(function ($q) use ($mainPeminjaman) {
                $start = "{$mainPeminjaman->tanggal_pinjam} {$mainPeminjaman->jam_mulai}";
                $end   = "{$mainPeminjaman->tanggal_kembali} {$mainPeminjaman->jam_selesai}";

                $q->whereRaw("CONCAT(tanggal_kembali,' ',jam_selesai) > ?", [$start])
                    ->whereRaw("CONCAT(tanggal_pinjam,' ',jam_mulai) < ?", [$end]);
            })
            ->with('user')
            ->get();

        return view('admin.peminjaman.lihat_peminjaman', [
            'mainPeminjaman' => $mainPeminjaman,
            'rankedPeminjaman' => $conflicts
        ]);
    }

    /**
     * STORE (pengajuan)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_ruangan'       => 'nullable|exists:ruangans,id_ruangan',
            'id_proyektor'     => 'nullable|exists:proyektors,id_proyektor',
            'tanggal_pinjam'   => 'required|date|after_or_equal:today',
            'tanggal_kembali'  => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_mulai'        => 'required|date_format:H:i',
            'jam_selesai'      => 'required|date_format:H:i|after:jam_mulai',
            'nomor_whatsapp'   => 'required|string|max:20',
            'jumlah_peserta'   => 'required|integer|min:1',
            'jenis_kegiatan'   => 'required|string|max:500',
        ]);

        // Ubah 08 → 628 (WAJIB)
        $validated['nomor_whatsapp'] = preg_replace('/^0/', '62', $validated['nomor_whatsapp']);

        // HARUS PILIH SATU SARPRAS
        if (empty($validated['id_ruangan']) && empty($validated['id_proyektor'])) {
            return back()->withErrors([
                'id_sarpras' => 'Pilih ruangan atau proyektor.'
            ])->withInput();
        }

        if (!empty($validated['id_ruangan']) && !empty($validated['id_proyektor'])) {
            return back()->withErrors([
                'id_sarpras' => 'Hanya boleh memilih salah satu sarpras.'
            ]);
        }

        $isRuangan = !empty($validated['id_ruangan']);

        // CHECK BENTROK
        $isBentrok = Peminjaman::where(function ($q) use ($validated, $isRuangan) {
                if ($isRuangan) {
                    $q->where('id_ruangan', $validated['id_ruangan']);
                } else {
                    $q->where('id_proyektor', $validated['id_proyektor']);
                }
            })
            ->whereIn('status_peminjaman', ['Menunggu', 'Disetujui'])
            ->where(function ($q) use ($validated) {
                $start = "{$validated['tanggal_pinjam']} {$validated['jam_mulai']}";
                $end   = "{$validated['tanggal_kembali']} {$validated['jam_selesai']}";

                $q->whereRaw("CONCAT(tanggal_kembali,' ',jam_selesai) > ?", [$start])
                    ->whereRaw("CONCAT(tanggal_pinjam,' ',jam_mulai) < ?", [$end]);
            })
            ->exists();

        if ($isBentrok) {
            $this->sendWaToPeminjam(
                $validated['nomor_whatsapp'],
                "❌ Peminjaman Gagal\nJadwal bentrok dengan peminjaman lain."
            );

            return back()->withErrors(['tanggal' => 'Jadwal bentrok.'])->withInput();
        }

        Peminjaman::create([
            'id_akun'         => Auth::id(),
            'id_ruangan'      => $validated['id_ruangan'],
            'id_proyektor'    => $validated['id_proyektor'],
            'tanggal_pinjam'  => $validated['tanggal_pinjam'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'jam_mulai'       => $validated['jam_mulai'],
            'jam_selesai'     => $validated['jam_selesai'],
            'jumlah_peserta'  => $validated['jumlah_peserta'],
            'jenis_kegiatan'  => $validated['jenis_kegiatan'],
            'nama_peminjam'   => Auth::user()->name,
            'email_peminjam'  => Auth::user()->email,
            'nomor_whatsapp'  => $validated['nomor_whatsapp'],
            'status_peminjaman' => 'Menunggu',
        ]);

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Pengajuan dikirim.');
    }

    /**
     * APPROVE
     */
    public function approve($id)
    {
        $this->authorizeAdmin();

        $p = Peminjaman::findOrFail($id);

        if ($p->status_peminjaman === 'Disetujui') {
            return back()->with('warning', 'Sudah disetujui sebelumnya.');
        }

        // CEK BENTROK
        $isBentrok = Peminjaman::where(function ($q) use ($p) {
                if ($p->id_ruangan) $q->where('id_ruangan', $p->id_ruangan);
                if ($p->id_proyektor) $q->where('id_proyektor', $p->id_proyektor);
            })
            ->where('id_peminjaman', '!=', $id)
            ->where('status_peminjaman', 'Disetujui')
            ->where(function ($q) use ($p) {
                $start = "{$p->tanggal_pinjam} {$p->jam_mulai}";
                $end   = "{$p->tanggal_kembali} {$p->jam_selesai}";

                $q->whereRaw("CONCAT(tanggal_kembali,' ',jam_selesai) > ?", [$start])
                    ->whereRaw("CONCAT(tanggal_pinjam,' ',jam_mulai) < ?", [$end]);
            })
            ->exists();

        if ($isBentrok) {
            $this->sendWaToPeminjam(
                $p->nomor_whatsapp,
                "⚠ Pengajuan Tidak Bisa Disetujui\nKarena jadwal bentrok dengan peminjaman lain."
            );

            return back()->withErrors(['error' => 'Tidak dapat disetujui karena bentrok.']);
        }

        // SETUJUI
        $p->update(['status_peminjaman' => 'Disetujui']);

        $sarpras = $p->ruangan->nama_ruangan ?? $p->proyektor->nama_proyektor;

        $this->sendWaToPeminjam(
            $p->nomor_whatsapp,
            "✅ Pengajuan Disetujui\n"
            . "Sarpras: $sarpras\n"
            . "Tanggal: {$p->tanggal_pinjam} - {$p->tanggal_kembali}\n"
            . "Waktu: {$p->jam_mulai} - {$p->jam_selesai}\n"
        );

        return back()->with('success', 'Pengajuan disetujui.');
    }

    /**
     * REJECT
     */
    public function reject(Request $request, $id)
    {
        $this->authorizeAdmin();

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        $p = Peminjaman::findOrFail($id);

        $p->update([
            'status_peminjaman' => 'Ditolak',
            'alasan_penolakan'  => $request->alasan_penolakan
        ]);

        $this->sendWaToPeminjam(
            $p->nomor_whatsapp,
            "❌ Pengajuan Ditolak\nAlasan: {$request->alasan_penolakan}"
        );

        return back()->with('success', 'Peminjaman ditolak.');
    }

    /**
     * COMPLETE
     */
    public function complete($id)
    {
        $this->authorizeAdmin();

        $p = Peminjaman::findOrFail($id);
        $p->update(['status_peminjaman' => 'Selesai']);

        return back()->with('success', 'Peminjaman diselesaikan.');
    }

    /**
     * SEND WA — dipakai untuk kirim ke user
     */
    protected function sendWaToPeminjam($nomor, $message)
    {
        try {
            return FonnteService::sendMessage($nomor, $message);

        } catch (\Throwable $e) {
            Log::error('WA Error', ['error' => $e->getMessage()]);
            session()->flash('warning', 'Notifikasi WA gagal dikirim.');
            return null;
        }
    }

    /**
     * ADMIN AUTH
     */
    protected function authorizeAdmin()
    {
        $role = optional(Auth::user()->userRole)->nama_role;

        if (!in_array($role, ['Admin', 'Superadmin'])) {
            abort(403);
        }
    }
}