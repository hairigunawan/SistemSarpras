<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        return Ruangan::TampilkanRuangan($request);
    }

    public function tambah_ruangan(Request $request)
    {
        return Ruangan::TambahRuangan($request);
    }

    public function store_ruangan(Request $request)
    {
        return Ruangan::Submit($request);
    }

    public function store(Request $request)
    {
        return $this->store_ruangan($request);
    }

    public function lihat_ruangan($id)
    {
        $r = Ruangan::findOrFail($id);

        return view('admin.sarpras.ruangan.lihat_ruangan', compact('r'));
    }

    public function edit_ruangan($id)
    {
        return Ruangan::EditRuangan($id);
    }


    public function update_ruangan(Request $request, $id)
    {
        return Ruangan::updateRuanganFromRequest($request, $id);
    }

    public function destroy($id)
    {
        return Ruangan::HapusRuangan($id);   
    }
}
