<?php

declare(strict_types=1);

namespace App\Services\Book\NYTimes;

use App\DTO\Book\BookListData;
use App\DTO\Book\NYT\BookListDTO;
use App\Services\ApiClient\Book\NyTimes\NYTimesHttpClient;
use App\Services\Book\BookProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

/**
 * Class NYTimesBookProvider
 */
readonly class NYTimesBookProvider implements BookProvider
{
    public function __construct(
        private readonly NYTimesHttpClient $httpClient
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchBooks(FormRequest $formRequest): BookListData
    {
        return BookListDTO::from(
            $this->httpClient->fetchBooks($formRequest)
        );
    }
}
