<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class LocalAssetsTest extends TestCase
{
    use RefreshDatabase;

    private const EXTERNAL_HOSTS = '/cdn\.jsdelivr\.net|cdnjs\.cloudflare\.com|fonts\.bunny\.net|fonts\.googleapis\.com|unpkg\.com/';

    public function test_no_view_loads_assets_from_a_cdn(): void
    {
        $offenders = collect(File::allFiles(resource_path('views')))
            ->filter(fn ($file) => preg_match(self::EXTERNAL_HOSTS, $file->getContents()) === 1)
            ->map(fn ($file) => $file->getRelativePathname())
            ->values()
            ->all();

        $this->assertSame([], $offenders);
    }

    public function test_pages_render_with_the_bundled_assets(): void
    {
        Setting::current();

        foreach (['/', '/login', '/password/reset'] as $uri) {
            $this->get($uri)->assertOk()->assertSee('/build/assets/alpine-', false);
        }
    }

    public function test_error_page_serves_its_fonts_locally(): void
    {
        $this->get('/halaman-yang-tidak-ada')
            ->assertNotFound()
            ->assertSee('fonts/inter-400.woff2', false);

        $this->assertFileExists(public_path('fonts/inter-400.woff2'));
    }
}
