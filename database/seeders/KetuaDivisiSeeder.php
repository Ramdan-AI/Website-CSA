<?php

namespace Database\Seeders;

class KetuaDivisiSeeder extends MemberAccountSeeder
{
    protected function roleSlug(): string
    {
        return 'ketua-divisi';
    }

    protected function people(): array
    {
        return [
            ['24110241', 'Muhammad Ramdan', 'Ketua Divisi', self::PEMROGRAMAN],
            ['23110153', 'Asep Farhan Pratama', 'Ketua Divisi', self::MULTIMEDIA],
        ];
    }
}
