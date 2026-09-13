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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('attendance_enabled')->default(false)->after('status');
            $table->string('attendance_code', 6)->nullable()->after('attendance_enabled');
            $table->string('qr_code')->nullable()->after('attendance_code');
            $table->timestamp('attendance_opened_at')->nullable()->after('qr_code');
            $table->timestamp('attendance_closed_at')->nullable()->after('attendance_opened_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['attendance_enabled', 'attendance_code', 'qr_code', 'attendance_opened_at', 'attendance_closed_at']);
        });
    }
};
