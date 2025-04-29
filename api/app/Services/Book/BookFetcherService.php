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
     * @param BookProvider[] $providers
     */
    public function __construct(
        private readonly array $providers
    ) {}

    public function fetchBooks(FormRequest $formRequest, string $providerName = null): BookListData
    {
        if ($providerName === null) {
            // If no name is provided, use the first provider in the list
            $providerName = array_key_first($this->providers);
        }

        if (!isset($this->providers[$providerName])) {
            throw new \InvalidArgumentException("Provider '{$providerName}' is not supported.");
        }

        return $this->providers[$providerName]->fetchBooks($formRequest);
    }
}
