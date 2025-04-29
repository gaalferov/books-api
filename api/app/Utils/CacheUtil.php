<?php

declare(strict_types=1);

namespace App\Utils;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CacheUtil
 */
class CacheUtil
{
    public static function generateCacheKey(FormRequest $formRequest): string
    {
        return hash(
            'sha256',
            sprintf('%s:%s', get_class($formRequest), $formRequest->getQueryString())
        );
    }
}
