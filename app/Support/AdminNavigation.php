<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminNavigation
{
    /**
     * Grup menu yang terlihat oleh pengguna saat ini.
     *
     * Grup gugur bila role tidak cocok, bila izinnya tidak dimiliki,
     * atau bila seluruh itemnya tersaring habis.
     */
    public function groups(): array
    {
        $groups = collect(config('admin.menu'))
            ->filter(fn (array $group) => $this->allowedForRole($group['roles'] ?? []))
            ->filter(fn (array $group) => $this->authorized($group['permissions'] ?? []))
            ->map(function (array $group) {
                $group['items'] = collect($group['items'])
                    ->filter(fn (array $item) => $this->allowedForRole($item['roles'] ?? []))
                    ->filter(fn (array $item) => $this->authorized(Arr::wrap($item['permission'] ?? null)))
                    ->values()
                    ->all();

                return $group;
            })
            ->filter(fn (array $group) => count($group['items']) > 0)
            ->values();

        return $groups->all();
    }

    /**
     * Tautan pengaturan di footer sidebar, disaring berdasarkan izin.
     */
    public function settings(): array
    {
        return collect(config('admin.settings'))
            ->filter(fn (array $item) => $this->authorized(Arr::wrap($item['permission'] ?? null)))
            ->values()
            ->all();
    }

    /**
     * True bila tidak ada batasan izin, atau salah satu izin dimiliki.
     */
    private function authorized(array $permissions): bool
    {
        return empty($permissions) || Gate::any($permissions);
    }

    /**
     * True bila tidak ada batasan role, atau role pengguna terdaftar.
     */
    private function allowedForRole(array $roles): bool
    {
        if (empty($roles)) {
            return true;
        }

        $current = Auth::user()?->role?->slug;

        return $current !== null && in_array($current, $roles, true);
    }
}
