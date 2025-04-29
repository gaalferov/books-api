<?php

declare(strict_types=1);

namespace App\OpenApi\Controllers\API\V1\Book;

use App\Http\Controllers\API\V1\Book\BookHistoryController;
use OpenApi\Attributes as OA;
use App\Enums\Status;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(
    name: 'Books History',
    description: 'Endpoints related to book history'
)]
class BookHistoryControllerDocs extends BookControllerDocs
{
    #[OA\Get(
        path: '/api/v1/books/history',
        summary: 'Get books history',
        tags: ['Books History'],
        parameters: [
            new OA\Parameter(
                name: 'age-group',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', maxLength: 255, nullable: true)
            ),
            new OA\Parameter(
                name: 'author',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', maxLength: 255, nullable: true)
            ),
            new OA\Parameter(
                name: 'contributor',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', maxLength: 255, nullable: true)
            ),
            new OA\Parameter(
                name: 'isbn',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', pattern: '^(\d{10}|\d{13})$', nullable: true)
            ),
            new OA\Parameter(
                name: 'offset',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', minimum: 0, nullable: true)
            ),
            new OA\Parameter(
                name: 'price',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', pattern: '^\d+(\.\d{1,2})?$', nullable: true)
            ),
            new OA\Parameter(
                name: 'publisher',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', maxLength: 255, nullable: true)
            ),
            new OA\Parameter(
                name: 'title',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', maxLength: 255, nullable: true)
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Successful response',
                content: new OA\JsonContent(
                    type: 'object',
                              properties: [
                                  new OA\Property(
                                      property: 'status',
                                      type: 'string',
                                      example: Status::SUCCESS->value
                                  ),
                                  new OA\Property(
                                      property: 'data',
                                      type: 'array',
                                      items: new OA\Items(ref: '#/components/schemas/BookDTO')
                                  )
                          ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_UNPROCESSABLE_ENTITY,
                description: 'Validation error',
                content: new OA\JsonContent(
                type: 'object',
                properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            example: Status::ERROR->value
                        ),
                        new OA\Property(
                          property: 'errors',
                          type: 'object',
                          additionalProperties: new OA\AdditionalProperties(
                            type: 'array',
                            items: new OA\Items(type: 'string', example: 'The title field is required.')
                          )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_INTERNAL_SERVER_ERROR,
                description: 'Internal server error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                            new OA\Property(
                                property: 'status',
                                type: 'string',
                                example: Status::ERROR->value
                            ),
                            new OA\Property(
                                property: 'errors',
                                type: 'object',
                                properties: [
                                    new OA\Property(
                                      property: 'message',
                                      type: 'string',
                                      example: 'An unexpected error occurred. Please try again later.'
                                    ),
                                    new OA\Property(
                                      property: 'code',
                                      type: 'integer',
                                      example: 500
                                    )
                                ]
                            )
                        ]
                    )
            )
        ]
    )]
    public static function get(): void {}
}
