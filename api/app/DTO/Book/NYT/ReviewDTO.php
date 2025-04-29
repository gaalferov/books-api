<?php

declare(strict_types=1);

namespace App\DTO\Book\NYT;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

#[OA\Schema(
    schema: 'ReviewDTO',
    type: 'object',
    properties: [
        new OA\Property(property: 'book_review_link', type: 'string', nullable: true, example: 'https://example.com/review'),
        new OA\Property(property: 'first_chapter_link', type: 'string', nullable: true, example: 'https://example.com/first-chapter'),
        new OA\Property(property: 'sunday_review_link', type: 'string', nullable: true, example: 'https://example.com/sunday-review'),
        new OA\Property(property: 'article_chapter_link', type: 'string', nullable: true, example: 'https://example.com/article-chapter'),
    ]
)]
class ReviewDTO extends Data
{
    public function __construct(
        #[MapInputName('book_review_link'), MapOutputName('book_review_link')]
        public readonly ?string $bookReviewLink,
        #[MapInputName('first_chapter_link'), MapOutputName('first_chapter_link')]
        public readonly ?string $firstChapterLink,
        #[MapInputName('sunday_review_link'), MapOutputName('sunday_review_link')]
        public readonly ?string $sundayReviewLink,
        #[MapInputName('article_chapter_link'), MapOutputName('article_chapter_link')]
        public readonly ?string $articleChapterLink
    ) {}
}
