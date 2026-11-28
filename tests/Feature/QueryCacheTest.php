<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Setting;
use App\Support\QueryCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueryCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_remember_returns_cached_value_until_flush(): void
    {
        $calls = 0;
        $resolver = function () use (&$calls): int {
            return ++$calls;
        };

        $this->assertSame(1, QueryCache::remember('probe', $resolver));
        $this->assertSame(1, QueryCache::remember('probe', $resolver));

        QueryCache::flush();

        $this->assertSame(2, QueryCache::remember('probe', $resolver));
    }

    public function test_saving_a_registered_model_flushes_the_cache(): void
    {
        QueryCache::remember('probe', fn () => 'stale');

        Setting::current()->update(['site_name' => 'Nama Baru']);

        $this->assertSame('fresh', QueryCache::remember('probe', fn () => 'fresh'));
    }

    public function test_deleting_a_registered_model_flushes_the_cache(): void
    {
        $division = Division::create(['name' => 'Divisi Uji', 'slug' => 'divisi-uji', 'is_active' => true]);
        QueryCache::remember('probe', fn () => 'stale');

        $division->delete();

        $this->assertSame('fresh', QueryCache::remember('probe', fn () => 'fresh'));
    }
}
