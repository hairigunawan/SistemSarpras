<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (!Schema::hasTable('laporans')) {
      return;
    }

    Schema::table('laporans', function (Blueprint $table) {
      if (!Schema::hasColumn('laporans', 'jam_selesai')) {
        // Store average hours with one decimal place
        $table->decimal('jam_selesai', 5, 1)->default(0)->after('ruangan_tersering');
      }
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    if (!Schema::hasTable('laporans')) {
      return;
    }

    Schema::table('laporans', function (Blueprint $table) {
      if (Schema::hasColumn('laporans', 'jam_selesai')) {
        $table->dropColumn('jam_selesai');
      }
    });
  }
};
