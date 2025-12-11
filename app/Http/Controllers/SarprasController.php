<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Proyektor;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

class SarprasController extends Controller
{
    public function index(Request $request, $type = null, $id = null)
    {
        // Query untuk ruangan dengan filter yang sama di RuanganController
        $r = Ruangan::filter($request->all())
            ->latest()
            ->paginate(9);

        // Query untuk proyektor
        $p = Proyektor::with('status');

        if (isset($request->nama_status) && $request->nama_status) {
            $statusId = Status::where('nama_status', $request->nama_status)->value('id_status');
            $p->where('id_status', $statusId);
        }

        if (isset($request->search) && $request->search) {
            $p->where('nama_proyektor', 'like', '%' . $request->search . '%');
        }

        $p = $p->latest()->paginate(9);

        $this->updateProyektorStatus();

        $s = Status::all();

        return view('admin.sarpras.index', compact('r', 's', 'p'));
    }

    /**
     * Perbarui status proyektor berdasarkan peminjaman aktif
     */
    private function updateProyektorStatus()
    {
        // Gunakan transaction untuk menghindari race condition
        DB::transaction(function () {
            $idStatusTersedia = Status::where('nama_status', 'Tersedia')->first()->id_status ?? null;
            $idStatusDipakai = Status::where('nama_status', 'Dipakai')->first()->id_status ?? null;
            $idStatusDipinjam = Status::where('nama_status', 'Dipinjam')->first()->id_status ?? null;

            if (is_null($idStatusTersedia) || is_null($idStatusDipakai) || is_null($idStatusDipinjam)) {
                // Log error atau throw exception jika status penting tidak ditemukan
                Log::error('Status penting tidak ditemukan: Tersedia=' . $idStatusTersedia . ', Dipakai=' . $idStatusDipakai . ', Dipinjam=' . $idStatusDipinjam);
                return;
            }

            // Dapatkan semua proyektor dengan lock untuk update
            $proyektors = Proyektor::lockForUpdate()->get();

            foreach ($proyektors as $proyektor) {
                // Cek apakah ada peminjaman aktif untuk proyektor ini
                $activePeminjaman = \App\Models\Peminjaman::where('id_proyektor', $proyektor->id_proyektor)
                    ->whereIn('status_peminjaman', ['Disetujui', 'Dipinjam'])
                    ->where(function ($query) {
                        $query->whereDate('tanggal_pinjam', Carbon::today())
                            ->whereTime('jam_mulai', '<=', Carbon::now()->format('H:i'))
                            ->whereTime('jam_selesai', '>=', Carbon::now()->format('H:i'));
                    })
                    ->first();

                // Update status proyektor
                if ($activePeminjaman) {
                    // Jika ada peminjaman aktif, status harus 'Dipakai' atau 'Dipinjam'
                    $targetStatus = $activePeminjaman->status_peminjaman === 'Disetujui' ? $idStatusDipakai : $idStatusDipinjam;
                    if ($proyektor->id_status != $targetStatus) {
                        $proyektor->update(['id_status' => $targetStatus]);
                        Log::info("Status proyektor {$proyektor->nama_proyektor} diubah ke " . $activePeminjaman->status_peminjaman);
                    }
                } else {
                    // Jika tidak ada peminjaman aktif, status harus 'Tersedia'
                    if ($proyektor->id_status != $idStatusTersedia) {
                        $proyektor->update(['id_status' => $idStatusTersedia]);
                        Log::info("Status proyektor {$proyektor->nama_proyektor} diubah ke Tersedia");
                    }
                }
            }
        });
    }
}
