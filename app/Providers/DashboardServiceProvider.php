<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    /**
     * Bagikan data dashboard yang dipakai banyak halaman sekaligus.
     *
     * View composer dipakai agar pengumuman dan ringkasan aktivitas tidak
     * perlu diulang di setiap method controller.
     */
    public function boot(): void
    {
        View::composer([
            'pages.dashboard',
            'pages.dashboard-secretary',
            'pages.dashboard-treasurer',
            'pages.dashboard-division',
            'pages.dashboard-member',
        ], function ($view) {
            $view->with('announcements', $this->announcements());
            $view->with('systemActivity', $this->systemActivity());
        });
    }

    /**
     * Pengumuman aktif, disematkan lebih dulu, maksimal 4 buah.
     */
    private function announcements()
    {
        return Announcement::query()
            ->with('creator:id,name')
            ->active()
            ->ordered()
            ->take(4)
            ->get();
    }

    /**
     * Ringkasan aktivitas sistem untuk pemegang view-activity-log.
     * Role lain menerima koleksi kosong tanpa query tambahan.
     */
    private function systemActivity()
    {
        $user = Auth::user();

        if (!$user?->role?->hasPermission('view-activity-log')) {
            return collect();
        }

        return ActivityLog::query()
            ->with('user:id,name,avatar')
            ->latestFirst()
            ->take(6)
            ->get();
    }
}
