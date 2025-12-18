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

        $defaultStatusId = Status::where('nama_status', 'Tersedia')->value('id_status');

        $rQuery = Ruangan::filter($request->all())
            ->select(
                DB::raw("'ruangan' as type"),
                'id_ruangan as id',
                'nama_ruangan as nama',
                'created_at',
                DB::raw($defaultStatusId . ' as id_status'),
                DB::raw("'Tersedia' as nama_status")
            );

        // Query Proyektor - memiliki id_status
        $pQuery = Proyektor::select(
            DB::raw("'proyektor' as type"),
            'id_proyektor as id',
            'nama_proyektor as nama',
            'created_at',
            'id_status',
            DB::raw("(SELECT nama_status FROM statuses WHERE id_status = proyektors.id_status LIMIT 1) as nama_status")
        );

        // Terapkan filter status hanya untuk Proyektor
        if (isset($request->nama_status) && $request->nama_status) {
            $statusId = Status::where('nama_status', $request->nama_status)->value('id_status');
            $pQuery->where('id_status', $statusId);
        }

        // Terapkan filter search
        if (isset($request->search) && $request->search) {
            $search = '%' . $request->search . '%';
            $rQuery->where('nama_ruangan', 'like', $search);
            $pQuery->where('nama_proyektor', 'like', $search);
        } elseif ($type && $id) {
            if ($type === 'status') {
                $pQuery->where('id_status', $id);
            }
        }

        // Gabungkan dengan UNION dan paginate
        $items = $rQuery->union($pQuery)
            ->orderByDesc('created_at')
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
