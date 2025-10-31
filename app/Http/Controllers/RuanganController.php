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

    public function index(Request $request)
    {
        $query = Ruangan::with('status', 'lokasi');

        if ($request->has('nama_status') && $request->nama_status) {
            $statusId = Status::where('nama_status', $request->nama_status)->value('id_status');
            $query->where('id_status', $statusId);
        }

        if ($request->has('search') && $request->search) {
            $query->where('nama_ruangan', 'like', '%' . $request->search . '%');
        }

        $ruangans = $query->latest()->paginate(9);
        $statuses = Status::all();
        $proyektors = Proyektor::with('status')->latest()->paginate(9);

        return view('sarpras.index', compact('ruangans', 'statuses', 'proyektors'));
    }




    public function tambah_ruangan()
    {
        $statuses = Status::all();
        $lokasiList = Lokasi::pluck('nama_lokasi', 'id_lokasi');
        $defaultStatus = Status::where('nama_status', 'Tersedia')->first();

        if (!$defaultStatus) {
            $defaultStatus = Status::create(['nama_status' => 'Tersedia']);
        }

        $defaultStatusId = $defaultStatus->id_status;

        return view('sarpras.ruangan.tambah_ruangan', compact('statuses', 'lokasiList', 'defaultStatusId'));
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
            return redirect()->route('admin.sarpras.ruangan.lihat_ruangan', ['ruangan' => $ruangan->id_ruangan])->with('success', 'Ruangan berhasil diperbarui.');
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
