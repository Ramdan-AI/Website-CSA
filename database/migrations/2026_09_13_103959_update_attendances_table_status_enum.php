<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        // Kolom status kini berupa string (lihat migrasi create) sehingga
        // tidak perlu ALTER ENUM. Nilai sah dijaga validasi aplikasi.

        // Hapus duplikat (event_id, member_id), simpan baris dengan id terkecil.
        if ($driver === 'mysql') {
            DB::statement('DELETE a1 FROM attendances a1 INNER JOIN attendances a2 ON a1.event_id = a2.event_id AND a1.member_id = a2.member_id AND a1.id > a2.id');
        } else {
            DB::statement('DELETE FROM attendances WHERE id NOT IN (SELECT min_id FROM (SELECT MIN(id) AS min_id FROM attendances GROUP BY event_id, member_id) AS keep)');
        }

        Schema::table('attendances', function (Blueprint $table) {
            $table->unique(['event_id', 'member_id'], 'attendances_event_member_unique');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('attendances_event_member_unique');
        });
    }
};
