<?php

namespace App\Http\Controllers;

use App\Models\Inventory;

class UserInventoryController extends Controller
{
    public function index()
    {
        // Ambil semua data dari tabel inventories
        $inventories = Inventory::all();

        // Kirim ke view user.halamansarpras
        return view('user.halamansarpras', compact('inventories'));
    }

    public function show($id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('user.show', compact('inventory'));
    }
}
