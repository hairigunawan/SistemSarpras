<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

// app/Models/Peminjaman.php
class Peminjaman extends Model
{
    protected $primaryKey = 'id_peminjaman';
    protected $table = 'peminjamans';

    protected $fillable = [
        'id_akun',
        'id_ruangan',
        'id_proyektor',
        'id_lokasi',
        'id_status',
        'nama_peminjam',
        'email_peminjam',
        'nomor_whatsapp',
        'jumlah_peserta',
        'tanggal_pinjam',
        'tanggal_kembali',
        'jam_mulai',
        'jam_selesai',
        'jenis_kegiatan',
        'alasan_penolakan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_akun', 'id_akun');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }

    public function proyektor()
    {
        return $this->belongsTo(Proyektor::class, 'id_proyektor', 'id_proyektor');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'id_peminjaman', 'id_peminjaman');
    }
    public function sarpras()
    {
        if ($this->id_ruangan) {
            return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
        } elseif ($this->id_proyektor) {
            return $this->belongsTo(Proyektor::class, 'id_proyektor', 'id_proyektor');
        }
        return null; // Atau throw exception jika tidak ada
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }
}
