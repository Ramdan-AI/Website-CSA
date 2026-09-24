<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('submitted_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary', 300)->nullable();
            $table->longText('description')->nullable();

            $table->string('thumbnail')->nullable();
            $table->json('images')->nullable();
            $table->json('tech_stack')->nullable();

            $table->string('repo_url')->nullable();
            $table->string('demo_url')->nullable();

            $table->enum('status', ['draft', 'published'])->default('published');
            $table->boolean('featured')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_member', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('member_id')
                ->constrained('members')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('role')->nullable();

            $table->timestamps();

            $table->unique(['project_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_member');
        Schema::dropIfExists('projects');
    }
};
