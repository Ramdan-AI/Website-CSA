<?php

namespace Tests\Feature;

use App\Models\NewsCategory;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class NewsCategoryModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created_via_modal_endpoint(): void
    {
        $user = $this->superAdmin();

        $response = $this->actingAs($user)->post(
            route('admin.news-categories.store', ['roleSlug' => $user->role->slug]),
            ['name' => 'Prestasi', 'description' => 'Berita prestasi anggota']
        );

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news_categories', ['name' => 'Prestasi']);
    }

    public function test_category_can_be_updated_and_deleted(): void
    {
        $user = $this->superAdmin();
        $category = NewsCategory::create(['name' => 'Lama', 'slug' => 'lama']);

        $this->actingAs($user)->put(
            route('admin.news-categories.update', ['roleSlug' => $user->role->slug, 'news_category' => $category->id]),
            ['name' => 'Baru', 'description' => null]
        )->assertRedirect();

        $this->assertSame('Baru', $category->fresh()->name);

        $this->actingAs($user)->delete(
            route('admin.news-categories.destroy', ['roleSlug' => $user->role->slug, 'news_category' => $category->id])
        )->assertRedirect();

        $this->assertNull(NewsCategory::find($category->id));
    }

    private function superAdmin(): User
    {
        Permission::firstOrCreate(['slug' => 'manage-news'], ['name' => 'manage-news']);
        Gate::define('manage-news', fn (User $u) => true);

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
