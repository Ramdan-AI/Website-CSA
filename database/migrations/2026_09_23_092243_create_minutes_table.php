<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('minutes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->date('meeting_date');
            $table->string('location')->nullable();

            $table->text('agenda')->nullable();
            $table->longText('decisions')->nullable();
            $table->string('attachment')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // Peserta rapat diambil dari daftar anggota.
        Schema::create('minute_member', function (Blueprint $table) {
            $table->id();

            $table->foreignId('minute_id')
                ->constrained('minutes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('member_id')
                ->constrained('members')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['minute_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('minute_member');
        Schema::dropIfExists('minutes');
    }
};
