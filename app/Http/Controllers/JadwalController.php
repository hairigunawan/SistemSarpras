<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $j = Jadwal::all();
        return view('admin.jadwal.index', compact('j'));
    }

    public function create()
    {
        return view('admin.jadwal.create');
    }

    public function store(Request $request)
    {
        $j = new Jadwal();
        return $j->submit($request);
    }

    public function edit($id)
    {
        $j = Jadwal::findOrFail($id);
        return view('admin.jadwal.edit', compact('j'));
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
}
