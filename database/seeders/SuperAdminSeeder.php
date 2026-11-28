<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\Support\SeedPassword;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    private const ACCOUNTS = [
        'admin@csa.local' => 'Super Admin',
        'ahfifauka@gmail.com' => 'Ahfi Fauka Pranata',
    ];

    public function run(): void
    {
        $roleId = Role::where('slug', 'super-admin')->firstOrFail()->id;
        $password = SeedPassword::hashed();

        foreach (self::ACCOUNTS as $email => $name) {
            User::firstOrCreate(['email' => $email], [
                'role_id' => $roleId,
                'name' => $name,
                'password' => $password,
                'is_active' => true,
            ]);
        }
    }
}
