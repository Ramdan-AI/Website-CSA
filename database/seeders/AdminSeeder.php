<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'role_id' => 1,
            'name' => 'Super Admin',
            'email' => 'admin@csa.local',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
    }
}
