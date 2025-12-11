<?php

namespace App\Http\Controllers;

use App\Models\Proyektor;
use Illuminate\Http\Request;


class ProyektorController extends Controller
{
    public function tambah_proyektor(Request $request)
    {
        return Proyektor::TampilkanProyektor($request);
    }

    public function store_proyektor(Request $request)
    {
        return Proyektor::Submit($request);
    }

    public function lihat_proyektor($id)
    {
        return Proyektor::LihatProyektor($id);
    }

    public function edit_proyektor($id)
    {
        return Proyektor::EditProyektor($id);
    }

    public function update_proyektor(Request $request, $id)
    {
        return Proyektor::proyektorUpdate($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->update_proyektor($request, $id);
    }

    public function hapus_proyektor($id)
    {
        return Proyektor::hapusProyektor($id);
    }
}
