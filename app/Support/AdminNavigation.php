<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class AdminNavigation
{
    /**
     * Menu groups visible to the current user.
     *
     * A group is kept when the user holds ANY of its required permissions;
     * individual items are filtered the same way. Groups left empty are dropped.
     */
    public function groups(): array
    {
        $groups = collect(config('admin.menu'))
            ->filter(fn (array $group) => $this->authorized($group['permissions'] ?? []))
            ->map(function (array $group) {
                $group['items'] = collect($group['items'])
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
     * Settings / role links for the sidebar footer, filtered by permission.
     */
    public function settings(): array
    {
        return collect(config('admin.settings'))
            ->filter(fn (array $item) => $this->authorized([$item['permission']]))
            ->values()
            ->all();
    }

    /**
     * True when $permissions is empty (no restriction) or any permission passes.
     */
    private function authorized(array $permissions): bool
    {
        return empty($permissions) || Gate::any($permissions);
    }
}