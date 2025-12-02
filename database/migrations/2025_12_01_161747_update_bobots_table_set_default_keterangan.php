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
        // Make sure the column is nullable with a default value
        Schema::table('bobots', function (Blueprint $table) {
            $table->text('keterangan_bobot')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bobots', function (Blueprint $table) {
            $table->text('keterangan_bobot')->nullable(false)->change();
        });
    }
};
