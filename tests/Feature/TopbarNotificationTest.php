<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopbarNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Pengumuman aktif harus muncul di dropdown notifikasi topbar
     * (tombol lonceng), bukan hanya di card dashboard.
     */
    public function test_active_announcement_appears_in_topbar_notifications(): void
    {
        $user = $this->userWithRole();

        Announcement::create([
            'created_by' => $user->id,
            'title' => 'Rapat besar minggu depan',
            'body' => 'Hadir tepat waktu.',
            'category' => Announcement::CATEGORY_INFO,
            'status' => 'published',
            'pinned' => false,
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard', ['roleSlug' => $user->role->slug]));

        $response->assertOk();
        $response->assertSee('Rapat besar minggu depan');
        $response->assertSee('megaphone');
    }

    /**
     * Pengumuman berstatus draft tidak boleh bocor ke notifikasi.
     */
    public function test_draft_announcement_is_not_announced(): void
    {
        $user = $this->userWithRole();

        Announcement::create([
            'created_by' => $user->id,
            'title' => 'Draf rahasia internal',
            'body' => 'Belum terbit.',
            'category' => Announcement::CATEGORY_INFO,
            'status' => 'draft',
            'pinned' => false,
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard', ['roleSlug' => $user->role->slug]));

        $response->assertOk();
        $response->assertDontSee('Draf rahasia internal');
    }

    /**
     * Menandai notifikasi terbaca mengembalikan sukses dan mengisi sesi.
     */
    public function test_marking_notifications_seen_succeeds(): void
    {
        $user = $this->userWithRole();

        $response = $this->actingAs($user)->post(route('admin.notifications.seen', ['roleSlug' => $user->role->slug]));

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertNotEmpty(session('notifications_seen_at'));
    }

    /**
     * User dengan role dasar (anggota) untuk menguji tampilan notifikasi.
     */
    private function userWithRole(): User
    {
        Permission::firstOrCreate(['slug' => 'view-activity-log'], ['name' => 'Lihat Log Sistem']);

        $role = Role::create([
            'name' => 'Anggota',
            'slug' => 'anggota',
            'description' => null,
        ]);

        return User::create([
            'role_id' => $role->id,
            'name' => 'Anggota Biasa',
            'email' => 'anggota@example.test',
            'password' => 'password',
            'is_active' => true,
        ]);
    }
}
