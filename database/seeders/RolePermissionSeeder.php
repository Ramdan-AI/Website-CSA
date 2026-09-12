<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = Permission::all()->keyBy('slug');

        $rolePermissions = [
            'super-admin' => $permissions->pluck('id')->toArray(),

            'ketua' => [
                'manage-members',
                'manage-attendances',
                'manage-news',
                'manage-documents',
                'manage-events',
                'manage-registrations',
                'manage-finance',
                'view-finance',
                'manage-media',
            ],

            'wakil-ketua' => [
                'manage-members',
                'manage-attendances',
                'manage-news',
                'manage-documents',
                'manage-events',
                'manage-registrations',
                'manage-finance',
                'view-finance',
                'manage-media',
            ],

            'ketua-divisi' => [
                'manage-members',
                'manage-attendances',
                'manage-events',
                'manage-registrations',
                'view-finance',
                'manage-media',
            ],

            'wakil-ketua-divisi' => [
                'manage-attendances',
                'manage-events',
                'manage-registrations',
                'view-finance',
                'manage-media',
            ],

            'sekretaris' => [
                'manage-news',
                'manage-documents',
                'manage-attendances',
                'manage-media',
            ],

            'bendahara' => [
                'manage-finance',
                'view-finance',
                'manage-media',
            ],

            'anggota' => [
                'view-finance',
                'manage-media',
            ],
        ];

        foreach ($rolePermissions as $roleSlug => $permSlugs) {
            $role = Role::where('slug', $roleSlug)->first();
            if (!$role) continue;

            $permIds = collect($permSlugs)
                ->map(fn($slug) => $permissions[$slug]->id ?? null)
                ->filter()
                ->toArray();

            $role->permissions()->sync($permIds);
        }
    }
}
