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
        Schema::create('kriteria_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id_1')->constrained('kriteria')->onDelete('cascade');
            $table->foreignId('kriteria_id_2')->constrained('kriteria')->onDelete('cascade');
            $table->decimal('nilai', 8, 4); // Nilai perbandingan (1-9 atau 1/9)
            $table->timestamps();

            $table->unique(['kriteria_id_1', 'kriteria_id_2']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria_comparisons');
    }
};
