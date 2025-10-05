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
        Schema::table('sarpras', function (Blueprint $table) {
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Penuh', 'Perbaikan'])->default('Tersedia')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sarpras', function (Blueprint $table) {
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Penuh'])->default('Tersedia')->change();
        });
    }
};
