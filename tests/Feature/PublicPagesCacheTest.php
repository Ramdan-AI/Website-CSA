<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PublicPagesCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::current();
    }

    private function queriesDuringRequest(string $uri): int
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        $this->get($uri)->assertOk();

        DB::disableQueryLog();

        return count(DB::getQueryLog());
    }

    public function test_landing_page_is_served_from_cache_on_second_request(): void
    {
        $this->get('/')->assertOk();

        $this->assertSame(0, $this->queriesDuringRequest('/'));
    }

    public function test_content_change_is_visible_on_next_request(): void
    {
        $this->get('/')->assertOk()->assertDontSee('Divisi Baru Uji');

        Division::create(['name' => 'Divisi Baru Uji', 'slug' => 'divisi-baru-uji', 'is_active' => true]);

        $this->get('/')->assertSee('Divisi Baru Uji');
    }
}
