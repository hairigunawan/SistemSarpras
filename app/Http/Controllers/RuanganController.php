<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Status;
use App\Models\Lokasi;
use App\Models\Proyektor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class RuanganController extends Controller
{


    public function tambah_ruangan()
    {
        $statuses = Status::all();
        $lokasiList = Lokasi::pluck('nama_lokasi', 'id_lokasi');
        $defaultStatus = Status::where('nama_status', 'Tersedia')->first();

        if (!$defaultStatus) {
            $defaultStatus = Status::create(['nama_status' => 'Tersedia']);
        }

        $defaultStatusId = $defaultStatus->id_status;

        return view('admin.sarpras.ruangan.tambah_ruangan', compact('statuses', 'lokasiList', 'defaultStatusId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'id_status' => 'required|exists:statuses,id_status',
            'kode_ruangan' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique(Ruangan::class, 'kode_ruangan'),
            ],
            'lokasi_id' => 'required|exists:lokasis,id_lokasi',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $path = 'images/default.png';
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('images', 'public');
            $path = str_replace('public/', '', $path);
        }

        $validated['gambar'] = $path;

        Ruangan::create($validated);

        return redirect()->route('admin.sarpras.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function lihat_ruangan(Ruangan $ruangan)
    {
        return view('admin.sarpras.ruangan.lihat_ruangan', compact('ruangan'));
    }

    public function edit_ruangan(Ruangan $ruangan)
    {
        $statuses = Status::all();
        $lokasiList = Lokasi::pluck('nama_lokasi', 'id_lokasi');
        return view('admin.sarpras.ruangan.edit_ruangan', compact('ruangan', 'statuses', 'lokasiList'));
    }



    public function update(Request $request, Ruangan $ruangan)
    {
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'id_status' => 'required|exists:statuses,id_status',
            'kode_ruangan' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique(Ruangan::class, 'kode_ruangan')->ignore($ruangan->id_ruangan, 'id_ruangan'),
            ],
            'lokasi_id' => 'required|exists:lokasis,id_lokasi',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($ruangan->gambar && Storage::disk('public')->exists($ruangan->gambar)) {
                Storage::disk('public')->delete($ruangan->gambar);
            }
            $path = $request->file('gambar')->store('images', 'public');
            $validated['gambar'] = str_replace('public/', '', $path);
        }

        try {
            $ruangan->update($validated);
            return redirect()->route('admin.sarpras.index')->with('success', 'Ruangan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui Ruangan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui Ruangan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Ruangan $ruangan)
    {
        $statusDipinjam = Status::where('nama_status', 'Dipinjam')->first()->id_status;

        if ($ruangan->id_status === $statusDipinjam) {
            return redirect()->route('admin.sarpras.index')->with('error', 'Ruangan tidak dapat dihapus karena sedang dipinjam.');
        }

        if ($ruangan->peminjamans()->exists()) {
            return redirect()->route('admin.sarpras.index')->with('error', 'Ruangan tidak dapat dihapus karena memiliki riwayat peminjaman.');
        }

        if ($ruangan->gambar && Storage::disk('public')->exists($ruangan->gambar)) {
            Storage::disk('public')->delete($ruangan->gambar);
        }


        return redirect()->route('admin.sarpras.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}
