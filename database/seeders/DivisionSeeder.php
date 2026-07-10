<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'Divisi Pemrograman',
                'slug' => Str::slug('Divisi Pemrograman'),
                'description' => 'Fokus pada pengembangan aplikasi berbasis web dan mobile dengan teknologi terkini. Anggota akan belajar tentang front-end, back-end, dan fullstack development.',
            ],
            [
                'name' => 'Divisi Multimedia',
                'slug' => Str::slug('Divisi Multimedia'),
                'description' => 'Mengembangkan keterampilan desain grafis, editing video, animasi, dan konten visual. Anggota akan belajar membuat konten kreatif untuk berbagai platform.',
            ],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
