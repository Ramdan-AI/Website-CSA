<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Modul pendaftaran event dihapus karena tumpang tindih dengan modul
     * kehadiran (attendances). Presensi sudah menangani pendaftaran dan
     * kehadiran aktual dalam satu alur.
     */
    public function up(): void
    {
        Schema::dropIfExists('event_registrations');
    }

    public function down(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();
        });
    }
};
