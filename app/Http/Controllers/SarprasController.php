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
    public function index(Request $request , $type = null, $id = null)
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

        // Perbarui status proyektor berdasarkan peminjaman aktif
        ProyektorStatusHelper::updateProyektorStatus();

        $s = Status::all();

        return view('admin.sarpras.index', compact('r', 's', 'p'));
    }
}
