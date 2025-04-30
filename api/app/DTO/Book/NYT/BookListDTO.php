<?php

declare(strict_types=1);

namespace App\DTO\Book\NYT;

use App\DTO\Book\BookListData;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

class BookListDTO extends Data implements BookListData
{
    public function __construct(
        #[MapInputName('num_results'), MapOutputName('num_results')]
        public readonly int $numResults,
        /** @var BookDTO[] */
        public readonly array $results
    ) {}

    public function getBooksAsArray(): array
    {
        return array_map(fn ($book) => $book->toArray(), $this->results);
    }
}
