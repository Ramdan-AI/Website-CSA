<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Support\LogRetentionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class LogRetentionTest extends TestCase
{
    use RefreshDatabase;

    private function setting(): Setting
    {
        $setting = Setting::firstOrCreate(['id' => 1], ['site_name' => 'CSA']);

        return $setting->wasRecentlyCreated ? $setting->refresh() : $setting;
    }

    private function service(): LogRetentionService
    {
        return app(LogRetentionService::class);
    }

    /**
     * Catat satu log uji dengan penanda subject_label 'x' agar bisa
     * dibedakan dari baris audit yang ditulis service.
     */
    private function makeLog(string $description, $createdAt): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => null,
            'action' => 'created',
            'subject_type' => Setting::class,
            'subject_id' => 1,
            'subject_label' => 'x',
            'description' => $description,
            'changes' => null,
            'ip_address' => '127.0.0.1',
            'created_at' => $createdAt,
        ]);
    }

    private function countFixtureLogs(): int
    {
        return ActivityLog::where('subject_label', 'x')->count();
    }

    public function test_migration_defaults_are_disabled_and_14_days(): void
    {
        $setting = $this->setting();

        $this->assertFalse($setting->log_auto_purge_enabled);
        $this->assertSame(14, $setting->log_retention_days);
        $this->assertNull($setting->log_retention_started_at);
        $this->assertNull($setting->log_next_purge_at);
    }

    public function test_enabling_sets_schedule_from_today(): void
    {
        $this->travelTo(now());

        $setting = $this->service()->updateSettings(true, 7);

        $this->assertTrue($setting->log_auto_purge_enabled);
        $this->assertSame(7, $setting->log_retention_days);
        $this->assertNotNull($setting->log_retention_started_at);
        $this->assertEqualsWithDelta(
            0,
            $setting->log_next_purge_at->diffInSeconds(now()->addDays(7)),
            1,
            'Jadwal berikutnya harus dihitung dari hari ini + 7 hari.'
        );
    }

    public function test_changing_period_while_active_resets_countdown_from_today(): void
    {
        $this->travelTo(now());
        $this->service()->updateSettings(true, 7);

        // Majukan 3 hari, lalu ubah periode ke 30 hari.
        $this->travel(3)->days();
        $setting = $this->service()->updateSettings(true, 30);

        $this->assertEqualsWithDelta(
            0,
            $setting->log_next_purge_at->diffInSeconds(now()->addDays(30)),
            1,
            'Mengubah periode harus mengatur ulang hitungan dari hari ini.'
        );
        $this->assertEqualsWithDelta(
            0,
            $setting->log_retention_started_at->diffInSeconds(now()),
            1
        );
    }

    public function test_disabling_clears_schedule_but_keeps_logs(): void
    {
        $this->makeLog('lama', now()->subDays(60));

        $setting = $this->service()->updateSettings(true, 7);
        $this->assertNotNull($setting->log_next_purge_at);

        $setting = $this->service()->updateSettings(false, 7);

        $this->assertFalse($setting->log_auto_purge_enabled);
        $this->assertNull($setting->log_retention_started_at);
        $this->assertNull($setting->log_next_purge_at);
        $this->assertSame(1, $this->countFixtureLogs(), 'Log lama tidak boleh terhapus saat nonaktif.');
    }

    public function test_scheduled_purge_removes_old_logs_when_due(): void
    {
        $this->travelTo(now());
        $this->service()->updateSettings(true, 14);

        $this->makeLog('tua', now()->subDays(20));

        // Belum waktunya (baru diaktifkan hari ini).
        $this->assertSame(0, $this->service()->runScheduledPurge());

        // Lewati 15 hari supaya jadwal terlampaui, lalu buat log yang masih baru.
        $this->travel(15)->days();
        $this->makeLog('baru', now()->subDays(1));

        $deleted = $this->service()->runScheduledPurge();

        $this->assertGreaterThanOrEqual(1, $deleted);
        $this->assertNull(ActivityLog::where('description', 'tua')->first());
        $this->assertNotNull(ActivityLog::where('description', 'baru')->first());
    }

    public function test_scheduled_purge_does_nothing_when_disabled(): void
    {
        $this->makeLog('tua', now()->subDays(60));

        $this->service()->updateSettings(false, 7);

        $this->assertSame(0, $this->service()->runScheduledPurge());
        $this->assertSame(1, $this->countFixtureLogs());
    }

    public function test_purge_writes_audit_log_without_recursion(): void
    {
        $this->travelTo(now());
        $this->service()->updateSettings(true, 7);

        $this->makeLog('tua', now()->subDays(20));

        $this->travel(8)->days();
        $this->service()->runScheduledPurge();

        // Audit aktivitas purge tercatat (plus audit perubahan setelan).
        $audit = ActivityLog::where('description', 'like', '%Purge otomatis%')->first();
        $this->assertNotNull($audit, 'Aktivitas purge harus tercatat.');
        $this->assertSame(Setting::class, $audit->subject_type);
    }

    public function test_remaining_days_reports_countdown(): void
    {
        $this->travelTo(now());
        $setting = $this->service()->updateSettings(true, 30);

        $this->assertSame(30, $this->service()->remainingDays($setting));

        $this->travel(10)->days();
        $this->assertSame(20, $this->service()->remainingDays($setting->refresh()));

        $this->service()->updateSettings(false, 30);
        $this->assertNull($this->service()->remainingDays($setting->refresh()));
    }

    public function test_settings_endpoint_rejects_period_outside_whitelist(): void
    {
        [$user, $roleSlug] = $this->superAdmin();

        $response = $this->actingAs($user)->put(route('admin.activity-log.settings', [
            'roleSlug' => $roleSlug,
        ]), [
            'log_auto_purge_enabled' => '1',
            'log_retention_days' => 99,
        ]);

        $response->assertSessionHasErrors('log_retention_days');
        $this->assertFalse((bool) $this->setting()->log_auto_purge_enabled);
    }

    public function test_settings_endpoint_saves_valid_configuration(): void
    {
        [$user, $roleSlug] = $this->superAdmin();

        $response = $this->actingAs($user)->put(route('admin.activity-log.settings', [
            'roleSlug' => $roleSlug,
        ]), [
            'log_auto_purge_enabled' => '1',
            'log_retention_days' => 30,
        ]);

        $response->assertRedirect();

        $setting = $this->setting()->refresh();
        $this->assertTrue($setting->log_auto_purge_enabled);
        $this->assertSame(30, $setting->log_retention_days);
    }

    /**
     * Buat user super-admin (hasPermission selalu true) + role slug.
     *
     * @return array{0: User, 1: string}
     */
    private function superAdmin(): array
    {
        // Daftarkan gate izin ini agar rute dapat diakses.
        Permission::create(['name' => 'Lihat Log Sistem', 'slug' => 'view-activity-log']);
        Gate::define('view-activity-log', fn (User $user) => $user->role?->hasPermission('view-activity-log') ?? false);

        $role = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'description' => null,
        ]);

        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'is_active' => true,
        ]);

        return [$user, $role->slug];
    }
}
