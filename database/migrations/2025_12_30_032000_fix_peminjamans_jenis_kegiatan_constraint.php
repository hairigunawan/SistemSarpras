<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the check constraint that might be lingering from the enum definition
        // We use raw SQL because Schema builder doesn't always easily target specific constraints by name
        // especially when converting types.
        try {
            DB::statement('ALTER TABLE peminjamans DROP CONSTRAINT IF EXISTS peminjamans_jenis_kegiatan_check');
        } catch (\Exception $e) {
            // Ignore if it fails, maybe it's not Postgres or constraint doesn't exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't necessarily need to restore the broken constraint.
    }
};
