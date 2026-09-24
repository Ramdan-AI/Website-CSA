<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class RegistrationScheduleTest extends TestCase
{
    use RefreshDatabase;

    private function setting(array $attrs = []): Setting
    {
        return Setting::create(array_merge(['site_name' => 'CSA'], $attrs));
    }

    public function test_manual_mode_ignores_dates(): void
    {
        $s = $this->setting([
            'registration_use_schedule' => false,
            'registration_open' => true,
            'registration_opens_at' => now()->addDays(10),
            'registration_closes_at' => now()->addDays(20),
        ]);

        $this->assertTrue($s->isRegistrationOpen());
    }

    public function test_schedule_mode_opens_and_closes_automatically(): void
    {
        // Belum masuk rentang (buka 5 hari lagi).
        $s = $this->setting([
            'registration_use_schedule' => true,
            'registration_open' => false,
            'registration_opens_at' => now()->addDays(5),
            'registration_closes_at' => now()->addDays(10),
        ]);
        $this->assertFalse($s->isRegistrationOpen());

        // Di tengah rentang -> terbuka.
        $this->travel(6)->days();
        $this->assertTrue($s->fresh()->isRegistrationOpen());

        // Setelah rentang -> tertutup.
        $this->travel(6)->days();
        $this->assertFalse($s->fresh()->isRegistrationOpen());
    }

    public function test_schedule_mode_without_dates_is_closed(): void
    {
        $s = $this->setting([
            'registration_use_schedule' => true,
            'registration_open' => false,
            'registration_opens_at' => null,
            'registration_closes_at' => null,
        ]);

        $this->assertFalse($s->isRegistrationOpen());
    }

    public function test_manual_toggle_ignored_when_schedule_active(): void
    {
        $s = $this->setting([
            'registration_use_schedule' => true,
            'registration_open' => true, // tetap diabaikan
            'registration_opens_at' => now()->addDays(3),
            'registration_closes_at' => now()->addDays(8),
        ]);

        // Toggle manual menyala, tapi karena mode otomatis & belum waktunya -> tertutup.
        $this->assertFalse($s->isRegistrationOpen());
    }

    public function test_landing_shows_register_button_when_schedule_open(): void
    {
        $this->setting([
            'registration_use_schedule' => true,
            'registration_open' => false,
            'registration_opens_at' => now()->subDay(),
            'registration_closes_at' => now()->addDay(),
        ]);

        $response = $this->get('/');
        $response->assertOk();
        // Tombol daftar harus mengarah ke /register (sinkron dengan mode otomatis).
        $response->assertSee(route('register'), false);
    }

    public function test_landing_hides_direct_register_link_when_schedule_closed(): void
    {
        $this->setting([
            'registration_use_schedule' => true,
            'registration_open' => false,
            'registration_opens_at' => now()->subDays(10),
            'registration_closes_at' => now()->subDays(5),
        ]);

        $response = $this->get('/register');
        // Tertutup -> middleware redirect ke home.
        $response->assertRedirect(route('home'));
    }

    public function test_update_endpoint_saves_each_mode(): void
    {
        $admin = $this->adminUser();
        $this->setting();

        // Mode open
        $this->actingAs($admin)->put(route('admin.registration.update', ['roleSlug' => 'super-admin']), [
            'registration_mode' => 'open', 'whatsapp_group_link' => null,
        ])->assertRedirect();
        $s = Setting::first();
        $this->assertTrue($s->registration_open);
        $this->assertFalse($s->registration_use_schedule);

        // Mode schedule
        $this->actingAs($admin)->put(route('admin.registration.update', ['roleSlug' => 'super-admin']), [
            'registration_mode' => 'schedule',
            'registration_opens_at' => now()->subDay()->format('Y-m-d\TH:i'),
            'registration_closes_at' => now()->addDay()->format('Y-m-d\TH:i'),
        ])->assertRedirect();
        $s = Setting::first();
        $this->assertTrue($s->registration_use_schedule);
        $this->assertFalse($s->registration_open);
        $this->assertTrue($s->isRegistrationOpen());

        // Mode closed (kembali ke manual tutup) -> bug tidak bisa kembali ke manual.
        $this->actingAs($admin)->put(route('admin.registration.update', ['roleSlug' => 'super-admin']), [
            'registration_mode' => 'closed',
        ])->assertRedirect();
        $s = Setting::first();
        $this->assertFalse($s->registration_use_schedule);
        $this->assertFalse($s->registration_open);
        $this->assertNull($s->registration_opens_at);
        $this->assertFalse($s->isRegistrationOpen());
    }

    private function adminUser(): User
    {
        Permission::firstOrCreate(['slug' => 'manage-settings'], ['name' => 'manage-settings']);
        Gate::define('manage-settings', fn () => true);

        $role = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => null]);

        return User::create([
            'role_id' => $role->id,
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'is_active' => true,
        ]);
    }
}
