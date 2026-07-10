<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::insert([
            ['name' => 'Ketua', 'description' => 'Pimpinan tertinggi organisasi'],
            ['name' => 'Wakil Ketua', 'description' => 'Mendampingi ketua'],
            ['name' => 'Ketua Divisi', 'description' => 'Pemimpin divisi'],
            ['name' => 'Wakil Ketua Divisi', 'description' => 'Mendampingi ketua divisi'],
            ['name' => 'Sekretaris 1', 'description' => 'Administrasi utama'],
            ['name' => 'Sekretaris 2', 'description' => 'Administrasi pendukung'],
            ['name' => 'Bendahara 1', 'description' => 'Pengelola keuangan utama'],
            ['name' => 'Bendahara 2', 'description' => 'Pengelola keuangan cadangan'],
            ['name' => 'Anggota', 'description' => 'Anggota biasa'],
        ]);
    }
}
