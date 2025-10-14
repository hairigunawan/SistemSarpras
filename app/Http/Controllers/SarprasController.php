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

    /**
     * Menampilkan daftar semua sarpras.
     */
    public function index(Request $request)
    {
        $query = Sarpras::query();

        if ($request->has('jenis') && $request->jenis) {
            $query->where('jenis_sarpras', $request->jenis);
        }

        if ($request->has('search') && $request->search) {
            $query->where('nama_sarpras', 'like', '%' . $request->search . '%');
        }

        $sarpras = $query->latest()->paginate(9);
        return view('admin.sarpras.index', compact('sarpras'));
    }

    /**
     * Menampilkan form untuk menambah sarpras baru.
     */
    public function tambah_sarpras()
    {
        return view('admin.sarpras.tambah_sarpras');
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
            'status' => 'required|string|in:Tersedia,Dipinjam,Penuh,Perbaikan',
            'kode_ruangan' => [
                'required_if:jenis_sarpras,Ruangan',
                'nullable',
                'string',
                'max:50',
                Rule::unique(Sarpras::class, 'kode_ruangan')->where(fn($query) => $query->where('jenis_sarpras', 'Ruangan')),
            ],
            'kode_proyektor' => [
                'required_if:jenis_sarpras,Proyektor',
                'nullable',
                'string',
                'max:50',
                Rule::unique(Sarpras::class, 'kode_proyektor')->where(fn($query) => $query->where('jenis_sarpras', 'Proyektor')),
            ],
            'lokasi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'kapasitas_ruangan' => 'required_if:jenis_sarpras,Ruangan|nullable|integer|min:1',
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

        return redirect()->route('admin.sarpras.index')
            ->with('success', 'Sarpras berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu sarpras.
     */
    public function lihat_sarpras(Sarpras $sarpra)
    {
        return view('admin.sarpras.lihat_sarpras', compact('sarpra'));
    }

    /**
     * Menampilkan form untuk mengedit sarpras.
     */
    public function edit_sarpras(Sarpras $sarpra)
    {
        return view('admin.sarpras.edit_sarpras', compact('sarpra'));
    }

    /**
     * Memperbarui data sarpras di database.
     */
    public function update(Request $request, Sarpras $sarpra)
    {
        $validated = $request->validate([
            'nama_sarpras' => 'required|string|max:255',
            'jenis_sarpras' => 'required|string',
            'status' => 'required|string|in:Tersedia,Dipinjam,Penuh,Perbaikan',
            'kode_ruangan' => [
                'required_if:jenis_sarpras,Ruangan',
                'nullable',
                'string',
                'max:50',
                Rule::unique(Sarpras::class, 'kode_ruangan')
                    ->ignore($sarpra)
                    ->where(fn($query) => $query->where('jenis_sarpras', 'Ruangan'))
            ],
            'kode_proyektor' => [
                'required_if:jenis_sarpras,Proyektor',
                'nullable',
                'string',
                'max:50',
                Rule::unique(Sarpras::class, 'kode_proyektor')->ignore($sarpra)->where(fn($query) => $query->where('jenis_sarpras', 'Proyektor')),
            ],
            'lokasi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'kapasitas_ruangan' => 'required_if:jenis_sarpras,Ruangan|nullable|integer|min:1',
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

        try {
            $sarpra->update($validated);
            return redirect()->route('sarpras.show', $sarpra)->with('success', 'Sarpras berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui sarpras: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus sarpras dari database.
     */
    public function destroy(Sarpras $sarpra)
    {
        // Cek apakah sarpras sedang dipinjam
        if ($sarpra->status === 'Dipinjam') {
            return redirect()->route('sarpras.index')->with('error', 'Sarpras tidak dapat dihapus karena sedang dipinjam.');
        }

        // Cek apakah sarpras memiliki riwayat peminjaman
        if ($sarpra->peminjamans()->exists()) {
            return redirect()->route('sarpras.index')->with('error', 'Sarpras tidak dapat dihapus karena memiliki riwayat peminjaman.');
        }

        // Hapus gambar dari storage jika ada
        if ($sarpra->gambar) {
            Storage::delete('public/' . $sarpra->gambar);
        }

        $sarpra->delete();

        return redirect()->route('sarpras.index')->with('success', 'Sarpras berhasil dihapus.');
    }
}
