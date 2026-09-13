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
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE attendances MODIFY status ENUM('present', 'permit', 'sick', 'absent') NOT NULL DEFAULT 'present'");
        }

        DB::statement('DELETE a1 FROM attendances a1 INNER JOIN attendances a2 ON a1.event_id = a2.event_id AND a1.member_id = a2.member_id AND a1.id > a2.id');
        Schema::table('attendances', function (Blueprint $table) {
            $table->unique(['event_id', 'member_id'], 'attendances_event_member_unique');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('attendances_event_member_unique');
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE attendances MODIFY status ENUM('present', 'absent') NOT NULL DEFAULT 'present'");
        }
    }
};
