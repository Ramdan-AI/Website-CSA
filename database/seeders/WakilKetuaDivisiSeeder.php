<?php

namespace Database\Seeders;

class WakilKetuaDivisiSeeder extends MemberAccountSeeder
{
    protected function roleSlug(): string
    {
        return 'wakil-ketua-divisi';
    }

    protected function people(): array
    {
        return [
            ['251110124', 'Rozan Nouval', 'Wakil Ketua Divisi', self::PEMROGRAMAN],
            ['24110243', 'Fikri Maulana Abdillah', 'Wakil Ketua Divisi', self::MULTIMEDIA],
        ];
    }
}
