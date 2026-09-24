<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NewsCategory::insert([
            [
                'name' => 'Kegiatan',
                'slug' => 'kegiatan',
            ],
            [
                'name' => 'Pengumuman',
                'slug' => 'pengumuman',
            ],
            [
                'name' => 'Prestasi',
                'slug' => 'prestasi',
            ],
        ]);
    }
}
