<?php

declare(strict_types=1);

namespace App\DTO\Book;

interface BookListData
{
    public function getBooksAsArray() : array;
}
