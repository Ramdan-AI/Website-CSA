<?php

return [

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

    'menu' => [
        [
            'id'          => 'dashboard',
            'label'       => 'Utama',
            'color'       => 'blue',
            'permissions' => [],
            'items'       => [
                ['icon' => 'layout-dashboard', 'label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard'],
            ],
        ],

        [
            'id'          => 'member-services',
            'label'       => 'Layanan Anggota',
            'color'       => 'gray',
            'roles'       => ['anggota'],
            'permissions' => [],
            'items'       => [
                ['icon' => 'calendar-days', 'label' => 'Kegiatan & Presensi', 'route' => 'admin.member.calendar', 'pattern' => 'admin.member.calendar'],
                ['icon' => 'user-check', 'label' => 'Riwayat Kehadiran', 'route' => 'admin.member.attendance', 'pattern' => 'admin.member.attendance'],
                ['icon' => 'newspaper', 'label' => 'Berita', 'route' => 'admin.member.news', 'pattern' => 'admin.member.news'],
                ['icon' => 'folder-open', 'label' => 'Dokumen', 'route' => 'admin.member.documents', 'pattern' => 'admin.member.documents'],
                ['icon' => 'users', 'label' => 'Anggota Divisi', 'route' => 'admin.member.division', 'pattern' => 'admin.member.division'],
                ['icon' => 'images', 'label' => 'Galeri', 'route' => 'admin.member.gallery', 'pattern' => 'admin.member.gallery'],
                ['icon' => 'settings', 'label' => 'Pengaturan', 'route' => 'admin.member.settings', 'pattern' => 'admin.member.settings'],
            ],
        ],

        [
            'id'          => 'master-data',
            'label'       => 'Data Master',
            'color'       => 'purple',
            'permissions' => ['manage-divisions', 'manage-positions'],
            'items'       => [
                ['icon' => 'building-2', 'label' => 'Divisi', 'route' => 'admin.divisions.index', 'pattern' => 'admin.divisions.*', 'permission' => 'manage-divisions'],
                ['icon' => 'briefcase', 'label' => 'Jabatan', 'route' => 'admin.positions.index', 'pattern' => 'admin.positions.*', 'permission' => 'manage-positions'],
            ],
        ],

        [
            'id'          => 'operational',
            'label'       => 'Keanggotaan',
            'color'       => 'orange',
            'permissions' => ['manage-members', 'manage-attendances'],
            'items'       => [
                ['icon' => 'users', 'label' => 'Anggota', 'route' => 'admin.members.index', 'pattern' => 'admin.members.*', 'permission' => 'manage-members'],
                ['icon' => 'clipboard-check', 'label' => 'Kehadiran', 'route' => 'admin.attendances.index', 'pattern' => 'admin.attendances.*', 'permission' => 'manage-attendances'],
            ],
        ],

        [
            'id'          => 'publication',
            'label'       => 'Publikasi',
            'color'       => 'teal',
            'permissions' => ['manage-news', 'manage-documents'],
            'items'       => [
                ['icon' => 'tags', 'label' => 'Kategori', 'route' => 'admin.news-categories.index', 'pattern' => 'admin.news-categories.*', 'permission' => 'manage-news'],
                ['icon' => 'newspaper', 'label' => 'Berita', 'route' => 'admin.news.index', 'pattern' => 'admin.news.*', 'permission' => 'manage-news'],
                ['icon' => 'folder-open', 'label' => 'Dokumen', 'route' => 'admin.documents.index', 'pattern' => 'admin.documents.*', 'permission' => 'manage-documents'],
            ],
        ],

        [
            'id'          => 'events',
            'label'       => 'Kegiatan',
            'color'       => 'cyan',
            'permissions' => ['manage-events', 'view-events'],
            'items'       => [
                ['icon' => 'calendar-days', 'label' => 'Acara', 'route' => 'admin.events.index', 'pattern' => 'admin.events.*'],
            ],
        ],

        [
            'id'          => 'finance',
            'label'       => 'Keuangan',
            'color'       => 'emerald',
            'permissions' => ['manage-finance', 'view-finance'],
            'items'       => [
                ['icon' => 'trending-up', 'label' => 'Pemasukan', 'route' => 'admin.incomes.index', 'pattern' => 'admin.incomes.*', 'permission' => 'manage-finance'],
                ['icon' => 'trending-down', 'label' => 'Pengeluaran', 'route' => 'admin.expenses.index', 'pattern' => 'admin.expenses.*', 'permission' => 'manage-finance'],
            ],
        ],

        [
            'id'          => 'media',
            'label'       => 'Media',
            'color'       => 'pink',
            'permissions' => ['manage-media'],
            'items'       => [
                ['icon' => 'images', 'label' => 'Album', 'route' => 'admin.albums.index', 'pattern' => 'admin.albums.*', 'permission' => 'manage-media'],
                ['icon' => 'image', 'label' => 'Galeri', 'route' => 'admin.galleries.index', 'pattern' => 'admin.galleries.*', 'permission' => 'manage-media'],
            ],
        ],
    ],

    'settings' => [
        [
            'label'      => 'Pengaturan',
            'icon'       => 'settings',
            'route'      => 'admin.settings.edit',
            'pattern'    => 'admin.settings.*',
            'permission' => 'manage-settings',
        ],
        [
            'label'      => 'Role & Permission',
            'icon'       => 'shield',
            'route'      => 'admin.roles.index',
            'pattern'    => 'admin.roles.*',
            'permission' => 'manage-roles',
        ],
    ],

];
