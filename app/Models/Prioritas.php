<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prioritas extends Model
{
    protected $primaryKey = 'id_prioritas';
    protected $fillable = ['nama_prioritas', 'nilai_prioritas'];
}
