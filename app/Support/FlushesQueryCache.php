<?php

namespace App\Support;

trait FlushesQueryCache
{
    protected static function bootFlushesQueryCache(): void
    {
        foreach (['saved', 'deleted', 'restored'] as $event) {
            static::registerModelEvent($event, fn () => QueryCache::flush());
        }
    }
}
