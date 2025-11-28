<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Jadwal extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'kode_mk',
        'nama_kelas',
        'kelas_mahasiswa',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'daya_tampung',
        'sebaran_mahasiswa'
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];


    public function getJamMulaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function getJamSelesaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public static function storeJadwal(array $data)
    {
        return self::create($data);
    }

    public function updateJadwal(array $data)
    {
        return $this->update($data);
    }

    public function deleteJadwal()
    {
        return $this->delete();
    }
}
