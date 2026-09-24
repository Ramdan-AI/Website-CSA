<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DivisionSeeder extends Seeder
{
    private const EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    public function run(): void
    {
        $divisionFiles = collect(Storage::disk('public')->files('divisions'))
            ->filter(fn (string $file) => in_array(
                strtolower(pathinfo($file, PATHINFO_EXTENSION)),
                self::EXTENSIONS
            ))
            ->values();

        $galleryFiles = collect(Storage::disk('public')->files('galleries'))
            ->filter(fn (string $file) => in_array(
                strtolower(pathinfo($file, PATHINFO_EXTENSION)),
                self::EXTENSIONS
            ))
            ->sort()
            ->values();

        $photoPool = $divisionFiles->merge($galleryFiles)->values();

        if ($photoPool->isEmpty()) {
            $this->command->warn('Tidak ada gambar yang tersedia untuk divisi. Letakkan gambar di storage lalu jalankan ulang seeder.');

            return;
        }

        $divisions = [
            [
                'name' => 'Divisi Pemrograman',
                'description' => 'Divisi Pemrograman Web fokus pada pengembangan aplikasi berbasis web dengan teknologi terkini. Anggota akan belajar tentang front-end, back-end, dan fullstack development.',
                'image_keyword' => 'pemograman',
                'skills' => ['HTML/CSS', 'PHP', 'JavaScript', 'React', 'Laravel', 'Node.js'],
                'has_levels' => true,
            ],
            [
                'name' => 'Divisi Multimedia',
                'description' => 'Divisi Multimedia mengembangkan keterampilan desain grafis, editing video, animasi, dan konten visual. Anggota akan belajar membuat konten kreatif untuk berbagai platform.',
                'image_keyword' => 'mulmed',
                'skills' => ['Desain Grafis', 'Editing Video', '3D Animasi', 'Fotografi'],
            ],
        ];

        $fallbackIndex = 0;

        foreach ($divisions as $data) {
            $keyword = $data['image_keyword'];
            unset($data['image_keyword']);

            // Slug ditetapkan eksplisit karena WithoutModelEvents pada
            // DatabaseSeeder menonaktifkan hook pembuat slug otomatis.
            $data['slug'] = Str::slug($data['name']);

            // Cari gambar yang namanya relevan dengan divisi (pemograman/mulmed).
            $image = $photoPool->first(fn (string $file) => str_contains($file, $keyword));

            if (! $image && ! $photoPool->isEmpty()) {
                $image = $photoPool[$fallbackIndex++];
            }

            if ($image && (! array_key_exists('image', $data) || ! $data['image'])) {
                $data['image'] = $image;
            }

            Division::updateOrCreate(['name' => $data['name']], $data);
        }

        $this->command->info('DivisionSeeder selesai: divisi disinkronkan dari storage.');
    }
}
