<?php

namespace Database\Seeders\Support;

use Illuminate\Support\Facades\Hash;
use RuntimeException;

final class SeedPassword
{
    public static function required(): string
    {
        $password = (string) config('seeding.password');

        if ($password === '') {
            throw new RuntimeException('Isi SEED_PASSWORD di .env sebelum menjalankan seeder.');
        }

        return $password;
    }

    public static function hashed(): string
    {
        return Hash::make(self::required());
    }
}
