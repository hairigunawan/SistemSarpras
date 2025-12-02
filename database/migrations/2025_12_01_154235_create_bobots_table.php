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
        if (!Schema::hasTable('bobots')) {
            Schema::create('bobots', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->decimal('nilai', 5, 4); // Allow values like 0.1234
                $table->text('keterangan_bobot')->nullable();
                $table->timestamps();
            });
        } else {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('bobots', 'keterangan_bobot')) {
                Schema::table('bobots', function (Blueprint $table) {
                    $table->text('keterangan_bobot')->nullable();
                });
            }

            if (!Schema::hasColumn('bobots', 'nilai')) {
                Schema::table('bobots', function (Blueprint $table) {
                    $table->decimal('nilai', 5, 4)->after('nama');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('bobots')) {
            Schema::dropIfExists('bobots');
        }
    }
};
