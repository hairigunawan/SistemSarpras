<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories'; // pastikan sesuai nama tabel di DB
    protected $fillable = ['nama_barang', 'kategori', 'jumlah', 'kondisi', 'gambar', 'keterangan'];
}
