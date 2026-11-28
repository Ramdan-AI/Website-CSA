<?php

namespace Database\Seeders;

use App\Support\QueryCache;
use Database\Seeders\Support\SeedPassword;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        SeedPassword::required();

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
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
            NewsCategorySeeder::class,
            NewsSeeder::class,
            SettingSeeder::class,
            AlbumSeeder::class,
            DocumentationSeeder::class,
            GallerySeeder::class,
        ]);

        QueryCache::flush();
    }
}
