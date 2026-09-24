<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rentang tanggal pendaftaran. Pendaftaran dibuka bila toggle manual
     * aktif ATAU waktu saat ini berada di dalam rentang ini. Biasanya
     * dipakai untuk periode pendaftaran tiap semester ganjil.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->timestamp('registration_opens_at')->nullable()->after('registration_open');
            $table->timestamp('registration_closes_at')->nullable()->after('registration_opens_at');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['registration_opens_at', 'registration_closes_at']);
        });
    }
};
