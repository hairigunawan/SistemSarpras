<?php

namespace App\Helpers;

use App\Models\Peminjaman;
use App\Models\Ruangan;
use App\Models\Proyektor;
use App\Models\Status;
use Carbon\Carbon;

class PeminjamanHelper
{
    /**
     * Cek apakah ada konflik peminjaman untuk sumber daya tertentu
     */
    public static function checkConflict($request, $existingPeminjaman = null)
    {
        $query = Peminjaman::where(function ($q) use ($request) {
            if (isset($request->id_ruangan)) {
                $q->where('id_ruangan', $request->id_ruangan);
            }
            if (isset($request->id_proyektor)) {
                $q->where('id_proyektor', $request->id_proyektor);
            }
            if (isset($request->sarpras_type) && isset($request->sarpras_id)) {
                if ($request->sarpras_type === 'ruangan') {
                    $q->where('id_ruangan', $request->sarpras_id);
                } else {
                    $q->where('id_proyektor', $request->sarpras_id);
                }
            }
        });

        // Filter status yang dianggap konflik
        $statusFilter = ['Disetujui', 'Dipinjam'];

        $query->whereIn('status_peminjaman', $statusFilter);

        // Jika ada peminjaman existing, kecualikan dari pengecekan
        if ($existingPeminjaman) {
            $query->where('id_peminjaman', '!=', $existingPeminjaman->id_peminjaman);
        }

        // Pengecekan konflik waktu yang lebih komprehensif
        $tanggalPinjam = $request->tanggal_pinjam ?? $request->tanggal_pinjam;
        $tanggalKembali = $request->tanggal_kembali ?? $request->tanggal_kembali;
        $jamMulai = $request->jam_mulai ?? $request->jam_mulai;
        $jamSelesai = $request->jam_selesai ?? $request->jam_selesai;

        $query->where(function ($timeQuery) use ($tanggalPinjam, $tanggalKembali, $jamMulai, $jamSelesai) {
            $timeQuery->where(function ($subQuery) use ($tanggalPinjam, $tanggalKembali, $jamMulai, $jamSelesai) {
                // Cek apakah ada tumpang tindih waktu:
                // tanggal_pinjam <= tanggal_kembali_peminjaman_lama AND
                // tanggal_kembali >= tanggal_pinjam_peminjaman_lama AND
                // jam_mulai < jam_selesai_peminjaman_lama AND
                // jam_selesai > jam_mulai_peminjaman_lama
                $subQuery->where('tanggal_pinjam', '<=', $tanggalKembali)
                         ->where('tanggal_kembali', '>=', $tanggalPinjam)
                         ->where('jam_mulai', '<', $jamSelesai)
                         ->where('jam_selesai', '>', $jamMulai);
            });
        });

        return $query->exists();
    }

    /**
     * Update status sumber daya setelah peminjaman disetujui/ditolak
     */
    public static function updateResourceStatus($peminjaman, $status)
    {
        $sarprasId = $peminjaman->id_ruangan ?? $peminjaman->id_proyektor;
        $isRuangan = $peminjaman->id_ruangan !== null;

        if ($status === 'Disetujui') {
            $namaStatus = 'Dipakai';
        } elseif ($status === 'Selesai') {
            $namaStatus = 'Tersedia';
        } else {
            return; // Tidak perlu update status untuk status lain
        }

        $idStatus = Status::where('nama_status', $namaStatus)->first()->id_status;

        if ($isRuangan) {
            Ruangan::where('id_ruangan', $sarprasId)->update(['id_status' => $idStatus]);
        } else {
            Proyektor::where('id_proyektor', $sarprasId)->update(['id_status' => $idStatus]);
        }
    }

    /**
     * Format tanggal dan jam untuk ditampilkan
     */
    public static function formatDateTime($tanggal, $jam)
    {
        return Carbon::parse("{$tanggal} {$jam}")->format('Y-m-d H:i');
    }

