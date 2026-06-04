<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'site_name' => 'CSA',
            'email' => 'admin@csa.local',
            'phone' => '08123456789',
            'address' => 'Indonesia',
            'instagram' => 'https://instagram.com/csa',
            'youtube' => 'https://youtube.com/@csa',
            'linkedin' => null,
            'about' => 'Computer Student Association',
        ]);
    }
}
