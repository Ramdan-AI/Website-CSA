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

    public function test_seeded_news_only_point_to_images_that_exist(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('galleries/coding.jpg', 'image');
        Storage::disk('public')->put('galleries/seminar-1.jpg', 'image');
        User::create(['name' => 'Penulis', 'email' => 'penulis@example.test', 'password' => 'password']);

        $this->seed([NewsCategorySeeder::class, NewsSeeder::class]);

        $thumbnails = News::pluck('thumbnail');
        $this->assertCount(6, $thumbnails);
        $thumbnails->each(fn (?string $path) => Storage::disk('public')->assertExists($path));
    }
}
