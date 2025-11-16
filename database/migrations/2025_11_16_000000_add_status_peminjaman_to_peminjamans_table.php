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
        Schema::table('peminjamans', function (Blueprint $table) {
            // Tambahkan kolom status_peminjaman jika belum ada
            if (!Schema::hasColumn('peminjamans', 'status_peminjaman')) {
                $table->string('status_peminjaman')->default('Menunggu')->after('jenis_kegiatan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn('status_peminjaman');
        });
    }
};
