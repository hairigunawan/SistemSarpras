<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Proyektor extends Model
{
    use HasFactory;

    protected $table = 'proyektors';
    protected $primaryKey = 'id_proyektor';

    protected $fillable = [
        'nama_proyektor',
        'merk',
        'kode_proyektor',
        'gambar',
        'id_status'
    ];

    // --- RELASI ---

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_proyektor', 'id_proyektor');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'id_proyektor', 'id_proyektor');
    }


    public function scopeFilter($query, array $filters)
    {
        $query->with(['status']);

        if (isset($filters['nama_status']) && $filters['nama_status']) {
            $query->whereHas('status', function ($q) use ($filters) {
                $q->where('nama_status', $filters['nama_status']);
            });
        }

        if (isset($filters['search']) && $filters['search']) {
            $query->where('nama_proyektor', 'like', '%' . $filters['search'] . '%');
        }

        return $query;
    }

    public static function proyektorUpdate(Request $request, $id)
    {

        $p = Proyektor::findOrFail($id);

        $validated = $request->validate([
            'nama_proyektor' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'kode_proyektor' => 'nullable|string|max:255|unique:proyektors,kode_proyektor,' . $p->id_proyektor . ',id_proyektor',
            'id_status' => 'required|exists:statuses,id_status',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            // Panggil method di Model
            $p->updateProyektor($validated, $request->file('gambar'));

            return redirect()->route('admin.sarpras.index')
                ->with('success', 'Data proyektor berhasil diperbarui!');
        } catch (Exception $e) {
            Log::error('Gagal update Proyektor: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }
    /**
     * Handle logika Create Proyektor beserta upload gambar
     */
    public static function Submit(array $data, $imageFile = null)
    {
        // Path default jika tidak ada gambar (opsional, sesuaikan dengan kebutuhan)
        $path = null;

        if ($imageFile) {
            $path = self::uploadImage($imageFile);
        }

        $data['gambar'] = $path;

        return self::create($data);
    }

    /**
     * Handle logika Update Proyektor beserta replace gambar
     */
    public function updateProyektor(array $data, $imageFile = null)
    {
        if ($imageFile) {
            // Hapus gambar lama jika ada
            $this->removeImage();
            // Upload yang baru
            $data['gambar'] = self::uploadImage($imageFile);
        }

        return $this->update($data);
    }

    /**
     * Handle logika Delete Proyektor dengan pengecekan status dan relasi
     */
    public function hapusProyektor()
    {
        // Cek 1: Apakah sedang dipinjam?
        if ($this->status && $this->status->nama_status === 'Dipinjam') {
            throw new Exception('Proyektor sedang dipinjam dan tidak dapat dihapus.');
        }

        // Cek 2: Apakah ada riwayat peminjaman?
        if ($this->peminjamans()->exists()) {
            throw new Exception('Proyektor memiliki riwayat peminjaman dan tidak dapat dihapus.');
        }

        // Jika lolos, hapus gambar dan record
        $this->removeImage();
        return $this->delete();
    }

    public static function edit(Request $request, $id){
        $p = Proyektor::findOrFail($id);
        $s = Status::all();

        return compact('p', 's');
    }

    // --- HELPER PRIVATE ---

    private static function uploadImage($file)
    {
        return $file->store('proyektor', 'public');
    }

    private function removeImage()
    {
        if ($this->gambar && Storage::disk('public')->exists($this->gambar)) {
            Storage::disk('public')->delete($this->gambar);
        }
    }
}
