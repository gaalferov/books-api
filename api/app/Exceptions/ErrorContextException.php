<?php

declare(strict_types=1);

namespace App\Exceptions;

interface ErrorContextException
{
    public function errors(): array;
}
