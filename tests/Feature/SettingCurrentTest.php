<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingCurrentTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_uses_id_one_even_after_sequence_advanced(): void
    {
        Setting::create(['site_name' => 'Temporary'])->delete();

        $this->assertSame(1, Setting::current()->id);
    }

    public function test_current_does_not_create_duplicate_rows(): void
    {
        Setting::create(['site_name' => 'Temporary'])->delete();

        Setting::current();
        Setting::current();

        $this->assertSame(1, Setting::count());
    }
}
