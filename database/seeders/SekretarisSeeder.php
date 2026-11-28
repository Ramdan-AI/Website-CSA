<?php

namespace Database\Seeders;

class SekretarisSeeder extends MemberAccountSeeder
{
    protected function roleSlug(): string
    {
        return 'sekretaris';
    }

    protected function people(): array
    {
        return [
            ['24110007', 'Helmi Fauzan Ramdani', 'Sekretaris', self::PEMROGRAMAN],
            ['251110215', 'Giestiani Putri', 'Sekretaris', self::PEMROGRAMAN],
        ];
    }
}
