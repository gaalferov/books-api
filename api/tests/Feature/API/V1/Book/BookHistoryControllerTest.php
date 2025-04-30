<?php

namespace Feature\API\V1\Book;

use App\Services\ApiClient\Book\NyTimes\NYTimesHttpClient;
use App\Utils\CacheUtil;
use Generator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Class BookHistoryControllerTest
 */
class BookHistoryControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Cache::flush();
        parent::tearDown();
    }

    #[Test]
    #[DataProvider('bookHistoryCacheDataProvider')]
    public function book_history_check_valid_cache(array $fakeApiResponse): void
    {
        // Arrange: Mock the configuration to enable caching
        config(['services.books.providers.nytimes.cache.enabled' => true]);

        // Fake the HTTP client to avoid real API calls
        Http::fake(
            [
                config('services.books.providers.nytimes.base_url').'*' => Http::response(
                    $fakeApiResponse
                ),
            ]
        );

        $this->getJson('/api/v1/books/history')
            ->assertStatus(200);

        $cachedData = Cache::get(CacheUtil::generateCacheKey(NYTimesHttpClient::HISTORY_ENDPOINT, null));
        $this->assertNotNull($cachedData);
        $this->assertArrayHasKey('status', $cachedData);
        $this->assertEquals('OK', $cachedData['status']);
        $this->assertArrayHasKey('num_results', $cachedData);
        $this->assertArrayHasKey('results', $cachedData);
    }

    #[Test]
    #[DataProvider('bookHistoryResponseDataProvider')]
    public function book_history_check_valid_response_with_cache(array $fakeApiResponse, array $appResponse): void
    {
        // Arrange: Mock the configuration to enable caching
        config(['services.books.providers.nytimes.cache.enabled' => true]);

        // Fake the HTTP client to avoid real API calls
        Http::fake(
            [
                config('services.books.providers.nytimes.base_url').'*' => Http::response(
                    $fakeApiResponse
                ),
            ]
        );

        // Assert that the response is valid
        $response = $this->getJson('/api/v1/books/history');
        $response->assertStatus(200);
        $response->assertJson($appResponse);

        // Assert that the cache is not empty
        $cachedData = Cache::get(CacheUtil::generateCacheKey(NYTimesHttpClient::HISTORY_ENDPOINT, null));
        $this->assertNotNull($cachedData);
        $this->assertArrayHasKey('status', $cachedData);
        $this->assertEquals('OK', $cachedData['status']);

    }

    #[Test]
    #[DataProvider('bookHistoryResponseDataProvider')]
    public function book_history_check_valid_response_with_out_cache(array $fakeApiResponse, array $appResponse): void
    {
        // Arrange: Mock the configuration to enable caching
        config(['services.books.providers.nytimes.cache.enabled' => false]);

        // Fake the HTTP client to avoid real API calls
        Http::fake(
            [
                config('services.books.providers.nytimes.base_url').'*' => Http::response(
                    $fakeApiResponse
                ),
            ]
        );

        // Assert that the response is valid
        $response = $this->getJson('/api/v1/books/history');
        $response->assertStatus(200);
        $response->assertJson($appResponse);

        // Assert that the cache is empty
        $cachedData = Cache::get(CacheUtil::generateCacheKey(NYTimesHttpClient::HISTORY_ENDPOINT, null));
        $this->assertNull($cachedData);
    }

    #[Test]
    public function book_history_empty_api_response(): void
    {
        // Arrange: Mock the configuration to enable caching
        config(['services.books.providers.nytimes.cache.enabled' => true]);

        // Fake the HTTP client to return an empty response
        Http::fake(
            [
                config('services.books.providers.nytimes.base_url').'*' => Http::response(
                    [
                        'status' => 'OK',
                        'copyright' => 'Copyright (c) ***.  All Rights Reserved.',
                        'num_results' => 0,
                        'results' => [],
                    ]
                ),
            ]
        );

        // Act: Make the GET request
        $response = $this->getJson('/api/v1/books/history');

        // Assert: Validate the response
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'OK',
            'data' => [],
        ]);

        // Assert: Ensure no data is cached
        $cachedData = Cache::get(CacheUtil::generateCacheKey(NYTimesHttpClient::HISTORY_ENDPOINT, null));
        $this->assertNotNull($cachedData);
        $this->assertEquals('OK', $cachedData['status']);
        $this->assertEquals(0, $cachedData['num_results']);
        $this->assertEmpty($cachedData['results']);
    }

    #[Test]
    #[DataProvider('bookHistoryValidationErrorDataProvider')]
    public function book_history_validation_error(array $queryParams, array $expectedErrorMessages): void
    {
        // Act: Make the GET request with invalid query parameters
        $response = $this->getJson('/api/v1/books/history?'.http_build_query($queryParams));

        // Assert: Validate the response
        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrorMessages);
    }

    #[Test]
    #[DataProvider('bookHistoryInternalServerErrorDataProvider')]
    public function book_history_internal_server_error(array $httpException, array $expectedResponse): void
    {
        // Arrange: Mock the HTTP client to throw an exception
        Http::fake(fn () => throw new \Exception($httpException['message'], $httpException['code']));

        // Act: Make the GET request
        $response = $this->getJson('/api/v1/books/history');

        // Assert: Validate the response
        $response->assertStatus(500);
        $response->assertJson($expectedResponse);
    }

    // Data providers

    public static function bookHistoryCacheDataProvider(): array
    {
        return [
            'single result' => [
                [
                    'status' => 'OK',
                    'copyright' => 'Copyright (c) ***.  All Rights Reserved.',
                    'num_results' => 1,
                    'results' => [
                        [
                            'title' => '"I GIVE YOU MY BODY ..."',
                            'author' => 'Diana Gabaldon',
                            'price' => '0.00',
                            'isbns' => [],
                            'ranks_history' => [],
                            'reviews' => [],
                        ],
                    ],
                ],
            ],
            'multiple results' => [
                [
                    'status' => 'OK',
                    'copyright' => 'Copyright (c) ***.  All Rights Reserved.',
                    'num_results' => 2,
                    'results' => [
                        [
                            'title' => '"I GIVE YOU MY BODY ..."',
                            'author' => 'Diana Gabaldon',
                            'price' => '0.00',
                            'isbns' => [],
                            'ranks_history' => [],
                            'reviews' => [],
                        ],
                        [
                            'title' => '"MOST BLESSED OF THE PATRIARCHS"',
                            'author' => 'Annette Gordon-Reed and Peter S Onuf',
                            'price' => '0.00',
                            'isbns' => [],
                            'ranks_history' => [],
                            'reviews' => [],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function bookHistoryResponseDataProvider(): Generator
    {
        yield [
            [
                'status' => 'OK',
                'copyright' => 'Copyright (c) ***.  All Rights Reserved.',
                'num_results' => 1,
                'results' => [
                    [
                        'title' => '"I GIVE YOU MY BODY ..."',
                        'author' => 'Diana Gabaldon',
                        'price' => '0.00',
                        'isbns' => [],
                        'ranks_history' => [],
                        'reviews' => [],
                    ],
                ],
            ],
            [
                'status' => 'OK',
                'data' => [
                    [
                        'title' => '"I GIVE YOU MY BODY ..."',
                        'description' => null,
                        'contributor' => null,
                        'author' => 'Diana Gabaldon',
                        'contributor_note' => null,
                        'price' => '0.00',
                        'age_group' => null,
                        'publisher' => null,
                        'isbns' => [],
                        'ranks_history' => [],
                        'reviews' => [],
                    ],
                ],
            ],
        ];

        yield [
            [
                'status' => 'OK',
                'copyright' => 'Copyright (c) ***.  All Rights Reserved.',
                'num_results' => 1,
                'results' => [
                    [
                        'title' => '"I GIVE YOU MY BODY ..."',
                        'author' => 'Diana Gabaldon',
                        'price' => '0.00',
                        'isbns' => [
                            [
                                'isbn10' => '1234567890',
                                'isbn13' => '1234567890123',
                            ],
                        ],
                        'ranks_history' => [],
                        'reviews' => [],
                    ],
                ],
            ],
            [
                'status' => 'OK',
                'data' => [
                    [
                        'title' => '"I GIVE YOU MY BODY ..."',
                        'description' => null,
                        'contributor' => null,
                        'author' => 'Diana Gabaldon',
                        'contributor_note' => null,
                        'price' => '0.00',
                        'age_group' => null,
                        'publisher' => null,
                        'isbns' => [
                            [
                                'isbn10' => '1234567890',
                                'isbn13' => '1234567890123',
                            ],
                        ],
                        'ranks_history' => [],
                        'reviews' => [],
                    ],
                ],
            ],
        ];
    }

    public static function bookHistoryValidationErrorDataProvider(): Generator
    {
        yield 'invalid offset value' => [
            'queryParams' => ['offset' => -10], // Offset must be a positive integer
            'expectedErrorMessages' => [
                'offset' => [
                    'The offset field must be at least 0.',
                    'The offset field must be a multiple of 20.',
                ],
            ],
        ];

        yield 'invalid isbn format' => [
            'queryParams' => ['isbn' => 'invalid_isbn'], // ISBN must match the regex
            'expectedErrorMessages' => [
                'isbn' => [
                    'The isbn field format is invalid.',
                ],
            ],
        ];

        yield 'invalid price format' => [
            'queryParams' => ['price' => 'invalid_price'], // Price must match the regex for a valid decimal
            'expectedErrorMessages' => [
                'price' => [
                    'The price field format is invalid.',
                ],
            ],
        ];
    }

    public static function bookHistoryInternalServerErrorDataProvider(): Generator
    {
        yield 'unexpected server error' => [
            'httpException' => [
                'message' => 'Unexpected error occurred.',
                'code' => 500,
            ],
            'expectedResponse' => [
                'status' => 'ERROR',
                'errors' => [
                    'message' => 'Unexpected error occurred.',
                    'code' => 500,
                ],
            ],
        ];

        yield 'redis connection error' => [
            'httpException' => [
                'message' => 'Redis connection failed.',
                'code' => 500,
            ],
            'expectedResponse' => [
                'status' => 'ERROR',
                'errors' => [
                    'message' => 'Redis connection failed.',
                    'code' => 500,
                ],
            ],
        ];
    }
}
