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
        // Pindahkan data nomor telepon dari peminjamans ke users
        // Pertama, pastikan kolom nomor_whatsapp masih ada di peminjamans
        $columns = DB::select("SHOW COLUMNS FROM peminjamans LIKE 'nomor_whatsapp'");

        if (!empty($columns)) {
            $peminjamans = DB::table('peminjamans')->select('id_akun', 'nomor_whatsapp')->distinct()->get();

            foreach ($peminjamans as $peminjaman) {
                if (!empty($peminjaman->nomor_whatsapp)) {
                    DB::table('users')
                        ->where('id_akun', $peminjaman->id_akun)
                        ->update(['nomor_telepon' => $peminjaman->nomor_whatsapp]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan data ke tabel peminjamans jika perlu
        // Tidak perlu implementasi lengkap karena ini adalah migrasi satu arah
    }
};
