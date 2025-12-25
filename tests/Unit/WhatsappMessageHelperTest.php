<?php

use App\Helpers\WhatsappMessageHelper;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Ruangan;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);

test('format pesan approved', function () {
    $peminjaman = new Peminjaman([
        'nama_peminjam' => 'Budi Santoso',
        'tanggal_pinjam' => '2023-12-25',
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:00',
        'jenis_kegiatan' => 'Rapat Koordinasi',
    ]);
    // Mock user relation or manually set attribute if possible, but helper handles null user->nama by using nama_peminjam
    // Mock nama_sarpras accessor
    $peminjaman->setRelation('ruangan', new Ruangan(['nama_ruangan' => 'Aula Utama']));
    
    $msg = WhatsappMessageHelper::approved($peminjaman);
    
    expect($msg)->toContain('Yth. Bpk/Ibu *Budi Santoso*');
    expect($msg)->toContain('*DISETUJUI* ✅');
    expect($msg)->toContain('Aula Utama');
    expect($msg)->toContain('Rapat Koordinasi');
    expect($msg)->toContain('Sistem Informasi Sarana & Prasarana');
});

test('format pesan rejected', function () {
    $peminjaman = new Peminjaman([
        'nama_peminjam' => 'Ani Wijaya',
        'tanggal_pinjam' => '2023-12-26',
    ]);
    $peminjaman->setRelation('ruangan', new Ruangan(['nama_ruangan' => 'Lab Komputer']));

    $msg = WhatsappMessageHelper::rejected($peminjaman, 'Ruangan sedang renovasi');

    expect($msg)->toContain('*DITOLAK* ❌');
    expect($msg)->toContain('Lab Komputer');
    expect($msg)->toContain('Ruangan sedang renovasi');
});
