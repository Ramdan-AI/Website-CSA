<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // URL aset mengikuti host request agar gambar tetap tampil saat
        // aplikasi dibuka lewat domain lokal (mis. web-csa.test) meskipun
        // APP_URL menunjuk host lain. Set ASSET_URL di .env untuk mengunci.
        if (!config('app.asset_url') && !app()->runningInConsole() && request()->getHost()) {
            URL::forceRootUrl(request()->getSchemeAndHttpHost());
        }

        $requiredPermissions = [
            // Data master
            'manage-divisions',
            'manage-positions',

            // Keanggotaan
            'manage-members',
            'manage-division-members',
            'manage-attendances',
            'view-attendances',
            'view-division-reports',

            // Publikasi
            'manage-news',
            'manage-documents',

            // Kesekretariatan
            'manage-letters',
            'manage-minutes',

            // Kegiatan
            'manage-events',
            'view-events',
            'manage-registrations',

            // Keuangan
            'manage-finance',
            'view-finance',
            'view-finance-report',

            // Media & showcase
            'manage-media',
            'manage-projects',

            // Komunikasi & audit
            'manage-announcements',
            'view-activity-log',

            // Sistem
            'manage-settings',
            'manage-roles',
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