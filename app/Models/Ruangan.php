<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Exception;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangans';
    protected $primaryKey = 'id_ruangan';

    protected $fillable = [
        'nama_ruangan',
        'kapasitas',
        'lokasi_id',
        'id_status',
        'kode_ruangan',
        'gambar',
    ];


    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id', 'id_lokasi');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_ruangan', 'id_ruangan');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'id_ruangan', 'id_ruangan');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->with(['status', 'lokasi']);

        if (isset($filters['nama_status']) && $filters['nama_status']) {
            $query->whereHas('status', function ($q) use ($filters) {
                $q->where('nama_status', $filters['nama_status']);
            });
        }

        if (isset($filters['search']) && $filters['search']) {
            $query->where('nama_ruangan', 'like', '%' . $filters['search'] . '%');
        }

        return $query;
    }

    /**
     * Handle logika Create Ruangan beserta upload gambar
     */
    public static function Submit(array $data, $imageFile = null)
    {
        $path = 'images/default.png';

        if ($imageFile) {
            $path = self::uploadImage($imageFile);
        }

        $data['gambar'] = $path;

        return self::create($data);
    }

    /**
     * Handle logika Update Ruangan beserta replace gambar
     */
    public function updateRuangan(array $data, $imageFile = null)
    {
        if ($imageFile) {
            // Hapus gambar lama jika bukan default
            $this->removeImage();
            // Upload yang baru
            $data['gambar'] = self::uploadImage($imageFile);
        }

        return $this->update($data);
    }

    /**
     * Handle logika Delete Ruangan dengan pengecekan status dan relasi
     * Melempar Exception jika tidak memenuhi syarat
     */
    public function hapusRuangan()
    {
        // Cek 1: Apakah sedang dipinjam?
        // Mengakses relasi status untuk cek nama status
        if ($this->status && $this->status->nama_status === 'Dipinjam') {
            throw new Exception('Ruangan sedang dipinjam dan tidak dapat dihapus.');
        }

        // Cek 2: Apakah ada riwayat peminjaman?
        if ($this->peminjamans()->exists()) {
            throw new Exception('Ruangan memiliki riwayat peminjaman dan tidak dapat dihapus.');
        }

        // Jika lolos, hapus gambar dan record
        $this->removeImage();
        return $this->delete();
    }

    // --- HELPER PRIVATE ---

    private static function uploadImage($file)
    {
        $path = $file->store('images', 'public');
        return str_replace('public/', '', $path);
    }

    private function removeImage()
    {
        if ($this->gambar && $this->gambar !== 'images/default.png' && Storage::disk('public')->exists($this->gambar)) {
            Storage::disk('public')->delete($this->gambar);
        }
    }
}
