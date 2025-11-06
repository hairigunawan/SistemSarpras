<?php

namespace App\Http\Controllers;

use App\Models\Proyektor;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProyektorController extends Controller
{
    public function tambah_proyektor()
    {
        $statuses = Status::all();
        // cari id_status untuk "Tersedia"
        $defaultStatus = Status::where('nama_status', 'Tersedia')->value('id_status');

        return view('admin.sarpras.proyektor.tambah_proyektor', compact('statuses', 'defaultStatus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_proyektor' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'kode_proyektor' => 'nullable|string|max:255|unique:proyektors,kode_proyektor',
            'id_status' => 'required|exists:statuses,id_status',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('proyektor', 'public');
        }

        Proyektor::create($validated);

        return redirect()->route('admin.sarpras.index')->with('success', 'Proyektor berhasil ditambahkan!');
    }

    public function lihat_proyektor($id)
    {
        $proyektor = Proyektor::findOrFail($id);

        return view('admin.sarpras.proyektor.lihat_proyektor', compact('proyektor'));
    }

    public function edit_proyektor($id)
    {
        $proyektor = Proyektor::findOrFail($id);
        $statuses = Status::all();

        return view('admin.sarpras.proyektor.edit_proyektor', compact('proyektor', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $proyektor = Proyektor::findOrFail($id);

        $validated = $request->validate([
            'nama_proyektor' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'kode_proyektor' => 'nullable|string|max:255|unique:proyektors,kode_proyektor,' . $proyektor->id_proyektor . ',id_proyektor',
            'id_status' => 'required|exists:statuses,id_status',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($proyektor->gambar) {
                Storage::disk('public')->delete($proyektor->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('proyektor', 'public');
        }

        $proyektor->update($validated);

        return redirect()->route('admin.sarpras.index')->with('success', 'Data proyektor berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $proyektor = Proyektor::findOrFail($id);

        if ($proyektor->gambar) {
            Storage::disk('public')->delete($proyektor->gambar);
        }

        $proyektor->delete();

        return redirect()->route('admin.sarpras.index')->with('success', 'Data proyektor berhasil dihapus!');
    }
}
