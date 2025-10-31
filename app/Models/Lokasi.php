<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_lokasi';

    protected $fillable = [
        'nama_lokasi',
    ];

    public function ruangans()
    {
        return $this->hasMany(Ruangan::class, 'lokasi_id', 'id_lokasi');
    }
}
