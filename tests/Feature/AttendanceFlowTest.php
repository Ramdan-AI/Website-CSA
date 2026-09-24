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

class AttendanceFlowTest extends TestCase
{
    use RefreshDatabase;

    private function member(): array
    {
        $role = Role::create(['name' => 'Anggota', 'slug' => 'anggota', 'description' => null]);
        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Anggota Uji',
            'email' => 'anggota@example.test',
            'password' => 'password',
            'is_active' => true,
        ]);
        $division = Division::create(['name' => 'Div', 'slug' => 'div', 'is_active' => true]);
        $member = Member::create([
            'user_id' => $user->id,
            'name' => 'Anggota Uji',
            'division_id' => $division->id,
            'email' => 'anggota@example.test',
            'nim' => '20240001',
            'is_active' => true,
        ]);

        return [$user, $member];
    }

    private function event(array $attrs = []): Event
    {
        $owner = User::create(['role_id' => null, 'name' => 'Admin', 'email' => 'admin'.uniqid().'@example.test', 'password' => 'password', 'is_active' => true]);

        return Event::create(array_merge([
            'user_id' => $owner->id,
            'title' => 'Kegiatan Uji',
            'slug' => 'kegiatan-uji-'.uniqid(),
            'description' => 'Deskripsi',
            'event_date' => now()->toDateString(),
            'status' => 'published',
            'attendance_enabled' => true,
            'self_attendance_enabled' => false,
            'attendance_code' => 'ABC123',
        ], $attrs));
    }

    public function test_self_attendance_blocked_when_disabled(): void
    {
        [$user] = $this->member();
        $event = $this->event(['self_attendance_enabled' => false]);

        $this->actingAs($user)
            ->post(route('admin.member.attendance.self', ['roleSlug' => 'anggota', 'event' => $event->id]))
            ->assertRedirect();

        $this->assertSame(0, Attendance::count());
    }

    public function test_self_attendance_works_when_enabled(): void
    {
        [$user, $member] = $this->member();
        $event = $this->event(['self_attendance_enabled' => true]);

        $this->actingAs($user)
            ->post(route('admin.member.attendance.self', ['roleSlug' => 'anggota', 'event' => $event->id]))
            ->assertRedirect();

        $this->assertDatabaseHas('attendances', ['event_id' => $event->id, 'member_id' => $member->id, 'status' => 'present']);
    }

    public function test_scan_qr_works_even_when_self_attendance_disabled(): void
    {
        [$user, $member] = $this->member();
        $event = $this->event(['self_attendance_enabled' => false, 'attendance_code' => 'XYZ789']);

        $this->actingAs($user)
            ->post(route('admin.member.attendance.scan', ['roleSlug' => 'anggota', 'event' => $event->id]), ['code' => 'XYZ789'])
            ->assertRedirect();

        $this->assertDatabaseHas('attendances', ['event_id' => $event->id, 'member_id' => $member->id, 'status' => 'present']);
    }

    public function test_sick_and_permit_work_when_session_active(): void
    {
        [$user, $member] = $this->member();
        $event = $this->event(['attendance_enabled' => true, 'attendance_closed_at' => null]);

        $this->actingAs($user)
            ->post(route('admin.member.attendance.sick', ['roleSlug' => 'anggota', 'event' => $event->id]), ['note' => 'Demam'])
            ->assertRedirect();

        $this->assertDatabaseHas('attendances', ['event_id' => $event->id, 'member_id' => $member->id, 'status' => 'sick']);
    }
}
