<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tandai divisi Pemrograman sebagai divisi bertingkat. Diperlukan karena
     * kolom has_levels baru ditambahkan setelah data divisi sudah ada.
     */
    public function up(): void
    {
        DB::table('divisions')
            ->where('name', 'like', '%Pemrograman%')
            ->update(['has_levels' => true]);
    }

    public function down(): void
    {
        // Tidak dikembalikan: sifat divisi bertingkat adalah data konfigurasi.
    }
};
