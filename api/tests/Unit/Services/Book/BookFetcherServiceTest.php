<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Book;

use App\DTO\Book\BookListData;
use App\Services\Book\BookFetcherService;
use App\Services\Book\BookProvider;
use Illuminate\Foundation\Http\FormRequest;
use InvalidArgumentException;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookFetcherServiceTest extends TestCase
{
    #[Test]
    public function fetch_books_handles_providers_correctly(): void
    {
        // Arrange
        $formRequestMock = $this->mock(FormRequest::class);

        $bookListDataMock = Mockery::mock(BookListData::class);

        $providerMock = Mockery::mock(BookProvider::class, function (MockInterface $mock) use ($bookListDataMock) {
            $mock->shouldReceive('fetchBooks')
                ->once()
                ->andReturn($bookListDataMock);
        });

        $providers = ['valid' => $providerMock];
        $service = new BookFetcherService($providers);

        // Act
        $result = $service->fetchBooks($formRequestMock, 'valid');

        // Assert
        $this->assertEquals($bookListDataMock, $result);
    }

    #[Test]
    public function fetch_books_throws_exception_for_invalid_provider(): void
    {
        // Arrange
        $formRequestMock = $this->mock(FormRequest::class);
        $service = new BookFetcherService([]);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Provider 'invalid' is not supported.");

        // Act
        $service->fetchBooks($formRequestMock, 'invalid');
    }

    #[Test]
    public function fetch_books_throws_exception_for_empty_provider_name(): void
    {
        // Arrange
        $formRequestMock = $this->mock(FormRequest::class);
        $service = new BookFetcherService([]);

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Provider '' is not supported.");

        // Act
        $service->fetchBooks($formRequestMock, '');
    }
}
