<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mode pendaftaran bersifat eksklusif: manual (toggle) ATAU otomatis
     * (rentang tanggal). Kolom ini menentukan mode mana yang dipakai.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('registration_use_schedule')->default(false)->after('registration_open');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('registration_use_schedule');
        });
    }
};
