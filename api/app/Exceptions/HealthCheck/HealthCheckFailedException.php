<?php

declare(strict_types=1);

namespace App\Exceptions\HealthCheck;

use App\Exceptions\ErrorContextException;
use RuntimeException;
use Throwable;

/**
 * Class HealthCheckFailedException
 */
class HealthCheckFailedException extends RuntimeException implements ErrorContextException
{
    private array $context;

    public function __construct(
        array $serviceStatus = [],
        string $message = 'Health check failed',
        int $code = 503,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $serviceStatus;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function errors(): array
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'context' => $this->getContext(),
        ];
    }
}
