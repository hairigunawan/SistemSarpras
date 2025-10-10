<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'kode_mk',
        'nama_kelas',
        'kelas_mahasiswa',
        'sebaran_mahasiswa',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'daya_tampung',
    ];
}
