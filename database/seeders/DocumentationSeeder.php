<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Gallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DocumentationSeeder extends Seeder
{
    private const ALBUMS = [
        'goes-to-school-sman-1-baleendah' => ['CSA Goes To School SMAN 1 Baleendah', 'Dokumentasi kegiatan CSA Goes To School di SMAN 1 Baleendah.', 1],
        'goes-to-school' => ['CSA Goes To School', 'Dokumentasi kegiatan CSA Goes To School.', 2],
        'digital-literasi' => ['Digital Literasi CSA SMAN 7 Bandung', 'Dokumentasi kegiatan Digital Literasi CSA di SMAN 7 Bandung.', 0],
        'musyawarah-besar' =>['Musyawarah Besar CSA', 'Dokumentasi Musyawarah Besar Creative Student Association.', 2],
        'debat-calon-ketua' => ['Debat Calon Ketua CSA 2026/2027', 'Dokumentasi debat calon ketua CSA periode 2026/2027, 11 April 2026.', 1],
        'study-club' => ['Study Club CSA', 'Dokumentasi kegiatan Study Club CSA.', 1],
        'kegiatan-csa' => ['Dokumentasi CSA', 'Dokumentasi kegiatan Creative Student Association.', 1],
    ];

    public function run(): void
    {
        foreach (self::ALBUMS as $folder => [$title, $description, $heroCount]) {
            $sources = File::glob(database_path("seeders/media/dokumentasi/{$folder}/*.webp"));

            if ($sources === []) {
                continue;
            }

            sort($sources);
            $album = Album::firstOrCreate(['title' => $title], ['description' => $description]);

            foreach ($sources as $position => $source) {
                Gallery::firstOrCreate(['image' => $this->publish($folder, $source)], [
                    'album_id' => $album->id,
                    'caption' => $title,
                    'is_hero' => $position < $heroCount,
                ]);
            }
        }
    }

    private function publish(string $folder, string $source): string
    {
        $path = "documentation/{$folder}/".basename($source);

        Storage::disk('public')->put($path, File::get($source));

        return $path;
    }
}
