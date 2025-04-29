<?php

declare(strict_types=1);

namespace App\Services\Book;

use App\DTO\Book\BookListData;
use Illuminate\Foundation\Http\FormRequest;

interface BookProvider
{
    public function fetchBooks(FormRequest $formRequest): BookListData;
}
