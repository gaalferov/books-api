<?php

declare(strict_types=1);

namespace App\DTO\Book\NYT;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Data;

#[OA\Schema(
    schema: 'IsbnDTO',
    type: 'object',
    properties: [
        new OA\Property(property: 'isbn10', type: 'string', example: '1234567890'),
        new OA\Property(property: 'isbn13', type: 'string', example: '1234567890123'),
    ]
)]
class IsbnDTO extends Data
{
    public function __construct(
        public readonly string $isbn10,
        public readonly string $isbn13
    ) {}
}
