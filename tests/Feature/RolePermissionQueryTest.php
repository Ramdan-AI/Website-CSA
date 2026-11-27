<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RolePermissionQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repeated_permission_checks_query_the_database_once(): void
    {
        $role = Role::create(['name' => 'Sekretaris', 'slug' => 'sekretaris']);
        $role->permissions()->attach(Permission::create(['name' => 'Kelola Berita', 'slug' => 'manage-news']));
        $fresh = Role::findOrFail($role->id);

        DB::enableQueryLog();

        $this->assertTrue($fresh->hasPermission('manage-news'));
        $this->assertTrue($fresh->hasPermission('manage-news'));
        $this->assertFalse($fresh->hasPermission('manage-finance'));

        $this->assertCount(1, DB::getQueryLog());
    }
}
