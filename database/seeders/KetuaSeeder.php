<?php

namespace Database\Seeders;

class KetuaSeeder extends MemberAccountSeeder
{
    protected function roleSlug(): string
    {
        return 'ketua';
    }

    protected function people(): array
    {
        return [
            ['23110210', 'Nida Mutmainah', 'Ketua', self::PEMROGRAMAN],
        ];
    }
}
