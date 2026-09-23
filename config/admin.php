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

    /*
     | Setiap grup mendukung:
     |   roles       : grup hanya tampil untuk role ini (kosong = semua role)
     |   permissions : grup tampil bila user punya salah satu izin ini
     |
     | Setiap item mendukung:
     |   permission : item tampil bila user punya izin ini
     |   roles      : item tampil hanya untuk role ini
     */
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

        // Menu pribadi hanya untuk anggota: presensi, riwayat, dan proyeknya.
        // Admin tidak perlu ini karena tidak punya profil keanggotaan.
        [
            'id'          => 'my-workspace',
            'label'       => 'Workspace Saya',
            'color'       => 'gray',
            'roles'       => ['anggota'],
            'permissions' => [],
            'items'       => [
                ['icon' => 'calendar-days', 'label' => 'Kegiatan & Presensi', 'route' => 'admin.member.calendar', 'pattern' => 'admin.member.calendar'],
                ['icon' => 'user-check', 'label' => 'Riwayat Kehadiran', 'route' => 'admin.member.attendance', 'pattern' => 'admin.member.attendance'],
                ['icon' => 'rocket', 'label' => 'Proyek Saya', 'route' => 'admin.showcase.index', 'pattern' => 'admin.showcase.*'],
            ],
        ],

        // Layanan publik untuk anggota: berita, dokumen, galeri.
        [
            'id'          => 'member-services',
            'label'       => 'Informasi',
            'color'       => 'gray',
            'roles'       => ['anggota'],
            'permissions' => [],
            'items'       => [
                ['icon' => 'newspaper', 'label' => 'Berita', 'route' => 'admin.member.news', 'pattern' => 'admin.member.news'],
                ['icon' => 'folder-open', 'label' => 'Dokumen', 'route' => 'admin.member.documents', 'pattern' => 'admin.member.documents'],
                ['icon' => 'images', 'label' => 'Galeri', 'route' => 'admin.member.gallery', 'pattern' => 'admin.member.gallery'],
            ],
        ],

        // Komunikasi: pengumuman organisasi.
        [
            'id'          => 'communication',
            'label'       => 'Komunikasi',
            'color'       => 'amber',
            'permissions' => ['manage-announcements'],
            'items'       => [
                ['icon' => 'megaphone', 'label' => 'Pengumuman', 'route' => 'admin.announcements.index', 'pattern' => 'admin.announcements.*'],
            ],
        ],

        // Kesekretariatan: administrasi surat, notulen, dan arsip berita.
        [
            'id'          => 'secretariat',
            'label'       => 'Kesekretariatan',
            'color'       => 'teal',
            'permissions' => ['manage-letters', 'manage-minutes'],
            'items'       => [
                ['icon' => 'mail', 'label' => 'Surat & Arsip', 'route' => 'admin.letters.index', 'pattern' => 'admin.letters.*', 'permission' => 'manage-letters'],
                ['icon' => 'notebook-pen', 'label' => 'Notulen Rapat', 'route' => 'admin.minutes.index', 'pattern' => 'admin.minutes.*', 'permission' => 'manage-minutes'],
            ],
        ],

        // Publikasi: berita dan dokumen yang tampil ke publik.
        [
            'id'          => 'publication',
            'label'       => 'Publikasi',
            'color'       => 'teal',
            'permissions' => ['manage-news', 'manage-documents'],
            'items'       => [
                ['icon' => 'tags', 'label' => 'Kategori Berita', 'route' => 'admin.news-categories.index', 'pattern' => 'admin.news-categories.*', 'permission' => 'manage-news'],
                ['icon' => 'newspaper', 'label' => 'Berita', 'route' => 'admin.news.index', 'pattern' => 'admin.news.*', 'permission' => 'manage-news'],
                ['icon' => 'folder-open', 'label' => 'Dokumen', 'route' => 'admin.documents.index', 'pattern' => 'admin.documents.*', 'permission' => 'manage-documents'],
            ],
        ],

        // Divisi: kelola anggota dan rekap kehadiran divisi sendiri.
        // Hanya muncul untuk ketua & wakil divisi yang punya izinnya.
        [
            'id'          => 'my-division',
            'label'       => 'Divisi Saya',
            'color'       => 'purple',
            'roles'       => ['ketua-divisi', 'wakil-ketua-divisi'],
            'permissions' => ['manage-division-members', 'view-division-reports'],
            'items'       => [
                ['icon' => 'users', 'label' => 'Kelola Anggota', 'route' => 'admin.division-manage.index', 'pattern' => 'admin.division-manage.*', 'permission' => 'manage-division-members'],
                ['icon' => 'clipboard-check', 'label' => 'Rekap Kehadiran', 'route' => 'admin.division-report.index', 'pattern' => 'admin.division-report.*', 'permission' => 'view-division-reports'],
            ],
        ],

        // Data master: hanya pimpinan dengan izin terkait.
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
            'id'          => 'events',
            'label'       => 'Kegiatan',
            'color'       => 'cyan',
            'permissions' => ['manage-events', 'view-events', 'manage-registrations'],
            'items'       => [
                ['icon' => 'calendar-days', 'label' => 'Acara', 'route' => 'admin.events.index', 'pattern' => 'admin.events.*'],
                ['icon' => 'clipboard-list', 'label' => 'Pendaftaran', 'route' => 'admin.event-registrations.index', 'pattern' => 'admin.event-registrations.*', 'permission' => 'manage-registrations'],
            ],
        ],

        [
            'id'          => 'finance',
            'label'       => 'Keuangan',
            'color'       => 'emerald',
            'permissions' => ['manage-finance', 'view-finance', 'view-finance-report'],
            'items'       => [
                ['icon' => 'trending-up', 'label' => 'Pemasukan', 'route' => 'admin.incomes.index', 'pattern' => 'admin.incomes.*', 'permission' => 'view-finance'],
                ['icon' => 'trending-down', 'label' => 'Pengeluaran', 'route' => 'admin.expenses.index', 'pattern' => 'admin.expenses.*', 'permission' => 'view-finance'],
                ['icon' => 'chart-line', 'label' => 'Laporan Keuangan', 'route' => 'admin.finance-report.index', 'pattern' => 'admin.finance-report.*', 'permission' => 'view-finance-report'],
            ],
        ],

        [
            'id'          => 'media',
            'label'       => 'Media',
            'color'       => 'pink',
            'permissions' => ['manage-media'],
            'items'       => [
                ['icon' => 'images', 'label' => 'Album', 'route' => 'admin.albums.index', 'pattern' => 'admin.albums.*'],
                ['icon' => 'image', 'label' => 'Galeri', 'route' => 'admin.galleries.index', 'pattern' => 'admin.galleries.*'],
            ],
        ],
    ],

    'settings' => [
        [
            'label'      => 'Log Sistem',
            'icon'       => 'scroll-text',
            'route'      => 'admin.activity-log.index',
            'pattern'    => 'admin.activity-log.*',
            'permission' => 'view-activity-log',
        ],
        [
            'label'      => 'Pendaftaran & Komunitas',
            'icon'       => 'user-plus',
            'route'      => 'admin.registration.edit',
            'pattern'    => 'admin.registration.*',
            'permission' => 'manage-settings',
        ],
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
