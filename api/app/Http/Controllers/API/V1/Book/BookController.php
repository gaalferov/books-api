<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Book;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'API for managing book-related data',
    title: 'Books API'
)]
class BookController extends Controller
{
}
