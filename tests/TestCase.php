<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    private const TEST_DATABASE_PREFIX = 'web_csa_spec';

    protected function refreshApplication(): void
    {
        parent::refreshApplication();

        $this->refuseForeignDatabase();
    }

    private function refuseForeignDatabase(): void
    {
        $connection = config('database.default');
        $database = (string) config("database.connections.{$connection}.database");

        if (! str_starts_with($database, self::TEST_DATABASE_PREFIX)) {
            throw new RuntimeException(
                "Test menolak berjalan di database [{$database}]. Nama database test harus diawali ".self::TEST_DATABASE_PREFIX.'.'
            );
        }
    }
}
