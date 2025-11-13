<?php

namespace App\Helpers;

use App\Models\Peminjaman;
use App\Models\Proyektor;
use App\Models\Status;
use Carbon\Carbon;

class ProyektorStatusHelper
{
    /**
     * Perbarui status proyektor berdasarkan peminjaman aktif
     */
    public static function updateProyektorStatus()
    {
        $idStatusTersedia = Status::where('nama_status', 'Tersedia')->first()->id_status ?? null;
        $idStatusDipakai = Status::where('nama_status', 'Dipakai')->first()->id_status ?? null;

        if (is_null($idStatusTersedia) || is_null($idStatusDipakai)) {
            // Log error atau throw exception jika status penting tidak ditemukan
            // Untuk saat ini, kita bisa mengabaikan pembaruan status jika status tidak ada
            return;
        }

        // Dapatkan semua proyektor
        $proyektors = Proyektor::all();

        foreach ($proyektors as $proyektor) {
            // Cek apakah ada peminjaman aktif untuk proyektor ini
            $activePeminjaman = Peminjaman::where('id_proyektor', $proyektor->id_proyektor)
                ->whereIn('status_peminjaman', ['Disetujui', 'Dipinjam'])
                ->where(function ($query) {
                    $query->whereDate('tanggal_kembali', '>=', Carbon::today())
                          ->orWhere(function ($subQuery) {
                              $subQuery->whereDate('tanggal_kembali', Carbon::today())
                                       ->whereTime('jam_selesai', '>=', Carbon::now()->format('H:i'));
                          });
                })
                ->first();

            // Update status proyektor
            if ($activePeminjaman) {
                // Jika ada peminjaman aktif, status harus 'Dipakai'
                if ($proyektor->id_status != $idStatusDipakai) {
                    $proyektor->update(['id_status' => $idStatusDipakai]);
                }
            } else {
                // Jika tidak ada peminjaman aktif, status harus 'Tersedia'
                if ($proyektor->id_status != $idStatusTersedia) {
                    $proyektor->update(['id_status' => $idStatusTersedia]);
                }
            }
        }
    }

    /**
     * Cek status proyektor tertentu
     */
    public static function checkProyektorStatus($idProyektor)
    {
        $proyektor = Proyektor::findOrFail($idProyektor);

        // Cek apakah ada peminjaman aktif untuk proyektor ini
        $activePeminjaman = Peminjaman::where('id_proyektor', $proyektor->id_proyektor)
            ->whereIn('status_peminjaman', ['Disetujui', 'Dipinjam'])
            ->where(function ($query) {
                $query->whereDate('tanggal_kembali', '>=', Carbon::today())
                      ->orWhere(function ($subQuery) {
                          $subQuery->whereDate('tanggal_kembali', Carbon::today())
                                   ->whereTime('jam_selesai', '>=', Carbon::now()->format('H:i'));
                      });
            })
            ->first();

        if ($activePeminjaman) {
            // Pastikan status adalah 'Dipakai'
            $idStatusDipakai = Status::where('nama_status', 'Dipakai')->first()->id_status;
            if ($proyektor->id_status != $idStatusDipakai) {
                $proyektor->update(['id_status' => $idStatusDipakai]);
            }
            return 'Dipakai';
        } else {
            // Pastikan status adalah 'Tersedia'
            $idStatusTersedia = Status::where('nama_status', 'Tersedia')->first()->id_status;
            if ($proyektor->id_status != $idStatusTersedia) {
                $proyektor->update(['id_status' => $idStatusTersedia]);
            }
            return 'Tersedia';
        }
    }
}
