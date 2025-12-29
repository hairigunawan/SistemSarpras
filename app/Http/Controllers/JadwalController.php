<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalTemplateExport;

class JadwalController extends Controller
{
    public function index()
    {
        $j = Jadwal::all();
        return view('admin.jadwal.index', compact('j'));
    }

    public function create()
    {
        $ruangans = Ruangan::all();
        return view('admin.jadwal.create', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $j = new Jadwal();
        return $j->submit($request);
    }

    public function edit($id)
    {
        $j = Jadwal::findOrFail($id);
        $ruangans = Ruangan::all();
        return view('admin.jadwal.edit', compact('j', 'ruangans'));
    }

    public function update(Request $request, $id)
    {
        $j = new Jadwal();
        return $j->JadwalUpdate($request, $id);
    }

    public function destroy($id)
    {
        return Jadwal::JadwalDelete($id);
    }

    public function importStore(Request $request)
    {
        return Jadwal::Import($request);
    }

    public function downloadTemplate()
    {
        return Excel::download(new JadwalTemplateExport, 'template_jadwal.xlsx');
    }
}
