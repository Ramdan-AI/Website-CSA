<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $requiredPermissions = [
            'manage-divisions',
            'manage-positions',
            'manage-settings',
            'manage-roles',
            'manage-members',
            'manage-attendances',
            'view-attendances',
            'manage-news',
            'manage-documents',
            'manage-events',
            'view-events',
            'manage-registrations',
            'manage-finance',
            'view-finance',
            'manage-media',
        ];

        try {
            $permissionsFromDb = Permission::all()->keyBy('slug');

            foreach ($requiredPermissions as $slug) {
                if ($permissionsFromDb->has($slug)) {
                    $permission = $permissionsFromDb->get($slug);
                    Gate::define($slug, function ($user) use ($permission) {
                        return $user->role && $user->role->hasPermission($permission->slug);
                    });
                } else {
                    Gate::define($slug, fn ($user) => false);
                }
            }
        } catch (\Exception $e) {
            foreach ($requiredPermissions as $slug) {
                Gate::define($slug, fn ($user) => false);
            }
        }
    }
}