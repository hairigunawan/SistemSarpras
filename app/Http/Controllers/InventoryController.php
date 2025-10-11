<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    /**
     * Tampilkan semua data inventaris
     */
    public function index()
    {
        $inventories = Inventory::all();
        return view('admin.inventory', compact('inventories'));
    }

    /**
     * Form tambah data
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Simpan data baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'jumlah' => 'required|integer',
            'kondisi' => 'required',
        ]);

        Inventory::create($request->all());

        return redirect()->route('inventory.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail data
     */
    public function show(string $id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('admin.show', compact('inventory'));
    }

    /**
     * Form edit data
     */
    public function edit(string $id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('admin.edit', compact('inventory'));
    }

    /**
     * Update data ke database
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'jumlah' => 'required|integer',
            'kondisi' => 'required',
        ]);

        $inventory = Inventory::findOrFail($id);
        $inventory->update($request->all());

        return redirect()->route('inventory.index')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Hapus data dari database
     */
    public function destroy(string $id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return redirect()->route('inventory.index')->with('success', 'Data berhasil dihapus!');
    }
}
