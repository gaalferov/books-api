<?php

declare(strict_types=1);

namespace App\Services\ApiClient\Book\NyTimes;

use App\Utils\CacheUtil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;

/**
 * Class NYTimesHttpClient
 */
class NYTimesHttpClient
{
    private const HISTORY_ENDPOINT = '/svc/books/v3/lists/best-sellers/history.json';

    public function __construct(
        private readonly bool $cacheEnabled,
        private readonly int $cacheTtl,
        private readonly PendingRequest $httpClient
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchBooks(FormRequest $formRequest): array
    {
        $queryParams = $formRequest->validated();
        if ($this->cacheEnabled) {
            $cacheKey = CacheUtil::generateCacheKey($formRequest);
            return Cache::remember($cacheKey, $this->cacheTtl, function () use ($queryParams) {
                return $this->makeGetRequest(self::HISTORY_ENDPOINT, $queryParams);
            });
        }

        return $this->makeGetRequest(self::HISTORY_ENDPOINT, $queryParams);
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    private function makeGetRequest(string $endpoint, array $queryParams = []): array
    {
        $response = $this->httpClient->get($endpoint, $queryParams);
        $response->throw();

        return $response->json();
    }
}
