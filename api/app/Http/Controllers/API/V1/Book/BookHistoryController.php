<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Book;

use App\Http\Requests\Book\BookHistoryRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;


#[OA\Tag(
    name: 'Books History',
    description: 'Endpoints related to book history'
)]
class BookHistoryController extends BookController
{
    #[OA\Get(
        path: '/api/v1/books/history',
        summary: 'Get books history',
        tags: ['Books History'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(
                              type: 'object',
                              properties: [
                                        new OA\Property(property: 'message', type: 'string', example: 'Book history endpoint'),
                                    ]
                          )
            )
        ]
    )]
    public function __invoke(BookHistoryRequest $historyRequest): JsonResponse
    {
        return response()->json([
            'message' => 'Book history endpoint',
        ]);
    }
}
