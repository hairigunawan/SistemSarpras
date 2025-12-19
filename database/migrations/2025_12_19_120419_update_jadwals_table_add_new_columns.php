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
        Schema::table('jadwals', function (Blueprint $table) {
            $table->string('sistem_kuliah')->after('kode_mk')->nullable();
            $table->string('sebaran_kelas')->after('kelas_mahasiswa')->nullable();
            
            // Removing old column
            if (Schema::hasColumn('jadwals', 'sebaran_mahasiswa')) {
                $table->dropColumn('sebaran_mahasiswa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn('sistem_kuliah');
            $table->dropColumn('sebaran_kelas');
            $table->integer('sebaran_mahasiswa')->default(0);
        });
    }
};