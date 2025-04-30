<?php

declare(strict_types=1);

namespace App\Services\Book;

use App\DTO\Book\BookListData;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class BookFetcherService
 */
readonly class BookFetcherService
{
    /**
     * @param  BookProvider[]  $providers
     */
    public function __construct(
        private readonly array $providers
    ) {}

    public function fetchBooks(FormRequest $formRequest, string $providerName): BookListData
    {
        if (! isset($this->providers[$providerName])) {
            throw new \InvalidArgumentException("Provider '{$providerName}' is not supported.");
        }

        return $this->providers[$providerName]->fetchBooks($formRequest);
    }
}
