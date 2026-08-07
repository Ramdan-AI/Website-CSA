<?php

namespace Database\Seeders;

use App\Models\Album;
use Illuminate\Database\Seeder;

class AlbumSeeder extends Seeder
{
    public function run(): void
    {
        Album::create([
            'title' => 'Kegiatan CSA',
            'description' => 'Dokumentasi kegiatan dan acara CSA.',
        ]);
    }
}
