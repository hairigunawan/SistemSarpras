<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriteria';

    protected $fillable = [
        'nama_kriteria',
        'tipe',
        'bobot'
    ];

    protected $casts = [
        'tipe' => 'string',
        'bobot' => 'decimal:4'
    ];
}
