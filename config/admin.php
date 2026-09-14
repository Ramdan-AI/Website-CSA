<?php

return [

    /**
     * Color palette for sidebar sections.
     *
     * Matched by the 'color' key on each menu group and rendered by
     * views/components/admin/sidebar-item.blade.php.
     */
    'colors' => [
        'blue'    => ['bg' => 'from-blue-500 to-blue-600',    'glow' => 'rgba(59,130,246,0.25)'],
        'purple'  => ['bg' => 'from-purple-500 to-purple-600', 'glow' => 'rgba(139,92,246,0.25)'],
        'orange'  => ['bg' => 'from-orange-500 to-orange-600', 'glow' => 'rgba(249,115,22,0.25)'],
        'teal'    => ['bg' => 'from-teal-500 to-teal-600',    'glow' => 'rgba(20,184,166,0.25)'],
        'cyan'    => ['bg' => 'from-cyan-500 to-cyan-600',    'glow' => 'rgba(6,182,212,0.25)'],
        'emerald' => ['bg' => 'from-emerald-500 to-emerald-600', 'glow' => 'rgba(16,185,129,0.25)'],
        'pink'    => ['bg' => 'from-pink-500 to-pink-600',    'glow' => 'rgba(236,72,153,0.25)'],
        'gray'    => ['bg' => 'from-gray-500 to-gray-600',    'glow' => 'rgba(107,114,128,0.2)'],
    ],

    /**
     | Navigation groups.
     |
     | Each group:
     |   - id:          internal identifier (used for Alpine hover tracking)
     |   - label:       section heading in the sidebar
     |   - color:       key into 'colors' above
     |   - permissions: group is hidden unless the user has ANY of these
     |   - items:       each item = icon (Font Awesome), label, route, pattern;
     |                  optional 'permission' hides that item per-user
     */
    'menu' => [
        [
            'id'          => 'dashboard',
            'label'       => 'Utama',
            'color'       => 'blue',
            'permissions' => [],
            'items'       => [
                ['icon' => 'fa-chart-pie', 'label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard'],
            ],
        ],

        [
            'id'          => 'member-services',
            'label'       => 'Layanan Anggota',
            'color'       => 'gray',
            'permissions' => [],
            'items'       => [
                ['icon' => 'fa-calendar-alt', 'label' => 'Kegiatan & Presensi', 'route' => 'admin.member.calendar', 'pattern' => 'admin.member.calendar'],
                ['icon' => 'fa-user-check', 'label' => 'Riwayat Kehadiran', 'route' => 'admin.member.attendance', 'pattern' => 'admin.member.attendance'],
                ['icon' => 'fa-newspaper', 'label' => 'Berita', 'route' => 'admin.member.news', 'pattern' => 'admin.member.news'],
                ['icon' => 'fa-folder-open', 'label' => 'Dokumen', 'route' => 'admin.member.documents', 'pattern' => 'admin.member.documents'],
                ['icon' => 'fa-users', 'label' => 'Anggota Divisi', 'route' => 'admin.member.division', 'pattern' => 'admin.member.division'],
                ['icon' => 'fa-images', 'label' => 'Galeri', 'route' => 'admin.member.gallery', 'pattern' => 'admin.member.gallery'],
                ['icon' => 'fa-cog', 'label' => 'Pengaturan', 'route' => 'admin.member.settings', 'pattern' => 'admin.member.settings'],
            ],
        ],

        [
            'id'          => 'master-data',
            'label'       => 'Data Master',
            'color'       => 'purple',
            'permissions' => ['manage-divisions', 'manage-positions'],
            'items'       => [
                ['icon' => 'fa-building', 'label' => 'Divisi', 'route' => 'admin.divisions.index', 'pattern' => 'admin.divisions.*', 'permission' => 'manage-divisions'],
                ['icon' => 'fa-briefcase', 'label' => 'Jabatan', 'route' => 'admin.positions.index', 'pattern' => 'admin.positions.*', 'permission' => 'manage-positions'],
            ],
        ],

        [
            'id'          => 'operational',
            'label'       => 'Keanggotaan',
            'color'       => 'orange',
            'permissions' => ['manage-members', 'manage-attendances'],
            'items'       => [
                ['icon' => 'fa-users', 'label' => 'Anggota', 'route' => 'admin.members.index', 'pattern' => 'admin.members.*', 'permission' => 'manage-members'],
                ['icon' => 'fa-user-check', 'label' => 'Kehadiran', 'route' => 'admin.attendances.index', 'pattern' => 'admin.attendances.*', 'permission' => 'manage-attendances'],
            ],
        ],

        [
            'id'          => 'publication',
            'label'       => 'Publikasi',
            'color'       => 'teal',
            'permissions' => ['manage-news', 'manage-documents'],
            'items'       => [
                ['icon' => 'fa-tags', 'label' => 'Kategori', 'route' => 'admin.news-categories.index', 'pattern' => 'admin.news-categories.*', 'permission' => 'manage-news'],
                ['icon' => 'fa-newspaper', 'label' => 'Berita', 'route' => 'admin.news.index', 'pattern' => 'admin.news.*', 'permission' => 'manage-news'],
                ['icon' => 'fa-folder-open', 'label' => 'Dokumen', 'route' => 'admin.documents.index', 'pattern' => 'admin.documents.*', 'permission' => 'manage-documents'],
            ],
        ],

        [
            'id'          => 'events',
            'label'       => 'Kegiatan',
            'color'       => 'cyan',
            'permissions' => ['manage-events', 'view-events'],
            'items'       => [
                ['icon' => 'fa-calendar-alt', 'label' => 'Acara', 'route' => 'admin.events.index', 'pattern' => 'admin.events.*'],
            ],
        ],

        [
            'id'          => 'finance',
            'label'       => 'Keuangan',
            'color'       => 'emerald',
            'permissions' => ['manage-finance', 'view-finance'],
            'items'       => [
                ['icon' => 'fa-arrow-trend-up', 'label' => 'Pemasukan', 'route' => 'admin.incomes.index', 'pattern' => 'admin.incomes.*', 'permission' => 'manage-finance'],
                ['icon' => 'fa-arrow-trend-down', 'label' => 'Pengeluaran', 'route' => 'admin.expenses.index', 'pattern' => 'admin.expenses.*', 'permission' => 'manage-finance'],
            ],
        ],

        [
            'id'          => 'media',
            'label'       => 'Media',
            'color'       => 'pink',
            'permissions' => ['manage-media'],
            'items'       => [
                ['icon' => 'fa-images', 'label' => 'Album', 'route' => 'admin.albums.index', 'pattern' => 'admin.albums.*', 'permission' => 'manage-media'],
                ['icon' => 'fa-photo-video', 'label' => 'Galeri', 'route' => 'admin.galleries.index', 'pattern' => 'admin.galleries.*', 'permission' => 'manage-media'],
            ],
        ],
    ],

    /**
     | Fixed footer links in the sidebar (settings section).
     */
    'settings' => [
        [
            'label'      => 'Pengaturan',
            'icon'       => 'fa-cog',
            'route'      => 'admin.settings.edit',
            'pattern'    => 'admin.settings.*',
            'permission' => 'manage-settings',
        ],
        [
            'label'      => 'Role & Permission',
            'icon'       => 'fa-shield-alt',
            'route'      => 'admin.roles.index',
            'pattern'    => 'admin.roles.*',
            'permission' => 'manage-roles',
        ],
    ],

];