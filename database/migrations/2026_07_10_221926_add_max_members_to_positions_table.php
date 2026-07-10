<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->unsignedInteger('max_members')->default(1)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn('max_members');
        });
    }
};
