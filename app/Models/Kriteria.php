<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriterias';
    
    protected $fillable = [
        'nama_kriteria',
        'tipe',
        'keterangan'
    ];

    public function bobots()
    {
        return $this->hasMany(Bobot::class, 'kriteria_id');
    }
}