<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SettingCachedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::current();
    }

    public function test_repeated_reads_do_not_query_the_database(): void
    {
        Setting::cached();

        DB::enableQueryLog();
        Setting::cached();
        Setting::cached();

        $this->assertCount(0, DB::getQueryLog());
    }

    public function test_update_is_visible_immediately(): void
    {
        Setting::cached();

        Setting::current()->update(['site_name' => 'Nama Baru']);

        $this->assertSame('Nama Baru', Setting::cached()->site_name);
    }

    public function test_registration_schedule_is_evaluated_at_read_time(): void
    {
        Setting::current()->update([
            'registration_use_schedule' => true,
            'registration_opens_at' => now()->addDay(),
            'registration_closes_at' => now()->addDays(2),
        ]);

        $this->assertFalse(Setting::registrationOpen());

        $this->travel(36)->hours();

        $this->assertTrue(Setting::registrationOpen());
    }
}
