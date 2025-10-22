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
        Schema::create('sarpras', function (Blueprint $table) {
            $table->id('id_sarpras');
            $table->string('nama_sarpras');
            $table->string('jenis_sarpras');
            $table->integer('kapasitas')->nullable();
            $table->integer('kapasitas_ruangan')->nullable();
            $table->string('merk')->nullable();
            $table->string('lokasi')->nullable();
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Penuh', 'Perbaikan'])->default('Tersedia');
            $table->enum('status_sarpras', ['Tersedia', 'Dipinjam', 'Perbaikan'])->default('Tersedia');
            $table->string('kode_ruangan')->nullable()->unique();
            $table->string('kode_proyektor')->nullable()->unique();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('sarpras');
    }
};
