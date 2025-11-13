<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Proyektor;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Helpers\ProyektorStatusHelper;

class SarprasController extends Controller
{
    /**
     * Menampilkan halaman index sarpras dengan data ruangan dan proyektor.
     */
    public function index(Request $request)
    {
        // Query untuk ruangan
        $ruanganQuery = Ruangan::with('status', 'lokasi');

        if ($request->has('nama_status') && $request->nama_status) {
            $statusId = Status::where('nama_status', $request->nama_status)->value('id_status');
            $ruanganQuery->where('id_status', $statusId);
        }

        if ($request->has('search') && $request->search) {
            $ruanganQuery->where('nama_ruangan', 'like', '%' . $request->search . '%');
        }

        $ruangans = $ruanganQuery->latest()->paginate(9);

        // Query untuk proyektor
        $proyektorQuery = Proyektor::with('status');

        if ($request->has('nama_status') && $request->nama_status) {
            $statusId = Status::where('nama_status', $request->nama_status)->value('id_status');
            $proyektorQuery->where('id_status', $statusId);
        }

        if ($request->has('search') && $request->search) {
            $proyektorQuery->where('nama_proyektor', 'like', '%' . $request->search . '%');
        }

        $proyektors = $proyektorQuery->latest()->paginate(9);

        // Perbarui status proyektor berdasarkan peminjaman aktif
        ProyektorStatusHelper::updateProyektorStatus();

        // Refresh data proyektor setelah status diperbarui
        $proyektors = Proyektor::with('status')->latest()->paginate(9);

        $statuses = Status::all();

        return view('admin.sarpras.index', compact('ruangans', 'proyektors', 'statuses'));
    }
}
