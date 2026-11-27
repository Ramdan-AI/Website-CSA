<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    private const POSITIONS = [
        ['Ketua', 'Pimpinan tertinggi organisasi', 1],
        ['Wakil Ketua', 'Mendampingi ketua', 1],
        ['Ketua Divisi', 'Pemimpin divisi', 5],
        ['Wakil Ketua Divisi', 'Mendampingi ketua divisi', 5],
        ['Sekretaris', 'Mengurus administrasi organisasi', 2],
        ['Bendahara', 'Mengurus keuangan organisasi', 2],
        ['Humas', 'Mengelola hubungan masyarakat', 2],
        ['Litbang', 'Penelitian dan pengembangan organisasi', 3],
        ['Koordinator Proyek', 'Mengoordinasikan proyek divisi', 5],
        ['Wakil Koordinator Proyek', 'Mendampingi koordinator proyek', 5],
        ['Koordinator Tim Kreatif', 'Mengoordinasikan tim kreatif', 2],
        ['Tim Kreatif', 'Anggota tim kreatif', 20],
        ['Anggota', 'Anggota biasa', 150],
    ];

    public function run(): void
    {
        Position::insert(array_map(
            fn (array $position) => array_combine(['name', 'description', 'max_members'], $position),
            self::POSITIONS,
        ));
    }
}
