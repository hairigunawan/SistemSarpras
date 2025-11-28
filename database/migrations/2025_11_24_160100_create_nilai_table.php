<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_alternatif');
            $table->unsignedBigInteger('id_kriteria');
            $table->decimal('nilai', 8, 2);
            $table->timestamps();

            $table->foreign('id_alternatif')->references('id')->on('alternatif')->onDelete('cascade');
            $table->foreign('id_kriteria')->references('id')->on('kriteria')->onDelete('cascade');

            $table->unique(['id_alternatif', 'id_kriteria']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
