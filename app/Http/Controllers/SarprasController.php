<?php

namespace App\Http\Controllers;

use App\Models\Sarpras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SarprasController extends Controller
{
    /**
     * Menampilkan halaman utama inventory dengan kategori.
     */
    public function inventory()
    {
        $jumlahRuangan = Sarpras::where('jenis_sarpras', 'Ruangan')->count();
        $jumlahProyektor = Sarpras::where('jenis_sarpras', 'Proyektor')->count();

        return view('admin.sarpras.inventory', compact('jumlahRuangan', 'jumlahProyektor'));
    }

    /**
     * Menampilkan daftar semua sarpras.
     */
    public function index(Request $request)
    {
        $query = Sarpras::query();

        if ($request->has('jenis') && $request->jenis) {
            $query->where('jenis_sarpras', $request->jenis);
        }

        $sarpras = $query->latest()->paginate(9);
        return view('admin.sarpras.index', compact('sarpras'));
    }

    /**
     * Menampilkan form untuk menambah sarpras baru.
     */
    public function create()
    {
        return view('admin.sarpras.create');
    }

    /**
     * Menyimpan sarpras baru ke database.
     */
    //
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sarpras' => 'required|string|max:255',
            'jenis_sarpras' => 'required|string',
            'kode_ruangan' => [
                'required_if:jenis_sarpras,Ruangan',
                'nullable',
                'string',
                'max:50',
                Rule::unique('sarpras')->where(fn($query) => $query->where('jenis_sarpras', 'Ruangan')),
            ],
            'kode_proyektor' => [
                'required_if:jenis_sarpras,Proyektor',
                'nullable',
                'string',
                'max:50',
                Rule::unique('sarpras')->where(fn($query) => $query->where('jenis_sarpras', 'Proyektor')),
            ],
            'lokasi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kapasitas' => 'required_if:jenis_sarpras,Ruangan|nullable|integer|min:1',
            'merk' => 'required_if:jenis_sarpras,Proyektor|nullable|string|max:255',
        ]);

        $path = 'images/default.png';
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('images', 'public');
            // get only filename
            $path = str_replace('public/', '', $path);
        }

        $validated['gambar'] = $path;

        Sarpras::create($validated);

        return redirect()->route('sarpras.index')
            ->with('success', 'Sarpras berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu sarpras.
     */
    public function show(Sarpras $sarpra)
    {
        return view('admin.sarpras.show', compact('sarpra'));
    }

    /**
     * Menampilkan form untuk mengedit sarpras.
     */
    public function edit(Sarpras $sarpra)
    {
        return view('admin.sarpras.edit', compact('sarpra'));
    }

    /**
     * Memperbarui data sarpras di database.
     */
    public function update(Request $request, Sarpras $sarpra)
    {
        $validated = $request->validate([
            'nama_sarpras' => 'required|string|max:255',
            'jenis_sarpras' => 'required|string',
            'kode_ruangan' => [
                'required_if:jenis_sarpras,Ruangan',
                'nullable',
                'string',
                'max:50',
                Rule::unique('sarpras')->ignore($sarpra->id_sarpras)->where(fn($query) => $query->where('jenis_sarpras', 'Ruangan')),
            ],
            'kode_proyektor' => [
                'required_if:jenis_sarpras,Proyektor',
                'nullable',
                'string',
                'max:50',
                Rule::unique('sarpras')->ignore($sarpra->id_sarpras)->where(fn($query) => $query->where('jenis_sarpras', 'Proyektor')),
            ],
            'lokasi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kapasitas' => 'required_if:jenis_sarpras,Ruangan|nullable|integer|min:1',
            'merk' => 'required_if:jenis_sarpras,Proyektor|nullable|string|max:255',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($sarpra->gambar) {
                Storage::delete('public/' . $sarpra->gambar);
            }
            $path = $request->file('gambar')->store('images', 'public');
            $validated['gambar'] = str_replace('public/', '', $path);
        }

        $sarpra->update($validated);

        return redirect()->route('sarpras.index')->with('success', 'Sarpras berhasil diperbarui.');
    }

    /**
     * Menghapus sarpras dari database.
     */
    public function destroy(Sarpras $sarpra)
    {
        // Hapus gambar dari storage jika ada
        if ($sarpra->gambar) {
            Storage::delete('public/' . $sarpra->gambar);
        }

        $sarpra->delete();

        return redirect()->route('sarpras.index')->with('success', 'Sarpras berhasil dihapus.');
    }
}
