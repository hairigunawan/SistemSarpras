<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Halamansarpras extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'sarpras';

    /**
     * Primary key tabel (jika tidak pakai 'id')
     */
    protected $primaryKey = 'id_sarpras';

    /**
     * Kalau tabel tidak punya kolom created_at / updated_at
     */
    public $timestamps = false;

    /**
     * Kolom yang boleh diisi (mass assignable)
     */
    protected $fillable = [
        'nama_sarpras',
        'jenis_sarpras',  // contoh: 'Ruangan' atau 'Proyektor'
        'status',         // contoh: 'Tersedia', 'Dipinjam', 'Perbaikan'
        'deskripsi',      // opsional: untuk keterangan tambahan
        'lokasi',         // opsional: misal nama gedung atau lantai
    ];

    /**
     * Scope: ambil hanya data berdasarkan jenis
     */
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_sarpras', ucfirst(strtolower($jenis)));
    }

    /**
     * Scope: ambil hanya data yang tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->where('status', 'Tersedia');
    }
}
