<?php

namespace App\Exceptions;

interface ErrorContextException
{
    public function errors(): array;
}
