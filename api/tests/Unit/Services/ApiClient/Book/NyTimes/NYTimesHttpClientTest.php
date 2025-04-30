<?php

declare(strict_types=1);

namespace Tests\Unit\Services\ApiClient\Book\NyTimes;

use App\Services\ApiClient\Book\NyTimes\NYTimesHttpClient;
use App\Utils\CacheUtil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NYTimesHttpClientTest extends TestCase
{
    #[Test]
    #[DataProvider('fetchBooksDataProvider')]
    public function fetch_books_handles_api_calls_correctly(
        bool $cacheEnabled,
        array $queryParams,
        ?string $cacheKey,
        array $apiResponse,
        array $expectedResult
    ): void {
        // Arrange
        $httpClientMock = Mockery::mock(PendingRequest::class, function (MockInterface $mock) use ($apiResponse) {
            $mock->shouldReceive('get')
                ->once()
                ->with(NYTimesHttpClient::HISTORY_ENDPOINT, Mockery::type('array'))
                ->andReturnSelf();
            $mock->shouldReceive('throw')->once()->andReturnSelf();
            $mock->shouldReceive('json')->once()->andReturn($apiResponse);
        });

        $formRequestMock = $this->mockFormRequest($queryParams, $cacheEnabled);

        if ($cacheEnabled) {
            Cache::shouldReceive('remember')
                ->once()
                ->with($cacheKey, 3600, Mockery::on(function ($callback) use ($apiResponse) {
                    $this->assertEquals($apiResponse, $callback());

                    return true;
                }))
                ->andReturn($apiResponse);
        }

        $client = new NYTimesHttpClient($cacheEnabled, 3600, $httpClientMock);

        // Act
        $result = $client->fetchBooks($formRequestMock);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    public static function fetchBooksDataProvider(): array
    {
        return [
            'cache enabled with valid response' => [
                'cacheEnabled' => true,
                'queryParams' => ['author' => 'John Doe'],
                'cacheKey' => CacheUtil::generateCacheKey(
                    NYTimesHttpClient::HISTORY_ENDPOINT,
                    'author=John+Doe'
                ),
                'apiResponse' => ['data' => 'some book data'],
                'expectedResult' => ['data' => 'some book data'],
            ],
            'cache disabled with valid response' => [
                'cacheEnabled' => false,
                'queryParams' => ['author' => 'Jane Doe'],
                'cacheKey' => null,
                'apiResponse' => ['data' => 'other book data'],
                'expectedResult' => ['data' => 'other book data'],
            ],
        ];
    }

    private function mockFormRequest(array $queryParams, bool $cacheEnabled): MockInterface
    {
        return $this->mock(FormRequest::class, function (MockInterface $mock) use ($queryParams, $cacheEnabled) {
            $mock->shouldReceive('validated')->once()->andReturn($queryParams);

            if ($cacheEnabled) {
                $mock->shouldReceive('getQueryString')->once()->andReturn(http_build_query($queryParams));
            }
        });
    }
}
