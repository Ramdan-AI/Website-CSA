<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Daftar seluruh permission sistem.
     *
     * Seeder ini idempotent: bisa dijalankan ulang tanpa error duplikat,
     * sehingga aman dipakai saat menambah permission baru.
     */
    public function run(): void
    {
        $permissions = [
            // Data master
            ['name' => 'Kelola Divisi',          'slug' => 'manage-divisions',         'description' => 'Mengelola data divisi'],
            ['name' => 'Kelola Jabatan',         'slug' => 'manage-positions',         'description' => 'Mengelola data jabatan'],

            // Keanggotaan
            ['name' => 'Kelola Anggota',         'slug' => 'manage-members',           'description' => 'Mengelola seluruh data anggota'],
            ['name' => 'Kelola Anggota Divisi',  'slug' => 'manage-division-members',  'description' => 'Mengelola anggota dalam divisi sendiri'],
            ['name' => 'Kelola Kehadiran',       'slug' => 'manage-attendances',       'description' => 'Mengelola data kehadiran'],
            ['name' => 'Lihat Kehadiran',        'slug' => 'view-attendances',         'description' => 'Melihat data kehadiran'],
            ['name' => 'Rekap Kehadiran Divisi', 'slug' => 'view-division-reports',    'description' => 'Melihat rekap kehadiran divisi sendiri'],

            // Publikasi
            ['name' => 'Kelola Berita',          'slug' => 'manage-news',              'description' => 'Mengelola berita & kategori'],
            ['name' => 'Kelola Dokumen',         'slug' => 'manage-documents',         'description' => 'Mengelola dokumen'],

            // Kesekretariatan
            ['name' => 'Kelola Surat',           'slug' => 'manage-letters',           'description' => 'Mengelola surat masuk dan keluar'],
            ['name' => 'Kelola Notulen',         'slug' => 'manage-minutes',           'description' => 'Mengelola notulen rapat'],

            // Kegiatan
            ['name' => 'Kelola Acara',           'slug' => 'manage-events',            'description' => 'Mengelola acara'],
            ['name' => 'Lihat Acara',            'slug' => 'view-events',              'description' => 'Melihat daftar acara'],
            ['name' => 'Kelola Pendaftaran',     'slug' => 'manage-registrations',     'description' => 'Mengelola pendaftaran acara'],

            // Keuangan
            ['name' => 'Kelola Keuangan',        'slug' => 'manage-finance',           'description' => 'Mengelola pemasukan & pengeluaran'],
            ['name' => 'Lihat Keuangan',         'slug' => 'view-finance',             'description' => 'Melihat data keuangan'],
            ['name' => 'Laporan Keuangan',       'slug' => 'view-finance-report',      'description' => 'Melihat laporan keuangan terpadu'],

            // Media
            ['name' => 'Kelola Media',           'slug' => 'manage-media',             'description' => 'Mengelola album & galeri'],

            // Showcase
            ['name' => 'Kelola Showcase',        'slug' => 'manage-projects',          'description' => 'Memoderasi seluruh proyek showcase'],

            // Komunikasi
            ['name' => 'Kelola Pengumuman',      'slug' => 'manage-announcements',     'description' => 'Membuat dan mengelola pengumuman organisasi'],

            // Audit
            ['name' => 'Lihat Log Sistem',       'slug' => 'view-activity-log',        'description' => 'Melihat riwayat aktivitas pengguna'],

            // Sistem
            ['name' => 'Kelola Pengaturan',      'slug' => 'manage-settings',          'description' => 'Mengelola pengaturan website'],
            ['name' => 'Kelola Role & Izin',     'slug' => 'manage-roles',             'description' => 'Mengelola role dan permission'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                ['name' => $permission['name'], 'description' => $permission['description']]
            );
        }
    }
}
