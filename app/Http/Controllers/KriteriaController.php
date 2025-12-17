<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\SimpleAHPService;
use App\Services\SimpleSAWService;

class KriteriaController extends Controller
{

    public function index(Kriteria $k)
    {
        return $k->HalamanUtama();
    }

    public function create()
    {
        return view('admin.kriteria.create');
    }

    public function store(Request $request)
    {
        return Kriteria::Submit($request);
    }

    public function show(Kriteria $kriteria)
    {
        return view('admin.kriteria.show', compact('kriteria'));
    }

    public function edit(Kriteria $kriteria)
    {
        return view('admin.kriteria.edit', compact('kriteria'));
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        return Kriteria::UpdateKriteria($request, $kriteria);
    }

    public function destroy(Kriteria $kriteria)
    {
        return Kriteria::DeleteKriteria($kriteria);
    }
}
