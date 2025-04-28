<?php

declare(strict_types=1);

namespace App\Domain\Enums;

enum Status: string
{
    case SUCCESS = 'OK';
    case ERROR = 'ERROR';
}
