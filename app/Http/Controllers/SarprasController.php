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
        $this->updateProyektorStatus();
        $s = Status::all();

        $search = $request->search;
        $statusFilter = $request->nama_status;

        // Query Ruangan
        $rQuery = DB::table('ruangans')
            ->join('statuses', 'ruangans.id_status', '=', 'statuses.id_status')
            ->join('lokasis', 'ruangans.lokasi_id', '=', 'lokasis.id_lokasi')
            ->select(
                DB::raw("'ruangan' as type"),
                'ruangans.id_ruangan as id',
                'ruangans.nama_ruangan as nama',
                'lokasis.nama_lokasi as detail',
                'statuses.nama_status as nama_status',
                'ruangans.gambar',
                'ruangans.created_at'
            );

        if ($search) {
            $rQuery->where('ruangans.nama_ruangan', 'like', "%{$search}%");
        }
        if ($statusFilter) {
            $rQuery->where('statuses.nama_status', $statusFilter);
        }

        // Query Proyektor
        $pQuery = DB::table('proyektors')
            ->join('statuses', 'proyektors.id_status', '=', 'statuses.id_status')
            ->select(
                DB::raw("'proyektor' as type"),
                'proyektors.id_proyektor as id',
                'proyektors.nama_proyektor as nama',
                'proyektors.merk as detail',
                'statuses.nama_status as nama_status',
                'proyektors.gambar',
                'proyektors.created_at'
            );

        if ($search) {
            $pQuery->where('proyektors.nama_proyektor', 'like', "%{$search}%");
        }
        if ($statusFilter) {
            $pQuery->where('statuses.nama_status', $statusFilter);
        }

        // Handle type/id specific filtering if needed (legacy support)
        if ($type && $id && !$search && !$statusFilter) {
             if ($type === 'status') {
                $pQuery->where('proyektors.id_status', $id);
                // Should we filter rooms too? Assuming legacy behavior was only for projectors based on previous code.
             }
        }

        // Gabungkan dengan UNION dan paginate
        $items = $rQuery->union($pQuery)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.sarpras.index', compact('s', 'items'));
    }

    private function updateProyektorStatus()
    {
        DB::transaction(function () {
            $idStatusTersedia = Status::where('nama_status', 'Tersedia')->first()->id_status ?? null;
            $idStatusDipakai = Status::where('nama_status', 'Dipakai')->first()->id_status ?? null;
            $idStatusDipinjam = Status::where('nama_status', 'Dipinjam')->first()->id_status ?? null;

            if (is_null($idStatusTersedia) || is_null($idStatusDipakai) || is_null($idStatusDipinjam)) {
                Log::error('Status penting tidak ditemukan: Tersedia=' . $idStatusTersedia . ', Dipakai=' . $idStatusDipakai . ', Dipinjam=' . $idStatusDipinjam);
                return;
            }

            $proyektors = Proyektor::lockForUpdate()->get();

            foreach ($proyektors as $proyektor) {
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
                    $targetStatus = $activePeminjaman->status_peminjaman === 'Disetujui' ? $idStatusDipakai : $idStatusDipinjam;
                    if ($proyektor->id_status != $targetStatus) {
                        $proyektor->update(['id_status' => $targetStatus]);
                        Log::info("Status proyektor {$proyektor->nama_proyektor} diubah ke " . $activePeminjaman->status_peminjaman);
                    }
                } else {
                    if ($proyektor->id_status != $idStatusTersedia) {
                        $proyektor->update(['id_status' => $idStatusTersedia]);
                        Log::info("Status proyektor {$proyektor->nama_proyektor} diubah ke Tersedia");
                    }
                }
            }
        });
    }
}
