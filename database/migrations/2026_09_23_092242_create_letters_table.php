<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Nomor surat resmi, contoh: 001/CSA/IX/2026
            $table->string('reference_number')->unique();

            $table->enum('type', ['incoming', 'outgoing'])->default('outgoing');
            $table->string('subject');
            $table->string('correspondent')->nullable(); // Pengirim atau tujuan
            $table->date('letter_date');
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
