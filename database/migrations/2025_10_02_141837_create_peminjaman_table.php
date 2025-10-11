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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('id_peminjaman');
            $table->foreignId('id_akun')->constrained('users', 'id_akun');
            $table->foreignId('id_sarpras')->constrained('sarpras', 'id_sarpras');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('nomor_wahtsapp');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak', 'Selesai'])->default('Menunggu');
            $table->text('keterangan')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
