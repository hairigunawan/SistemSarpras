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
            $table->dropForeign(['id_akun']);
            $table->foreignId('id_akun')->nullable()->change()->constrained('users', 'id_akun');
            $table->string('nama_peminjam')->nullable();
            $table->string('email_peminjam')->nullable();
            $table->string('telepon_peminjam')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn(['nama_peminjam', 'email_peminjam', 'telepon_peminjam']);
            $table->foreignId('id_akun')->nullable(false)->change()->constrained('users', 'id_akun');
        });
    }
};
