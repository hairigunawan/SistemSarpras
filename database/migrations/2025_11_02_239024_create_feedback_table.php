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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id('id_feedback');
            $table->foreignId('id_ruangan')->constrained('ruangans', 'id_ruangan');
            $table->foreignId('id_proyektor')->constrained('proyektors', 'id_proyektor');
            $table->foreignId('id_peminjaman')->constrained('peminjamans', 'id_peminjaman');
            $table->text('isi_feedback');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
