<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Division;
use App\Models\Event;
use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AttendanceLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private function setupWorld(int $memberCount = 2): array
    {
        $role = Role::create(['name' => 'Anggota', 'slug' => 'anggota', 'description' => null]);
        $division = Division::create(['name' => 'Div', 'slug' => 'div', 'is_active' => true]);

        $members = [];
        $users = [];
        for ($i = 0; $i < $memberCount; $i++) {
            $user = User::create([
                'role_id' => $role->id,
                'name' => "Anggota $i",
                'email' => "anggota$i@example.test",
                'password' => 'password',
                'is_active' => true,
            ]);
            $members[] = Member::create([
                'user_id' => $user->id,
                'name' => "Anggota $i",
                'division_id' => $division->id,
                'email' => "anggota$i@example.test",
                'nim' => '2024000'.$i,
                'is_active' => true,
            ]);
            $users[] = $user;
        }

        // Admin pemilik event
        $adminRole = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => null]);
        Gate::define('manage-attendances', fn (User $u) => true);
        $admin = User::create([
            'role_id' => $adminRole->id,
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'is_active' => true,
        ]);

        return [$users, $members, $admin, $adminRole];
    }

    private function makeEvent(User $owner, array $attrs = []): Event
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

    public function test_no_absent_records_before_session_opened(): void
    {
        [$users, $members, $admin] = $this->setupWorld();
        $event = $this->makeEvent($admin, ['attendance_enabled' => false]);

        // Belum ada sesi, tidak ada record apa pun.
        $this->assertSame(0, Attendance::where('event_id', $event->id)->count());
    }

    public function test_self_attendance_blocked_when_disabled(): void
    {
        [$users, $members, $admin] = $this->setupWorld();
        $event = $this->makeEvent($admin, ['attendance_enabled' => true, 'self_attendance_enabled' => false]);

        $this->actingAs($users[0])->post(
            route('admin.member.attendance.self', ['roleSlug' => 'anggota', 'event' => $event->id])
        );

        $this->assertSame(0, Attendance::count());
    }

    public function test_qr_works_when_session_open_even_if_self_disabled(): void
    {
        [$users, $members, $admin] = $this->setupWorld();
        $event = $this->makeEvent($admin, [
            'attendance_enabled' => true,
            'self_attendance_enabled' => false,
            'attendance_code' => 'QR1234',
        ]);

        $this->actingAs($users[0])->post(
            route('admin.member.attendance.scan', ['roleSlug' => 'anggota', 'event' => $event->id]),
            ['code' => 'QR1234']
        );

        $this->assertDatabaseHas('attendances', ['event_id' => $event->id, 'member_id' => $members[0]->id, 'status' => 'present']);
    }

    public function test_sick_requires_reason_and_saves_note(): void
    {
        [$users, $members, $admin] = $this->setupWorld();
        $event = $this->makeEvent($admin, ['attendance_enabled' => true]);

        // Tanpa alasan -> tidak tersimpan
        $this->actingAs($users[0])->post(
            route('admin.member.attendance.sick', ['roleSlug' => 'anggota', 'event' => $event->id]),
            []
        )->assertSessionHasErrors('note');
        $this->assertSame(0, Attendance::count());

        // Dengan alasan -> tersimpan
        $this->actingAs($users[0])->post(
            route('admin.member.attendance.sick', ['roleSlug' => 'anggota', 'event' => $event->id]),
            ['note' => 'Demam tinggi']
        );

        $this->assertDatabaseHas('attendances', [
            'event_id' => $event->id, 'member_id' => $members[0]->id, 'status' => 'sick', 'note' => 'Demam tinggi',
        ]);
    }

    public function test_permit_requires_reason(): void
    {
        [$users, $members, $admin] = $this->setupWorld();
        $event = $this->makeEvent($admin, ['attendance_enabled' => true]);

        $this->actingAs($users[0])->post(
            route('admin.member.attendance.permit', ['roleSlug' => 'anggota', 'event' => $event->id]),
            ['note' => 'Urusan keluarga']
        );

        $this->assertDatabaseHas('attendances', ['event_id' => $event->id, 'member_id' => $members[0]->id, 'status' => 'permit', 'note' => 'Urusan keluarga']);
    }

    public function test_closing_session_marks_unrecorded_members_absent(): void
    {
        [$users, $members, $admin] = $this->setupWorld(3);
        $event = $this->makeEvent($admin, ['attendance_enabled' => true, 'self_attendance_enabled' => true]);

        // Anggota 0 hadir
        Attendance::create(['event_id' => $event->id, 'member_id' => $members[0]->id, 'status' => 'present', 'check_in_at' => now()]);
        // Anggota 1 izin
        Attendance::create(['event_id' => $event->id, 'member_id' => $members[1]->id, 'status' => 'permit', 'note' => 'sakit']);

        // Tutup sesi -> anggota 2 otomatis Alfa
        $this->actingAs($admin)
            ->withSession([])
            ->post(route('admin.attendance.close', ['roleSlug' => 'super-admin', 'event' => $event->id]));

        $this->assertDatabaseHas('attendances', ['event_id' => $event->id, 'member_id' => $members[2]->id, 'status' => 'absent']);
        // Yang sudah hadir/izin tidak berubah
        $this->assertDatabaseHas('attendances', ['event_id' => $event->id, 'member_id' => $members[0]->id, 'status' => 'present']);
        $this->assertDatabaseHas('attendances', ['event_id' => $event->id, 'member_id' => $members[1]->id, 'status' => 'permit']);
    }
}
