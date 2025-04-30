<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Book\NYTimes;

use App\DTO\Book\BookListData;
use App\DTO\Book\NYT\BookDTO;
use App\DTO\Book\NYT\BookListDTO;
use App\Services\ApiClient\Book\NyTimes\NYTimesHttpClient;
use App\Services\Book\NYTimes\NYTimesBookProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\ConnectionException;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NYTimesBookProviderTest extends TestCase
{
    #[Test]
    #[DataProvider('fetchBooksDataProvider')]
    public function fetch_books_handles_responses_correctly(array $httpResponse, BookListData $expectedResult): void
    {
        // Arrange
        $formRequestMock = $this->mock(FormRequest::class);
        $httpClientMock = $this->mockHttpClient($httpResponse);

        $provider = new NYTimesBookProvider($httpClientMock);

        // Act
        $result = $provider->fetchBooks($formRequestMock);

        // Assert
        $this->assertEquals($expectedResult, $result);
    }

    #[Test]
    public function fetch_books_throws_connection_exception(): void
    {
        // Arrange
        $formRequestMock = $this->mock(FormRequest::class);
        $httpClientMock = $this->mockHttpClientWithException(ConnectionException::class);

        $provider = new NYTimesBookProvider($httpClientMock);

        // Assert
        $this->expectException(ConnectionException::class);

        // Act
        $provider->fetchBooks($formRequestMock);
    }

    private function mockHttpClient(array $response): MockInterface
    {
        return Mockery::mock(NYTimesHttpClient::class, function (MockInterface $mock) use ($response) {
            $mock->shouldReceive('fetchBooks')
                ->once()
                ->andReturn($response);
        });
    }

    private function mockHttpClientWithException(string $exceptionClass): MockInterface
    {
        return Mockery::mock(NYTimesHttpClient::class, function (MockInterface $mock) use ($exceptionClass) {
            $mock->shouldReceive('fetchBooks')
                ->once()
                ->andThrow($exceptionClass);
        });
    }

    public static function fetchBooksDataProvider(): array
    {
        return [
            'valid response' => [
                'httpResponse' => [
                    'status' => 'OK',
                    'copyright' => 'Copyright ***  All Rights Reserved.',
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
                'expectedResult' => new BookListDTO(
                    1,
                    [
                        new BookDTO(
                            '"I GIVE YOU MY BODY ..."',
                            null,
                            null,
                            'Diana Gabaldon',
                            null,
                            '0.00',
                            null,
                            null,
                            [],
                            [],
                            []
                        ),
                    ]
                ),
            ],
        ];
    }
}
