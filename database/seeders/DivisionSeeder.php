<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Division::insert([
            ['name' => 'Programming'],
            ['name' => 'Design'],
            ['name' => 'Multimedia'],
            ['name' => 'Public Relation'],
        ]);
    }
}
