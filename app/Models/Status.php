<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'statuses';
    protected $primaryKey = 'id_status';
    protected $fillable = ['nama_status'];

    public $timestamps = false; // kalau tabel tidak pakai created_at/updated_at

    public function proyektors()
    {
        return $this->hasMany(Proyektor::class, 'id_status', 'id_status');
    }
}
