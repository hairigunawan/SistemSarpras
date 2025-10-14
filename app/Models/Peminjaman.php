<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Peminjaman.php
class Peminjaman extends Model
{
    protected $primaryKey = 'id_peminjaman';
    protected $table = 'peminjamans';

    protected $fillable = [
        'id_akun',
        'id_sarpras',
        'tanggal_pinjam',
        'tanggal_kembali',
        'jam_mulai',
        'jam_selesai',
        'status',
        'keterangan',
        'jumlah_peserta',
        'nama_peminjam',
        'email_peminjam',
        'nomor_whatsapp',
        'alasan_penolakan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_akun', 'id_akun');
    }

    public function sarpras()
    {
        return $this->belongsTo(Sarpras::class, 'id_sarpras', 'id_sarpras');
    }
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'id_laporan', 'id_laporan');
    }
}
