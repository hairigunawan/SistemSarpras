<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id('id_peminjaman');
            $table->foreignId('id_akun')->constrained('users', 'id_akun');
            $table->foreignId('id_ruangan')->constrained('ruangans', 'id_ruangan');
            $table->foreignId('id_proyektor')->constrained('proyektors', 'id_proyektor');
            $table->string('nama_peminjam');
            $table->string('email_peminjam');
            $table->string('nomor_whatsapp');
            $table->integer('jumlah_peserta');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status_peminjaman', ['Menunggu', 'Disetujui', 'Ditolak', 'Selesai'])->default('Menunggu');
            $table->enum('jenis_kegiatan', ['Praktikum', 'Bimbingan', 'Kelas Teori', 'Seminar PKL'])->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
