<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bobot extends Model
{
    protected $fillable = ['nama', 'nilai', 'keterangan_bobot'];

    public function prioritas()
    {
        return $this->belongsTo(Prioritas::class, 'nama_prioritas', 'nama');
    }
}
