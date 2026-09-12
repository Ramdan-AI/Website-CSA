<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Gallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class GallerySeeder extends Seeder
{
    private const EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    public function run(): void
    {
        $album = Album::first() ?? Album::create([
            'title' => 'Kegiatan CSA',
            'description' => 'Dokumentasi kegiatan dan acara CSA.',
        ]);

        $files = collect(Storage::disk('public')->files('galleries'))
            ->filter(fn (string $file) => in_array(
                strtolower(pathinfo($file, PATHINFO_EXTENSION)),
                self::EXTENSIONS
            ))
            ->sort()
            ->values();

        if ($files->isEmpty()) {
            $this->command->warn('Tidak ada gambar di storage/app/public/galleries. Letakkan gambar di sana lalu jalankan ulang seeder.');
            return;
        }

        $created = 0;

        foreach ($files as $file) {
            $gallery = Gallery::firstOrNew(['image' => $file]);

            $gallery->album_id = $album->id;
            $gallery->caption = $this->caption($file);

            // Hanya tandai hero saat pertama dibuat, jangan timpa pilihan admin.
            if (!$gallery->exists) {
                $gallery->is_hero = true;
            }

            $gallery->save();
            $created++;
        }

        // Hapus record yang gambarnya sudah tidak ada di storage.
        $removed = Gallery::whereNotIn('image', $files)->delete();

        $this->command->info("GallerySeeder selesai: {$created} gambar disinkronkan dari storage, {$removed} record usang dihapus.");
    }

    private function caption(string $file): string
    {
        return match (true) {
            str_contains($file, 'eraport') => 'E-Raport CSA',
            str_contains($file, 'gts') => 'Kegiatan GTS CSA',
            default => 'Foto kegiatan CSA',
        };
    }
}
