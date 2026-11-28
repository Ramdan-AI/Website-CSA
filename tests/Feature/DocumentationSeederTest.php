<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Gallery;
use Database\Seeders\DocumentationSeeder;
use Database\Seeders\GallerySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentationSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed(DocumentationSeeder::class);
    }

    public function test_every_documentation_photo_is_published_into_its_album(): void
    {
        $this->assertGreaterThan(0, Gallery::count());
        $this->assertSame(0, Album::doesntHave('galleries')->count());

        Gallery::pluck('image')->each(fn (string $path) => Storage::disk('public')->assertExists($path));
    }

    public function test_landing_hero_receives_exactly_eight_documentation_photos(): void
    {
        $this->assertSame(8, Gallery::where('is_hero', true)->count());
    }

    public function test_running_the_seeders_again_keeps_the_documentation_intact(): void
    {
        $total = Gallery::count();
        Storage::disk('public')->put('galleries/coding.jpg', 'image');

        $this->seed([DocumentationSeeder::class, GallerySeeder::class]);

        $this->assertSame($total + 1, Gallery::count());
    }
}
