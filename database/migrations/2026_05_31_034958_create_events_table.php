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
        Schema::create('events', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('title');

            $table->string('slug')->unique();

            $table->enum('type', ['acara', 'study_club'])->default('acara');

            $table->longText('description');

            $table->string('location')->nullable();

            $table->date('event_date');

            $table->string('banner')->nullable();

            $table->string('registration_link')->nullable();

            $table->enum('status', [
                'draft',
                'published',
                'closed',
            ])->default('draft');

            $table->boolean('attendance_enabled')->default(false);
            $table->boolean('self_attendance_enabled')->default(false);
            $table->string('attendance_code', 6)->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamp('attendance_opened_at')->nullable();
            $table->timestamp('attendance_closed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
