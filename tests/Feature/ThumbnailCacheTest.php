<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThumbnailCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::disk('public')->put('galleries/photo.jpg', $this->jpeg());
    }

    public function test_thumbnail_is_built_once_and_served_from_cache_afterwards(): void
    {
        $this->get('/img/320/galleries/photo.jpg')->assertOk();

        $cached = Storage::disk('public')->files('cache/img/320');
        $this->assertCount(1, $cached);
        Storage::disk('public')->put($cached[0], 'cached-bytes');

        $response = $this->get('/img/320/galleries/photo.jpg')->assertOk();

        $this->assertSame('cached-bytes', $response->getContent());
    }

    public function test_replacing_the_source_image_rebuilds_the_thumbnail(): void
    {
        $this->get('/img/320/galleries/photo.jpg')->assertOk();

        touch(Storage::disk('public')->path('galleries/photo.jpg'), time() + 60);
        clearstatcache();
        $this->get('/img/320/galleries/photo.jpg')->assertOk();

        $this->assertCount(2, Storage::disk('public')->files('cache/img/320'));
    }

    private function jpeg(): string
    {
        ob_start();
        imagejpeg(imagecreatetruecolor(400, 300));

        return ob_get_clean();
    }
}
