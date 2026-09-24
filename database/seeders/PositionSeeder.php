<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::insert([
            [
                'name' => 'Ketua',
                'description' => 'Pimpinan tertinggi organisasi',
                'max_members' => 1,
            ],
            [
                'name' => 'Wakil Ketua',
                'description' => 'Mendampingi ketua',
                'max_members' => 1,
            ],
            [
                'name' => 'Ketua Divisi',
                'description' => 'Pemimpin divisi',
                'max_members' => 5,
            ],
            [
                'name' => 'Wakil Ketua Divisi',
                'description' => 'Mendampingi ketua divisi',
                'max_members' => 5,
            ],
            [
                'name' => 'Sekretaris',
                'description' => 'Mengurus administrasi organisasi',
                'max_members' => 2,
            ],
            [
                'name' => 'Bendahara',
                'description' => 'Mengurus keuangan organisasi',
                'max_members' => 2,
            ],
            [
                'name' => 'Anggota',
                'description' => 'Anggota biasa',
                'max_members' => 150,
            ],
        ]);
    }
}
