<?php

declare(strict_types=1);

namespace App\DTO\Book\NYT;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

#[OA\Schema(
    schema: 'RankHistoryDTO',
    type: 'object',
    properties: [
        new OA\Property(property: 'primary_isbn10', type: 'string', example: '1234567890'),
        new OA\Property(property: 'primary_isbn13', type: 'string', example: '123-4567890123'),
        new OA\Property(property: 'rank', type: 'integer', example: 1),
        new OA\Property(property: 'list_name', type: 'string', example: 'Best Sellers'),
        new OA\Property(property: 'display_name', type: 'string', example: 'Fiction'),
        new OA\Property(property: 'published_date', type: 'string', format: 'date', example: '2023-01-01'),
        new OA\Property(property: 'bestsellers_date', type: 'string', format: 'date', example: '2023-01-01'),
        new OA\Property(property: 'weeks_on_list', type: 'integer', example: 10),
        new OA\Property(property: 'ranks_last_week', type: 'integer', nullable: true, example: 2),
        new OA\Property(property: 'asterisk', type: 'integer', example: 0),
        new OA\Property(property: 'dagger', type: 'integer', example: 1),
    ]
)]
class RankHistoryDTO extends Data
{
    public function __construct(
        #[MapInputName('primary_isbn10'), MapOutputName('primary_isbn10')]
        public readonly string $primaryIsbn10,
        #[MapInputName('primary_isbn13'), MapOutputName('primary_isbn13')]
        public readonly string $primaryIsbn13,
        public readonly int $rank,
        #[MapInputName('list_name'), MapOutputName('list_name')]
        public readonly string $listName,
        #[MapInputName('display_name'), MapOutputName('display_name')]
        public readonly string $displayName,
        #[MapInputName('published_date'), MapOutputName('published_date')]
        public readonly string $publishedDate,
        #[MapInputName('bestsellers_date'), MapOutputName('bestsellers_date')]
        public readonly string $bestsellersDate,
        #[MapInputName('weeks_on_list'), MapOutputName('weeks_on_list')]
        public readonly int $weeksOnList,
        #[MapInputName('ranks_last_week'), MapOutputName('ranks_last_week')]
        public readonly ?int $ranksLastWeek,
        public readonly int $asterisk,
        public readonly int $dagger
    ) {}
}
