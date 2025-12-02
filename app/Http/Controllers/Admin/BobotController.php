<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bobot;

class BobotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bobot = Bobot::orderBy('created_at', 'desc')->get();
        $total = $bobot->sum('nilai');
        return view('admin.prioritas.bobot.index', compact('bobot', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.prioritas.bobot.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0|max:1',
            'keterangan_bobot' => 'nullable|string'
        ]);

        Bobot::create($request->all());

        return redirect()->route('admin.prioritas.bobot.index')->with('success', 'Bobot berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bobot = Bobot::findOrFail($id);
        return view('admin.prioritas.bobot.show', compact('bobot'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bobot = Bobot::findOrFail($id);
        return view('admin.prioritas.bobot.edit', compact('bobot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bobot = Bobot::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0|max:1',
            'keterangan_bobot' => 'nullable|string'
        ]);

        $bobot->update($request->all());

        return redirect()->route('admin.prioritas.bobot.index')->with('success', 'Bobot berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bobot = Bobot::findOrFail($id);
        $bobot->delete();

        return redirect()->route('admin.prioritas.bobot.index')->with('success', 'Bobot berhasil dihapus.');
    }
}
