<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::insert([
            ['name' => 'Kelola Divisi',          'slug' => 'manage-divisions',       'description' => 'Mengelola data divisi'],
            ['name' => 'Kelola Jabatan',         'slug' => 'manage-positions',       'description' => 'Mengelola data jabatan'],
            ['name' => 'Kelola Anggota',         'slug' => 'manage-members',         'description' => 'Mengelola data anggota'],
            ['name' => 'Kelola Kehadiran',       'slug' => 'manage-attendances',     'description' => 'Mengelola data kehadiran'],
            ['name' => 'Kelola Berita',          'slug' => 'manage-news',            'description' => 'Mengelola berita & kategori'],
            ['name' => 'Kelola Dokumen',         'slug' => 'manage-documents',       'description' => 'Mengelola dokumen'],
            ['name' => 'Kelola Acara',           'slug' => 'manage-events',          'description' => 'Mengelola acara'],
            ['name' => 'Kelola Pendaftaran',     'slug' => 'manage-registrations',   'description' => 'Mengelola pendaftaran acara'],
            ['name' => 'Kelola Keuangan',        'slug' => 'manage-finance',         'description' => 'Mengelola pemasukan & pengeluaran'],
            ['name' => 'Lihat Keuangan',         'slug' => 'view-finance',           'description' => 'Melihat laporan keuangan'],
            ['name' => 'Kelola Media',           'slug' => 'manage-media',           'description' => 'Mengelola album & galeri'],
            ['name' => 'Kelola Pengaturan',      'slug' => 'manage-settings',        'description' => 'Mengelola pengaturan website'],
            ['name' => 'Kelola Role & Izin',     'slug' => 'manage-roles',           'description' => 'Mengelola role dan permission'],
        ]);
    }
}
