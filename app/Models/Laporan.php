<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_laporan';
    protected $fillable = [
        'priode',
        'sarpras_terbanyak',
        'ruang_tersering',
        'jam_selesai',
        'file_laporan',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_laporan');
    }
}
