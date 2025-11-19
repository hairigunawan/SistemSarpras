<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\Proyektor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Helpers\PeminjamanHelper;
use App\Services\FonnteService;

class PeminjamanController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Peminjaman::with(['user', 'ruangan', 'proyektor']);

        if ($status !== 'all') {
            $query->where('status_peminjaman', $status);
        }

        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('ruangan', function ($qr) use ($search) {
                    $qr->where('nama_ruangan', 'like', "%{$search}%");
                })->orWhereHas('proyektor', function ($qp) use ($search) {
                    $qp->where('nama_proyektor', 'like', "%{$search}%");
                })->orWhereHas('user', function ($qu) use ($search) {
                    $qu->where('nama', 'like', "%{$search}%");
                })->orWhere('nama_peminjam', 'like', "%{$search}%");

            });
        }

        $role = optional(Auth::user()->userRole)->nama_role ?? '';
        $peminjaman = $query->latest()->get();

        return view('admin.peminjaman.index', compact('peminjaman', 'role', 'status'));
    }


    public function lihat_peminjaman($id)
    {
        $mainPeminjaman = Peminjaman::with(['ruangan', 'proyektor', 'user'])
            ->findOrFail($id);

        // Ambil semua peminjaman yang konflik (termasuk yang sudah disetujui)
        $conflictingPeminjaman = Peminjaman::where(function ($query) use ($mainPeminjaman) {
            if ($mainPeminjaman->id_ruangan) {
                $query->where('id_ruangan', $mainPeminjaman->id_ruangan);
            } elseif ($mainPeminjaman->id_proyektor) {
                $query->where('id_proyektor', $mainPeminjaman->id_proyektor);
            }
        })
            ->where('id_peminjaman', '!=', $id)
            ->where(function ($query) use ($mainPeminjaman) {
                $query->where('tanggal_pinjam', '<=', $mainPeminjaman->tanggal_kembali);
                $query->where('tanggal_kembali', '>=', $mainPeminjaman->tanggal_pinjam);
                $query->where('jam_mulai', '<', $mainPeminjaman->jam_selesai);
                $query->where('jam_selesai', '>', $mainPeminjaman->jam_mulai);

            })
            ->with(['ruangan', 'proyektor', 'user', 'lokasi'])
            ->get();

        $candidates = collect([$mainPeminjaman])->merge($conflictingPeminjaman);


        return view('admin.peminjaman.lihat_peminjaman', [
            'mainPeminjaman' => $mainPeminjaman,
            'rankedPeminjaman' => $conflictingPeminjaman
        ]);
    }

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

    public function approve(Request $request, $id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Cek apakah status saat ini masih Menunggu
            if ($peminjaman->status_peminjaman !== 'Menunggu') {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Peminjaman tidak dapat disetujui karena status saat ini bukan Menunggu.',
                        'redirect' => route('admin.peminjaman.index')
                    ], 422);
                }
                return redirect()->route('admin.peminjaman.index')->with('error', 'Peminjaman tidak dapat disetujui karena status saat ini bukan Menunggu.');
            }

            // Update status dengan explicitly mass assignment
            $peminjaman->status_peminjaman = 'Disetujui';
            $peminjaman->save();

            // Update status sumber daya (ruangan dan/atau proyektor) menjadi Dipakai
            if ($peminjaman->id_ruangan) {
                PeminjamanHelper::updateResourceStatus($peminjaman, 'Disetujui');
            }
            if ($peminjaman->id_proyektor) {
                PeminjamanHelper::updateResourceStatus($peminjaman, 'Disetujui');
            }

            // Otomatis tolak peminjaman yang konflik
            PeminjamanHelper::autoRejectConflictingPeminjaman($peminjaman);

            // Kirim notifikasi WhatsApp
            $sarpras = 'Tidak Diketahui';
            if ($peminjaman->ruangan) {
                $sarpras = $peminjaman->ruangan->nama_ruangan;
            } elseif ($peminjaman->proyektor) {
                $sarpras = $peminjaman->proyektor->nama_proyektor;
            }

            $message = "✅ Pengajuan Disetujui\n"
                . "Sarpras: $sarpras\n"
                . "Tanggal: {$peminjaman->tanggal_pinjam} - {$peminjaman->tanggal_kembali}\n"
                . "Waktu: {$peminjaman->jam_mulai} - {$peminjaman->jam_selesai}\n";

            $this->sendWaToPeminjam($peminjaman->nomor_whatsapp, $message);

            // Log untuk debugging
            Log::info('Peminjaman approved', [
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'status_sebelumnya' => 'Menunggu',
                'status_setelahnya' => 'Disetujui',
                'id_ruangan' => $peminjaman->id_ruangan,
                'id_proyektor' => $peminjaman->id_proyektor,
                'user' => Auth::user()->name ?? 'Unknown'
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peminjaman berhasil disetujui.',
                    'redirect' => route('admin.peminjaman.index')
                ]);
            }

            return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil disetujui.');
        } catch (\Exception $e) {
            Log::error('Error approving peminjaman', [
                'id_peminjaman' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyetujui peminjaman.',
                    'redirect' => route('admin.peminjaman.index')
                ], 500);
            }

            return redirect()->route('admin.peminjaman.index')->with('error', 'Terjadi kesalahan saat menyetujui peminjaman.');
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Cek apakah status saat ini masih Menunggu
            if ($peminjaman->status_peminjaman !== 'Menunggu') {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Peminjaman tidak dapat ditolak karena status saat ini bukan Menunggu.',
                        'redirect' => route('admin.peminjaman.index')
                    ], 422);
                }
                return redirect()->route('admin.peminjaman.index')->with('error', 'Peminjaman tidak dapat ditolak karena status saat ini bukan Menunggu.');
            }

            $validatedData = $request->validate([
                'alasan_penolakan' => 'required|string|max:500',
            ]);

            // Update status dan alasan penolakan
            $peminjaman->status_peminjaman = 'Ditolak';
            $peminjaman->alasan_penolakan = $validatedData['alasan_penolakan'];
            $peminjaman->save();

            // Kirim notifikasi WhatsApp
            $this->sendWaToPeminjam(
                $peminjaman->nomor_whatsapp,
                "❌ Pengajuan Ditolak\n"
                    . "Alasan: {$validatedData['alasan_penolakan']}\n"
            );

            // Log untuk debugging
            Log::info('Peminjaman rejected', [
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'status_sebelumnya' => 'Menunggu',
                'status_setelahnya' => 'Ditolak',
                'user' => Auth::user()->name ?? 'Unknown'
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peminjaman berhasil ditolak.',
                    'redirect' => route('admin.peminjaman.index')
                ]);
            }

            return redirect()->route('admin.peminjaman.lihat_peminjaman', $peminjaman->id_peminjaman)->with('success', 'Peminjaman berhasil ditolak.');
        } catch (\Exception $e) {
            Log::error('Error rejecting peminjaman', [
                'id_peminjaman' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menolak peminjaman.',
                    'redirect' => route('admin.peminjaman.index')
                ], 500);
            }

            return redirect()->route('admin.peminjaman.index')->with('error', 'Terjadi kesalahan saat menolak peminjaman.');
        }
    }

    public function complete($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Cek apakah status saat ini adalah Disetujui
            if ($peminjaman->status_peminjaman !== 'Disetujui') {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Peminjaman tidak dapat diselesaikan karena status saat ini bukan Disetujui.',
                        'redirect' => route('admin.peminjaman.index')
                    ], 422);
                }
                return redirect()->route('admin.peminjaman.index')->with('error', 'Peminjaman tidak dapat diselesaikan karena status saat ini bukan Disetujui.');
            }

            // Update status
            $peminjaman->status_peminjaman = 'Selesai';
            $peminjaman->save();

            // Update status sumber daya (ruangan dan/atau proyektor) menjadi Tersedia
            if ($peminjaman->id_ruangan) {
                PeminjamanHelper::updateResourceStatus($peminjaman, 'Selesai');
            }
            if ($peminjaman->id_proyektor) {
                PeminjamanHelper::updateResourceStatus($peminjaman, 'Selesai');
            }

            // Kirim notifikasi WhatsApp
            $sarpras = $peminjaman->ruangan->nama_ruangan ?? $peminjaman->proyektor->nama_proyektor ?? 'Tidak Diketahui';
            $this->sendWaToPeminjam(
                $peminjaman->nomor_whatsapp,
                "✅ Peminjaman Selesai\n"
                    . "Sarpras: $sarpras\n"
                    . "Tanggal: {$peminjaman->tanggal_pinjam} - {$peminjaman->tanggal_kembali}\n"
                    . "Waktu: {$peminjaman->jam_mulai} - {$peminjaman->jam_selesai}\n"
            );

            // Log untuk debugging
            Log::info('Peminjaman completed', [
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'status_sebelumnya' => 'Disetujui',
                'status_setelahnya' => 'Selesai',
                'id_ruangan' => $peminjaman->id_ruangan,
                'id_proyektor' => $peminjaman->id_proyektor,
                'user' => Auth::user()->name ?? 'Unknown'
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peminjaman berhasil diselesaikan.',
                    'redirect' => route('admin.peminjaman.index')
                ]);
            }

            return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil diselesaikan.');
        } catch (\Exception $e) {
            Log::error('Error completing peminjaman', [
                'id_peminjaman' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyelesaikan peminjaman.',
                    'redirect' => route('admin.peminjaman.index')
                ], 500);
            }

            return redirect()->route('admin.peminjaman.index')->with('error', 'Terjadi kesalahan saat menyelesaikan peminjaman.');
        }
    }

    public function showRejectForm($id_peminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($id_peminjaman);
        return view('admin.peminjaman.reject_form', compact('peminjaman'));
    }

    /**
     * Mengirim notifikasi WhatsApp ke peminjam.
     *
     * @param string $nomor_whatsapp
     * @param string $message
     * @return void
     */
    private function sendWaToPeminjam(string $nomor_whatsapp, string $message): void
    {
        try {
            FonnteService::sendMessage($nomor_whatsapp, $message);
            Log::info('WhatsApp notification sent', [
                'to' => $nomor_whatsapp,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification', [
                'to' => $nomor_whatsapp,
                'message' => $message,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function approvedDates($type, $idSarpras)
    {
        $query = Peminjaman::where('status_peminjaman', 'Disetujui');

        if ($type !== 'all') {
            if ($type === 'ruangan') {
                $query->where('id_ruangan', $idSarpras);
            } elseif ($type === 'proyektor') {
                $query->where('id_proyektor', $idSarpras);
            }
        }

        $approvedPeminjaman = $query->get();

        $approvedDetails = [];
        foreach ($approvedPeminjaman as $peminjaman) {
            $date = $peminjaman->tanggal_pinjam;
            if (!isset($approvedDetails[$date])) {
                $approvedDetails[$date] = [];
            }
            $approvedDetails[$date][] = [
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'nama_peminjam' => $peminjaman->nama_peminjam,
                'jenis_kegiatan' => $peminjaman->jenis_kegiatan,
                'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
                'tanggal_kembali' => $peminjaman->tanggal_kembali,
                'jam_mulai' => $peminjaman->jam_mulai,
                'jam_selesai' => $peminjaman->jam_selesai,
                'jumlah_peserta' => $peminjaman->jumlah_peserta,
                'sarpras_type' => $peminjaman->id_ruangan ? 'ruangan' : 'proyektor',
                'id_sarpras' => $peminjaman->id_ruangan ?? $peminjaman->id_proyektor,
            ];
        }

        Log::info('Approved Dates API Response', ['approvedDetails' => $approvedDetails]); // Tambahkan logging

        return response()->json(['approvedDetails' => $approvedDetails]);
    }
}
