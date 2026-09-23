<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Setelan retensi log sistem.
     *
     * Semua kolom nullable/default agar aman di data lama. Retensi default
     * 14 hari dengan penghapusan otomatis nonaktif — perilaku yang sama
     * seperti sebelumnya, kini bisa diubah admin.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('log_auto_purge_enabled')->default(false)->after('whatsapp_group_link');
            $table->unsignedSmallInteger('log_retention_days')->default(14)->after('log_auto_purge_enabled');
            $table->timestamp('log_retention_started_at')->nullable()->after('log_retention_days');
            $table->timestamp('log_next_purge_at')->nullable()->after('log_retention_started_at');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'log_auto_purge_enabled',
                'log_retention_days',
                'log_retention_started_at',
                'log_next_purge_at',
            ]);
        });
    }
};
