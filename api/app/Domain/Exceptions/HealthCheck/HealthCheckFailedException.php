<?php

declare(strict_types=1);

namespace App\Domain\Exceptions\HealthCheck;

use RuntimeException;
use Throwable;

/**
 * Class HealthCheckFailedException
 */
class HealthCheckFailedException extends RuntimeException
{
    private array $serviceStatus;

    public function __construct(
        array $serviceStatus = [],
        string $message = 'Health check failed',
        int $code = 503,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->serviceStatus = $serviceStatus;
    }

    public function getServiceStatus(): array
    {
        return $this->serviceStatus;
    }
}
