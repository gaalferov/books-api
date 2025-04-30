<?php

declare(strict_types=1);

namespace Tests\Unit\Utils;

use App\Utils\CacheUtil;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CacheUtilTest extends TestCase
{
    #[Test]
    #[DataProvider('generateCacheKeyDataProvider')]
    public function generate_cache_key(string $prefix, ?string $queryString, string $expectedHash): void
    {
        // Act
        $cacheKey = CacheUtil::generateCacheKey($prefix, $queryString);

        // Assert
        $this->assertNotEmpty($cacheKey);
        $this->assertEquals($expectedHash, $cacheKey);
    }

    public static function generateCacheKeyDataProvider(): array
    {
        return [
            'with query string' => [
                'prefix' => 'test_prefix',
                'queryString' => 'key=value',
                'expectedHash' => hash('sha256', 'test_prefix:key=value'),
            ],
            'empty query string' => [
                'prefix' => 'test_prefix',
                'queryString' => null,
                'expectedHash' => hash('sha256', 'test_prefix:empty'),
            ],
        ];
    }
}
