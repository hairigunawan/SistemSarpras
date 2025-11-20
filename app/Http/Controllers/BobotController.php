<?php

namespace App\Http\Controllers;

use App\Models\Bobot;
use Illuminate\Http\Request;

class BobotController extends Controller
{
    public function index()
    {
        $bobot = Bobot::all(); // perbaikan: dulu bernama $bobots
        $total = Bobot::sum('nilai');

        return view('admin.prioritas.bobot.index', compact('bobot', 'total'));
    }

    public function create()
    {
        return view('admin.prioritas.bobot.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0|max:1',
        ]);

        Bobot::create([
            'nama' => $request->nama,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('admin.prioritas.bobot.index')
            ->with('success', 'Bobot berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $bobot = Bobot::findOrFail($id);

        return view('admin.prioritas.bobot.edit', compact('bobot'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0|max:1',
        ]);

        $bobot = Bobot::findOrFail($id);

        $bobot->update([
            'nama' => $request->nama,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('admin.prioritas.bobot.index')
            ->with('success', 'Bobot berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bobot = Bobot::findOrFail($id);
        $bobot->delete();

        return redirect()->route('admin.prioritas.bobot.index')
            ->with('success', 'Bobot berhasil dihapus.');
    }
}
