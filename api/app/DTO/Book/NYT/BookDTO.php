<?php

declare(strict_types=1);

namespace App\DTO\Book\NYT;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

#[OA\Schema(
    schema: 'BookDTO',
    type: 'object',
    properties: [
        new OA\Property(property: 'title', type: 'string', example: 'Book Title'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'A brief description of the book'),
        new OA\Property(property: 'contributor', type: 'string', nullable: true, example: 'John Doe'),
        new OA\Property(property: 'author', type: 'string', example: 'Jane Smith'),
        new OA\Property(property: 'contributor_note', type: 'string', nullable: true, example: 'Special thanks to...'),
        new OA\Property(property: 'price', type: 'string', example: '19.99'),
        new OA\Property(property: 'age_group', type: 'string', nullable: true, example: '12-18'),
        new OA\Property(property: 'publisher', type: 'string', nullable: true, example: 'Penguin Books'),
        new OA\Property(
            property: 'isbns',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/IsbnDTO')
        ),
        new OA\Property(
            property: 'ranks_history',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/RankHistoryDTO')
        ),
        new OA\Property(
            property: 'reviews',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/ReviewDTO')
        ),
    ]
)]
class BookDTO extends Data
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly ?string $contributor,
        public readonly string $author,
        #[MapInputName('contributor_note'), MapOutputName('contributor_note')]
        public readonly ?string $contributorNote,
        public readonly string $price,
        #[MapInputName('age_group'), MapOutputName('age_group')]
        public readonly ?string $ageGroup,
        public readonly ?string $publisher,
        /** @var IsbnDTO[] */
        public readonly array $isbns,
        #[MapInputName('ranks_history'), MapOutputName('ranks_history')]
        /** @var RankHistoryDTO[] */
        public readonly array $ranksHistory,
        /** @var ReviewDTO[] */
        public readonly array $reviews
    ) {}
}
