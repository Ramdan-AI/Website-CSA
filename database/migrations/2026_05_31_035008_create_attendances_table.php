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
        Schema::create('attendances', function (Blueprint $table) {

            $table->id();

            $table->foreignId('event_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('member_id')
                ->constrained()
                ->cascadeOnDelete();

            // String (bukan enum) agar portabel lintas driver; nilai yang sah
            // dijaga oleh validasi aplikasi (present/permit/sick/absent).
            $table->string('status', 20)->default('present');

            $table->string('note')->nullable();

            $table->timestamp('check_in_at')
                ->nullable();

            $table->timestamps();

            $table->unique(['event_id', 'member_id'], 'attendances_event_member_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
