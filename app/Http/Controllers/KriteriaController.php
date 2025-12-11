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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $k = new Kriteria();
        return $k->HalamanUtama();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kriteria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Kriteria::Submit($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Kriteria $kriteria)
    {
        return view('admin.kriteria.show', compact('kriteria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kriteria $kriteria)
    {
        return view('admin.kriteria.edit', compact('kriteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:100|unique:kriteria,nama_kriteria,' . $kriteria->id,
            'tipe' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric|min:0|max:1',
        ]);

        $kriteria->update($validated);

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus.');
    }
}
