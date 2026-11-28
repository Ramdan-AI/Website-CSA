<?php

namespace Database\Seeders;

class WakilKetuaSeeder extends MemberAccountSeeder
{
    protected function roleSlug(): string
    {
        return 'wakil-ketua';
    }

    protected function people(): array
    {
        return [
            ['23110062', 'Cellomita Rahdianti Putri', 'Wakil Ketua', self::MULTIMEDIA],
        ];
    }
}
