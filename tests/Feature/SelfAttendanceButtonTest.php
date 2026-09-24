<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Division;
use App\Models\Event;
use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SelfAttendanceButtonTest extends TestCase
{
    use RefreshDatabase;

    private function world(): array
    {
        $role = Role::create(['name' => 'Anggota', 'slug' => 'anggota', 'description' => null]);
        $division = Division::create(['name' => 'Div', 'slug' => 'div', 'is_active' => true]);
        $owner = Role::create(['name' => 'Admin', 'slug' => 'super-admin', 'description' => null]);
        $admin = User::create(['role_id' => $owner->id, 'name' => 'Admin', 'email' => 'admin@example.test', 'password' => 'password', 'is_active' => true]);
        $user = User::create(['role_id' => $role->id, 'name' => 'Ani', 'email' => 'ani@example.test', 'password' => 'password', 'is_active' => true]);
        $member = Member::create(['user_id' => $user->id, 'name' => 'Ani', 'division_id' => $division->id, 'email' => 'ani@example.test', 'nim' => '20240001', 'is_active' => true]);

        $event = Event::create([
            'user_id' => $admin->id, 'title' => 'Kegiatan', 'slug' => 'kegiatan', 'description' => 'x',
            'event_date' => now()->toDateString(), 'status' => 'published',
            'attendance_enabled' => true, 'self_attendance_enabled' => true, 'attendance_code' => 'ABC123',
        ]);

        return [$user, $member, $event];
    }

    public function test_button_enabled_when_mandiri_active_and_not_present(): void
    {
        [$user, $member, $event] = $this->world();

        // Status alfa tetap boleh presensi mandiri -> tombol aktif.
        Attendance::create(['event_id' => $event->id, 'member_id' => $member->id, 'status' => 'absent']);

        $response = $this->actingAs($user)->get(
            route('admin.member.calendar', ['roleSlug' => 'anggota'])
        );

        $response->assertOk();
        // Tidak ada tombol Mandiri yang disabled.
        $this->assertStringNotContainsString('disabled title="Presensi mandiri belum diaktifkan', $response->getContent());
    }

    public function test_member_can_self_attend_present(): void
    {
        [$user, $member, $event] = $this->world();

        $this->actingAs($user)
            ->post(route('admin.member.attendance.self', ['roleSlug' => 'anggota', 'event' => $event->id]))
            ->assertRedirect();

        $this->assertDatabaseHas('attendances', ['event_id' => $event->id, 'member_id' => $member->id, 'status' => 'present']);
    }
}
