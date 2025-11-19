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
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id('id_ruangan');
            $table->string('nama_ruangan');
            $table->integer('kapasitas')->nullable();
            $table->foreignId('lokasi_id')->constrained('lokasis', 'id_lokasi');
            $table->foreignId('id_status')->constrained('statuses', 'id_status');
            $table->string('kode_ruangan')->nullable()->unique();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};
