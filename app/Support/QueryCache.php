<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

class QueryCache
{
    private const VERSION_KEY = 'query-cache.version';

    private const TTL_SECONDS = 600;

    public static function remember(string $key, Closure $resolver): mixed
    {
        return Cache::memo()->remember(self::versionedKey($key), self::TTL_SECONDS, $resolver);
    }

    public static function flush(): void
    {
        Cache::memo()->add(self::VERSION_KEY, 1);
        Cache::memo()->increment(self::VERSION_KEY);
    }

    private static function versionedKey(string $key): string
    {
        return $key.'.v'.Cache::memo()->get(self::VERSION_KEY, 1);
    }
}
