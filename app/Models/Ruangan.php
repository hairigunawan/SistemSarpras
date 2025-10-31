<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
