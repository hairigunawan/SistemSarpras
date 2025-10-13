<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sarpras extends Model
{
    use HasFactory;

    protected $table = 'sarpras';
    protected $primaryKey = 'id_sarpras';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_sarpras',
        'jenis_sarpras',
        'kapasitas_ruangan',
        'lokasi',
        'status',
        'kode_ruangan',
        'kode_proyektor',
        'gambar',
        'merk',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_sarpras', 'id_sarpras');
    }


    // ... sisa kode model
}
