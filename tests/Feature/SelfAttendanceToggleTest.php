<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Event;
use App\Models\Member;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class SelfAttendanceToggleTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        Permission::firstOrCreate(['slug' => 'manage-attendances'], ['name' => 'manage-attendances']);
        Gate::define('manage-attendances', fn (User $u) => true);

        $role = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => null]);

        return User::create([
            'role_id' => $role->id,
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'is_active' => true,
        ]);
    }

    private function event(User $owner, array $attrs = []): Event
    {
        return Event::create(array_merge([
            'user_id' => $owner->id,
            'title' => 'Kegiatan',
            'slug' => 'kegiatan-'.uniqid(),
            'description' => 'Deskripsi',
            'event_date' => now()->toDateString(),
            'status' => 'published',
            'attendance_enabled' => false,
            'self_attendance_enabled' => false,
            'attendance_code' => null,
        ], $attrs));
    }

    public function test_enabling_self_attendance_also_opens_session(): void
    {
        $admin = $this->admin();
        $event = $this->event($admin, ['attendance_enabled' => false, 'self_attendance_enabled' => false]);

        $this->actingAs($admin)->post(
            route('admin.attendance.toggle', ['roleSlug' => 'super-admin', 'event' => $event->id])
        )->assertRedirect();

        $event->refresh();
        $this->assertTrue($event->self_attendance_enabled);
        // Sesi ikut dibuka agar tombol mandiri anggota langsung aktif.
        $this->assertTrue($event->attendance_enabled);
        $this->assertNull($event->attendance_closed_at);
    }

    public function test_member_can_self_attend_after_admin_enables(): void
    {
        $admin = $this->admin();

        $role = Role::create(['name' => 'Anggota', 'slug' => 'anggota', 'description' => null]);
        $division = Division::create(['name' => 'Div', 'slug' => 'div', 'is_active' => true]);
        $user = User::create(['role_id' => $role->id, 'name' => 'Ani', 'email' => 'ani@example.test', 'password' => 'password', 'is_active' => true]);
        $member = Member::create(['user_id' => $user->id, 'name' => 'Ani', 'division_id' => $division->id, 'email' => 'ani@example.test', 'nim' => '20240001', 'is_active' => true]);

        $event = $this->event($admin);

        // Admin mengaktifkan presensi mandiri.
        $this->actingAs($admin)->post(route('admin.attendance.toggle', ['roleSlug' => 'super-admin', 'event' => $event->id]));

        // Anggota melakukan presensi mandiri.
        $this->actingAs($user)->post(
            route('admin.member.attendance.self', ['roleSlug' => 'anggota', 'event' => $event->id])
        )->assertRedirect();

        $this->assertDatabaseHas('attendances', [
            'event_id' => $event->id,
            'member_id' => $member->id,
            'status' => 'present',
        ]);
    }
}
