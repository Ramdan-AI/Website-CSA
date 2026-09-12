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
            'instagram' => 'https://www.instagram.com/csa_stmikmi/',
            'youtube' => 'https://www.youtube.com/@csaworks143',
            'linkedin' => 'https://www.linkedin.com/company/creative-student-association/',
            'about' => 'Computer Student Association',
            'registration_open' => false,
        ]);
    }
}
