<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Set existing NULL nim values to a placeholder first
        DB::table('members')->whereNull('nim')->update(['nim' => 'TBD']);

        Schema::table('members', function (Blueprint $table) {
            $table->string('nim')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('nim')->nullable()->change();
        });
    }
};
