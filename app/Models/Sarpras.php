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
        'kapasitas',
        'lokasi',
        'status',
        'kode_ruangan',
        'kode_proyektor',
        'gambar',
        'merk', // <-- TAMBAHKAN INI
        'keterangan_lain', // <-- TAMBAHKAN INI
    ];

    // ... sisa kode model
}
