<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
            ],
            [
                'name' => 'Ketua',
                'slug' => 'ketua',
            ],
            [
                'name' => 'Wakil Ketua',
                'slug' => 'wakil-ketua',
            ],
            [
                'name' => 'Ketua Divisi',
                'slug' => 'ketua-divisi',
            ],
            [
                'name' => 'Wakil Ketua Divisi',
                'slug' => 'wakil-ketua-divisi',
            ],
            [
                'name' => 'Sekretaris',
                'slug' => 'sekretaris',
            ],
            [
                'name' => 'Bendahara',
                'slug' => 'bendahara',
            ],
            [
                'name' => 'Anggota',
                'slug' => 'anggota',
            ],
        ]);
    }
}
