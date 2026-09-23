<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('body');

            // info = pemberitahuan umum, penting = perlu perhatian,
            // urgent = harus segera dibaca (mis. kegiatan dibatalkan).
            $table->enum('category', ['info', 'penting', 'urgent'])->default('info');

            // Disematkan di urutan paling atas dashboard.
            $table->boolean('pinned')->default(false);

            $table->enum('status', ['draft', 'published'])->default('published');

            // Rentang tampil; kosong berarti selalu tampil selama published.
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'pinned']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
