<?php

declare(strict_types=1);

namespace App\Utils;

/**
 * Class CacheUtil
 */
class CacheUtil
{
    public static function generateCacheKey(string $prefix, ?string $queryString): string
    {
        if (empty($queryString)) {
            $queryString = 'empty';
        }

        return hash(
            'sha256',
            sprintf('%s:%s', $prefix, $queryString)
        );
    }
}
