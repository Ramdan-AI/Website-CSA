<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AnnouncementStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_announcement_form_stores_selected_category_and_pinned(): void
    {
        $user = $this->superAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.announcements.store', ['roleSlug' => $user->role->slug]),
            [
                'title' => 'Pengumuman Uji',
                'body' => 'Isi uji.',
                'category' => 'penting',
                'status' => 'published',
                'pinned' => '1',
            ]
        );

        $response->assertRedirect();

        $announcement = Announcement::where('title', 'Pengumuman Uji')->first();
        $this->assertNotNull($announcement);
        $this->assertSame('penting', $announcement->category);
        $this->assertTrue($announcement->pinned);
    }

    private function superAdmin(): User
    {
        foreach (['manage-announcements'] as $slug) {
            Permission::firstOrCreate(['slug' => $slug], ['name' => $slug]);
            Gate::define($slug, fn (User $u) => true);
        }

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
