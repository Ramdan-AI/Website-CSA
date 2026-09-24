<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Presensi mandiri dipisah dari status sesi. Sesi bisa dibuka tanpa
     * mengizinkan presensi mandiri, sehingga anggota wajib scan QR.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('self_attendance_enabled')->default(false)->after('attendance_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('self_attendance_enabled');
        });
    }
};
