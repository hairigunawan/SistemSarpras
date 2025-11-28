<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kriteria', function (Blueprint $table) {
            $table->decimal('bobot', 8, 4)->nullable()->after('tipe');
            $table->decimal('consistency_ratio', 8, 4)->nullable()->after('bobot');
        });
    }

    public function down(): void
    {
        Schema::table('kriteria', function (Blueprint $table) {
            $table->dropColumn(['bobot', 'consistency_ratio']);
        });
    }
};
