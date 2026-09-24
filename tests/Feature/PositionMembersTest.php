<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Permission;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class PositionMembersTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        foreach (['manage-positions', 'manage-members'] as $slug) {
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

    public function test_position_members_page_renders_member_options(): void
    {
        $admin = $this->admin();
        $division = Division::create(['name' => 'Div', 'slug' => 'div', 'is_active' => true]);
        $position = Position::create(['name' => 'Sekretaris', 'max_members' => 1]);

        Member::create([
            'name' => 'Budi Santoso',
            'division_id' => $division->id,
            'email' => 'budi@example.test',
            'nim' => '20240001',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(
            route('admin.positions.members', ['roleSlug' => 'super-admin', 'position' => $position->id])
        );

        $response->assertOk();
        // Nama anggota harus ter-render pada opsi dropdown (bug "anggota tidak ditemukan").
        $response->assertSee('Budi Santoso', false);
    }

    public function test_members_show_page_displays_level(): void
    {
        $admin = $this->admin();
        $division = Division::create(['name' => 'Div', 'slug' => 'div', 'is_active' => true, 'has_levels' => true]);

        $member = Member::create([
            'name' => 'Ani',
            'division_id' => $division->id,
            'email' => 'ani@example.test',
            'nim' => '20240002',
            'semester' => 3,
            'level' => 2,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(
            route('admin.members.show', ['roleSlug' => 'super-admin', 'member' => $member->id])
        );

        $response->assertOk();
        $response->assertSee('Tingkatan');
        $response->assertSee('Menengah');
    }
}
