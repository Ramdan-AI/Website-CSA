<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();

        if ($roles->isEmpty()) {
            $this->command->warn('Pastikan RoleSeeder sudah dijalankan terlebih dahulu.');
            return;
        }

        $roleKetua = $roles->firstWhere('slug', 'ketua')?->id;
        $roleSekretaris = $roles->firstWhere('slug', 'sekretaris')?->id;
        $roleBendahara = $roles->firstWhere('slug', 'bendahara')?->id;
        $roleKetuaDivisi = $roles->firstWhere('slug', 'ketua-divisi')?->id;
        $roleAnggota = $roles->firstWhere('slug', 'anggota')?->id;

        $defaultPassword = Hash::make('password');

        User::create([
            'role_id'   => $roleKetua,
            'name'      => 'Ketua CSA',
            'email'     => 'ketua@csa.local',
            'password'  => $defaultPassword,
            'is_active' => true,
        ]);

        User::create([
            'role_id'   => $roleSekretaris,
            'name'      => 'Sekretaris CSA',
            'email'     => 'sekretaris@csa.local',
            'password'  => $defaultPassword,
            'is_active' => true,
        ]);

        User::create([
            'role_id'   => $roleBendahara,
            'name'      => 'Bendahara CSA',
            'email'     => 'bendahara@csa.local',
            'password'  => $defaultPassword,
            'is_active' => true,
        ]);

        User::create([
            'role_id'   => $roleKetuaDivisi,
            'name'      => 'Ketua Divisi CSA',
            'email'     => 'ketuadivisi@csa.local',
            'password'  => $defaultPassword,
            'is_active' => true,
        ]);

        User::create([
            'role_id'   => $roleAnggota,
            'name'      => 'Anggota CSA',
            'email'     => 'anggota@csa.local',
            'password'  => $defaultPassword,
            'is_active' => true,
        ]);
    }
}