    /**
     * Ambil sumber daya yang tersedia untuk peminjaman
     */
    public static function getAvailableResources($includeWaiting = true)
    {
        $idStatusTersedia = Status::where('nama_status', 'Tersedia')->first()->id_status;

        // Dapatkan ID proyektor dan ruangan yang sedang dipinjang (disetujui)
        $approvedRuanganIds = Peminjaman::where('status_peminjaman', 'Disetujui')
            ->whereNotNull('id_ruangan')
            ->pluck('id_ruangan')
            ->toArray();

        $approvedProyektorIds = Peminjaman::where('status_peminjaman', 'Disetujui')
            ->whereNotNull('id_proyektor')
            ->pluck('id_proyektor')
            ->toArray();

        // Mulai query dengan sumber daya yang statusnya tersedia
        $ruanganQuery = Ruangan::where('id_status', $idStatusTersedia);
        $proyektorQuery = Proyektor::where('id_status', $idStatusTersedia);

        // Jika includeWaiting true, tambahkan sumber daya yang sedang menunggu persetujuan
        if ($includeWaiting) {
            $waitingRuanganIds = Peminjaman::where('status_peminjaman', 'Menunggu')
                ->whereNotNull('id_ruangan')
                ->pluck('id_ruangan')
                ->toArray();

            $waitingProyektorIds = Peminjaman::where('status_peminjaman', 'Menunggu')
                ->whereNotNull('id_proyektor')
                ->pluck('id_proyektor')
                ->toArray();

            $ruanganQuery->orWhereIn('id_ruangan', $waitingRuanganIds);
            $proyektorQuery->orWhereIn('id_proyektor', $waitingProyektorIds);
        }

        // Kecualikan sumber daya yang sudah disetujui
        if (!empty($approvedRuanganIds)) {
            $ruanganQuery->whereNotIn('id_ruangan', $approvedRuanganIds);
        }

        if (!empty($approvedProyektorIds)) {
            $proyektorQuery->whereNotIn('id_proyektor', $approvedProyektorIds);
        }

        return [
            'ruangan' => $ruanganQuery->get(),
            'proyektor' => $proyektorQuery->get()
        ];
    }

    /**
     * Otomatis menolak peminjaman yang konflik dengan peminjaman yang sudah disetujui
     */
    public static function autoRejectConflictingPeminjaman($approvedPeminjaman)
    {
        $conflictingPins = Peminjaman::where('id_peminjaman', '!=', $approvedPeminjaman->id_peminjaman)
            ->where('status_peminjaman', 'Menunggu')
            ->where(function ($query) use ($approvedPeminjaman) {
                // Filter berdasarkan jenis sumber daya
                if ($approvedPeminjaman->id_ruangan) {
                    $query->where('id_ruangan', $approvedPeminjaman->id_ruangan);
                } else {
                    $query->where('id_proyektor', $approvedPeminjaman->id_proyektor);
                }
            })
            ->where(function ($timeQuery) use ($approvedPeminjaman) {
                // Pengecekan konflik waktu yang komprehensif
                $timeQuery->where(function ($subQuery) use ($approvedPeminjaman) {
                    $subQuery->where('tanggal_pinjam', '<=', $approvedPeminjaman->tanggal_kembali)
                             ->where('tanggal_kembali', '>=', $approvedPeminjaman->tanggal_pinjam)
                             ->where('jam_mulai', '<', $approvedPeminjaman->jam_selesai)
                             ->where('jam_selesai', '>', $approvedPeminjaman->jam_mulai);
                });
            })
            ->get();

        // Otomatis menolak semua peminjaman yang konflik
        $rejectedCount = 0;
        foreach ($conflictingPins as $conflictingPin) {
            $conflictingPin->status_peminjaman = 'Ditolak';
            $conflictingPin->alasan_penolakan = 'Peminjaman ditolak otomatis karena konflik dengan peminjaman yang sudah disetujui (sumber daya sama, tanggal/jam tumpang tindih)';
            $conflictingPin->save();
            $rejectedCount++;
        }

        return $rejectedCount;
    }
}
