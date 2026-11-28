<?php

namespace Tests\Support;

use Database\Seeders\AnggotaSeeder;
use Database\Seeders\BendaharaSeeder;
use Database\Seeders\DivisionSeeder;
use Database\Seeders\KetuaDivisiSeeder;
use Database\Seeders\KetuaSeeder;
use Database\Seeders\PositionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SekretarisSeeder;
use Database\Seeders\SuperAdminSeeder;
use Database\Seeders\WakilKetuaDivisiSeeder;
use Database\Seeders\WakilKetuaSeeder;
use Illuminate\Support\Facades\Storage;

trait SeedsOrganization
{
    private const SEED_PASSWORD = 'seed-password-uji';

    private function seedOrganization(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('divisions/pemograman.jpg', 'image');
        Storage::disk('public')->put('divisions/mulmed.jpg', 'image');
        config(['seeding.password' => self::SEED_PASSWORD]);

        $this->seed([
            RoleSeeder::class,
            DivisionSeeder::class,
            PositionSeeder::class,
            SuperAdminSeeder::class,
            KetuaSeeder::class,
            WakilKetuaSeeder::class,
            SekretarisSeeder::class,
            BendaharaSeeder::class,
            KetuaDivisiSeeder::class,
            WakilKetuaDivisiSeeder::class,
            AnggotaSeeder::class,
        ]);
    }
}
