<?php

declare(strict_types=1);

namespace App\DTO\Book\NYT;

use App\DTO\Book\BookListData;
use Spatie\LaravelData\Data;

class BookListDTO extends Data implements BookListData
{
    public function __construct(
        public readonly int $num_results,
        /** @var BookDTO[] */
        public readonly array $results
    ) {}

    public function getBooksAsArray(): array
    {
        return array_map(fn($book) => $book->toArray(), $this->results);
    }
}
