<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
