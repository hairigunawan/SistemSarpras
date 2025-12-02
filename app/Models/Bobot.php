<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bobot extends Model
{
    use HasFactory;

    protected $table = 'bobots';
    protected $fillable = [
        'nama',
        'nilai',
        'keterangan_bobot'
    ];

    protected $casts = [
        'nilai' => 'decimal:4'
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}
