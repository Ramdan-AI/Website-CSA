<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Database\Seeders\NewsCategorySeeder;
use Database\Seeders\NewsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_seeded_news_gets_its_own_copy_of_a_documentation_photo(): void
    {
        Storage::fake('public');
        User::create(['name' => 'Penulis', 'email' => 'penulis@example.test', 'password' => 'password']);

        $this->seed([NewsCategorySeeder::class, NewsSeeder::class]);

        $thumbnails = News::pluck('thumbnail');
        $this->assertCount(6, $thumbnails->unique());
        $thumbnails->each(function (?string $path) {
            $this->assertStringStartsWith('news/', (string) $path);
            Storage::disk('public')->assertExists($path);
        });
    }
}
