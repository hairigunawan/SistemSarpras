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
            $table->string('jenis_sarpras'); // Ruangan, Proyektor
            // Legacy/general capacity field (dipakai oleh sebagian logika lama)
            $table->integer('kapasitas')->nullable();
            // Kapasitas khusus ruangan (dipakai oleh form/view terbaru)
            $table->integer('kapasitas_ruangan')->nullable();
            // Merk khusus proyektor
            $table->string('merk')->nullable();
            $table->string('lokasi')->nullable();
            // Status lama yang masih dipakai di sebagian kode (tambah 'Perbaikan' agar kompatibel)
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Penuh', 'Perbaikan'])->default('Tersedia');
            // Status baru yang dipakai di form/view
            $table->enum('status_sarpras', ['Tersedia', 'Dipinjam', 'Perbaikan'])->default('Tersedia');
            $table->string('kode_ruangan')->nullable()->unique();
            $table->string('kode_proyektor')->nullable()->unique();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarpras');
    }
};
