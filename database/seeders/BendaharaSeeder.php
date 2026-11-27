<?php

namespace Database\Seeders;

class BendaharaSeeder extends MemberAccountSeeder
{
    protected function roleSlug(): string
    {
        return 'bendahara';
    }

    protected function people(): array
    {
        return [
            ['251110218', 'Lenny Juwita Silalahi', 'Bendahara', self::PEMROGRAMAN],
            ['251110170', 'Nazwa Nabila Putri', 'Bendahara', self::PEMROGRAMAN],
        ];
    }
}
