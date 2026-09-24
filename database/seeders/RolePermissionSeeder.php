<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Matriks hak akses per role, mengikuti prinsip least privilege:
     * setiap role hanya mendapat yang benar-benar dibutuhkan perannya.
     *
     * super-admin tidak didaftarkan di sini karena Role::hasPermission()
     * otomatis mengembalikan true untuk slug super-admin.
     */
    public function run(): void
    {
        $permissions = Permission::all()->keyBy('slug');

        $rolePermissions = [
            // Pimpinan tertinggi: struktur organisasi, pengaturan, dan moderasi.
            'ketua' => [
                'manage-divisions',
                'manage-positions',
                'manage-members',
                'manage-attendances',
                'view-attendances',
                'manage-documents',
                'manage-events',
                'view-events',
                'manage-finance',
                'view-finance',
                'view-finance-report',
                'manage-media',
                'manage-projects',
                'manage-settings',
                'manage-announcements',
            ],

            // Mendampingi ketua tanpa wewenang mengubah struktur & pengaturan.
            'wakil-ketua' => [
                'manage-members',
                'manage-attendances',
                'view-attendances',
                'manage-documents',
                'manage-events',
                'view-events',
                'view-finance',
                'view-finance-report',
                'manage-media',
                'manage-projects',
                'manage-announcements',
            ],

            // Administrasi: berita, dokumen, surat, notulen, pencatatan kehadiran.
            'sekretaris' => [
                'manage-news',
                'manage-documents',
                'manage-letters',
                'manage-minutes',
                'manage-attendances',
                'view-attendances',
                'view-events',
                'manage-media',
                'manage-announcements',
            ],

            // Keuangan: pemasukan, pengeluaran, laporan. Tanpa akses media.
            'bendahara' => [
                'manage-finance',
                'view-finance',
                'view-finance-report',
                'view-attendances',
                'view-events',
                'manage-announcements',
            ],

            // Ketua divisi: mengelola divisinya sendiri, bukan lintas divisi.
            'ketua-divisi' => [
                'manage-division-members',
                'manage-attendances',
                'view-attendances',
                'view-division-reports',
                'manage-events',
                'view-events',
                'manage-media',
                'manage-announcements',
            ],

            // Wakil ketua divisi: mendampingi tanpa wewenang pendaftaran.
            'wakil-ketua-divisi' => [
                'manage-division-members',
                'manage-attendances',
                'view-attendances',
                'view-division-reports',
                'manage-events',
                'view-events',
                'manage-announcements',
            ],

            // Anggota: hanya melihat kegiatan dan presensi pribadi.
            // Detail transaksi keuangan tidak dibuka agar privasi pencatat terjaga.
            'anggota' => [
                'view-events',
                'view-attendances',
            ],
        ];

        foreach ($rolePermissions as $roleSlug => $permSlugs) {
            $role = Role::where('slug', $roleSlug)->first();
            if (! $role) {
                continue;
            }

            $permIds = collect($permSlugs)
                ->map(fn ($slug) => $permissions[$slug]->id ?? null)
                ->filter()
                ->toArray();

            $role->permissions()->sync($permIds);
        }
    }
}
