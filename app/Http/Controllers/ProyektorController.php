<?php

namespace App\Http\Controllers;

use App\Models\Proyektor;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ProyektorController extends Controller
{
    public function tambah_proyektor()
    {
        $s = Status::all();
        $defaultStatus = Status::where('nama_status', 'Tersedia')->value('id_status');

        return view('admin.sarpras.proyektor.tambah_proyektor', compact('s', 'defaultStatus'));
    }

    public function store_proyektor(Request $request)
    {
        $validated = $request->validate([
            'nama_proyektor' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'kode_proyektor' => 'nullable|string|max:255|unique:proyektors,kode_proyektor',
            'id_status' => 'required|exists:statuses,id_status',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            // Panggil method di Model
            Proyektor::Submit($validated, $request->file('gambar'));

            return redirect()->route('admin.sarpras.index')
                ->with('success', 'Proyektor berhasil ditambahkan!');
        } catch (Exception $e) {
            Log::error('Gagal menambah Proyektor: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambah data: ' . $e->getMessage())->withInput();
        }
    }

    public function store(Request $request)
    {
        return $this->store_proyektor($request);
    }

    public function lihat_proyektor($id)
    {
        $p = Proyektor::findOrFail($id);

        // Perbarui status proyektor berdasarkan peminjaman aktif
        $s = Status::all();

        return view('admin.sarpras.proyektor.lihat_proyektor', compact('p', 's'));
    }

    public function edit_proyektor($id)
    {
        $data = Proyektor::edit(request(), $id);
        return view('admin.sarpras.proyektor.edit_proyektor', $data);
    }

    public function update_proyektor(Request $request, $id)
    {
        return Proyektor::proyektorUpdate($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->update_proyektor($request, $id);
    }

    public function hapus_proyektor($id)
    {
        try {
            $p = Proyektor::findOrFail($id);

            // Panggil method di Model (akan throw exception jika tidak valid)
            $p->hapusProyektor();

            return redirect()->route('admin.sarpras.index')
                ->with('success', 'Data proyektor berhasil dihapus!');
        } catch (Exception $e) {
            // Menangkap pesan error dari model (misal: sedang dipinjam)
            return back()->with('error', $e->getMessage());
        }
    }
}
