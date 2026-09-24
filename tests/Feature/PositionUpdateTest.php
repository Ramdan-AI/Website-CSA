<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class PositionUpdateTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        Permission::firstOrCreate(['slug' => 'manage-positions'], ['name' => 'manage-positions']);
        Gate::define('manage-positions', fn (User $u) => true);

        $role = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => null]);

        return User::create([
            'role_id' => $role->id,
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'is_active' => true,
        ]);
    }

    public function test_max_members_is_updated(): void
    {
        $admin = $this->admin();
        $position = Position::create(['name' => 'Wakil Ketua Divisi', 'max_members' => 1]);

        $this->actingAs($admin)->put(
            route('admin.positions.update', ['roleSlug' => 'super-admin', 'position' => $position->id]),
            ['name' => 'Wakil Ketua Divisi', 'max_members' => 5, 'description' => 'Mendampingi ketua']
        )->assertRedirect(route('admin.positions.index'));

        $this->assertSame(5, $position->fresh()->max_members);
    }
}
