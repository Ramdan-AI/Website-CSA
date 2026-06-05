<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'role_id' => Role::where(
                'slug',
                'ketua'
            )->first()->id,

            'name' => 'Ketua CSA',

            'email' => 'ketua@csa.local',

            'password' => bcrypt(
                'password'
            ),

            'is_active' => true,
        ]);

        User::create([
            'role_id' => Role::where(
                'slug',
                'sekretaris'
            )->first()->id,

            'name' => 'Sekretaris CSA',

            'email' => 'sekretaris@csa.local',

            'password' => bcrypt(
                'password'
            ),

            'is_active' => true,
        ]);

        User::create([
            'role_id' => Role::where(
                'slug',
                'bendahara'
            )->first()->id,

            'name' => 'Bendahara CSA',

            'email' => 'bendahara@csa.local',

            'password' => bcrypt(
                'password'
            ),

            'is_active' => true,
        ]);

        User::create([
            'role_id' => Role::where(
                'slug',
                'kepala-divisi'
            )->first()->id,

            'name' => 'Kepala Divisi CSA',

            'email' => 'kepaladivisi@csa.local',

            'password' => bcrypt(
                'password'
            ),

            'is_active' => true,
        ]);

        User::create([
            'role_id' => Role::where(
                'slug',
                'anggota'
            )->first()->id,

            'name' => 'Anggota CSA',

            'email' => 'anggota@csa.local',

            'password' => bcrypt(
                'password'
            ),

            'is_active' => true,
        ]);
    }
}
