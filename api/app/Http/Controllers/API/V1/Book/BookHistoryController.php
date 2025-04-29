<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Book;

use App\Http\Requests\Book\BookHistoryRequest;
use App\Services\Book\BookFetcherService;
use App\Utils\JsonResponseUtil;
use Illuminate\Http\JsonResponse;

class BookHistoryController extends BookController
{
    public function __construct(
        private readonly BookFetcherService $bookFetcherService
    ) {}

    /**
     * @see \App\OpenApi\Controllers\API\V1\Book\BookHistoryControllerDocs::get()
     */
    public function __invoke(BookHistoryRequest $historyRequest): JsonResponse
    {
        return JsonResponseUtil::successResponse(
            $this->bookFetcherService->fetchBooks($historyRequest)->getBooksAsArray()
        );
    }
}
