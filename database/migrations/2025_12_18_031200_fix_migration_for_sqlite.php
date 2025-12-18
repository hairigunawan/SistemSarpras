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
        // Hapus kolom yang tidak ada di SQLite
        if (Schema::hasColumn('peminjamans', 'nomor_whatsapp')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->dropColumn('nomor_whatsapp');
            });
        }

        // Hapus kolom yang tidak ada di SQLite
        if (Schema::hasColumn('users', 'nomor_whatsapp')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('nomor_whatsapp');
            });
        }

        // Tambahkan kolom verifikasi email ke tabel users
        if (!Schema::hasColumn('users', 'verification_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('verification_code')->nullable()->after('email');
                $table->boolean('is_verified')->default(false)->after('verification_code');
                $table->timestamp('email_verified_at')->nullable()->change();
                $table->string('email_domain')->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['verification_code', 'is_verified', 'email_domain']);
        });
    }
};
