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
        Schema::create('proyektors', function (Blueprint $table) {
            $table->id('id_proyektor');
            $table->string('nama_proyektor');
            $table->string('merk')->nullable();
            $table->foreignId('id_status')->constrained('statuses', 'id_status');
            $table->string('kode_proyektor')->nullable()->unique();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyektors');
    }
};
