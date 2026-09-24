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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('site_name');

            $table->string('logo')->nullable();

            $table->string('email')->nullable();

            $table->string('phone')->nullable();

            $table->text('address')->nullable();

            $table->string('instagram')->nullable();

            $table->string('youtube')->nullable();

            $table->string('linkedin')->nullable();

            $table->longText('about')->nullable();

            $table->boolean('registration_open')->default(false);
            $table->boolean('registration_use_schedule')->default(false);
            $table->timestamp('registration_opens_at')->nullable();
            $table->timestamp('registration_closes_at')->nullable();

            $table->string('whatsapp_group_link', 500)->nullable();

            $table->boolean('log_auto_purge_enabled')->default(false);
            $table->unsignedSmallInteger('log_retention_days')->default(14);
            $table->timestamp('log_retention_started_at')->nullable();
            $table->timestamp('log_next_purge_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
