<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        return Ruangan::TampilkanRuangan($request);
    }

    public function tambah_ruangan(Request $request)
    {
        return Ruangan::TambahRuangan($request);
    }

    public function store_ruangan(Request $request)
    {
        return Ruangan::Submit($request);
    }

    public function store(Request $request)
    {
        // HARD RESET DEBUG MODE
        try {
            $request->validate([
                'gambar' => 'required|image|max:2048', // Ruangan uses 'gambar' not 'foto'
            ]);

            $supabase = new \App\Services\SupabaseService();

            $file = $request->file('gambar');
            $name = 'ruangan/' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Direct upload via Service (Guzzle)
            $url = $supabase->upload($file, $name);

            return response()->json([
                'OK' => true,
                'url' => $url,
                'note' => 'DEBUG MODE ACTIVE - Check this URL in browser immediately'
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('UPLOAD WEB FAIL', [
                'msg' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'ERROR' => $e->getMessage()
            ], 500);
        }
    }

    public function lihat_ruangan($id)
    {
        return Ruangan::LihatRuangan($id);
    }

    public function edit_ruangan($id)
    {
        return Ruangan::EditRuangan($id);
    }


    public function update_ruangan(Request $request, $id)
    {
        return Ruangan::updateRuanganFromRequest($request, $id);
    }

    public function destroy($id)
    {
        return Ruangan::HapusRuangan($id);
    }
}
