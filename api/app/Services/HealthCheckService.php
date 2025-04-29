<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Status;
use App\Exceptions\HealthCheck\HealthCheckFailedException;
use BadMethodCallException;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class HealthCheckService
 */
class HealthCheckService
{
    private const CHECKED_SERVICES = [
        'redis',
        'cache',
    ];

    /**
     * @throws HealthCheckFailedException|BadMethodCallException
     */
    public function checkServices(): array
    {
        $hasErrors = false;
        $serviceStatus = [];
        foreach (self::CHECKED_SERVICES as $service) {
            if (! method_exists($this, 'is'.ucfirst($service).'Available')) {
                throw new BadMethodCallException("Method is{$service}Available does not exist");
            }

            $method = 'is'.ucfirst($service).'Available';
            $serviceStatus[$service] = $this->$method()
                ? Status::SUCCESS->value
                : Status::ERROR->value;

            if ($serviceStatus[$service] === Status::ERROR->value) {
                $hasErrors = true;
            }
        }

        if ($hasErrors) {
            throw new HealthCheckFailedException($serviceStatus);
        }

        return $serviceStatus;
    }

    private function isRedisAvailable(): bool
    {
        try {
            Cache::store('redis')->put('health_check', 'OK', 10);
            $value = Cache::store('redis')->get('health_check');
            if ($value === 'OK') {
                return true;
            }
        } catch (Exception $e) {
            Log::error('Redis availability check failed', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return false;
    }

    private function isCacheAvailable(): bool
    {
        try {
            $testFile = 'health_check.txt';
            Storage::put($testFile, 'OK');
            $content = Storage::get($testFile);
            Storage::delete($testFile);

            if ($content === 'OK') {
                return true;
            }
        } catch (Exception $e) {
            Log::error('Cache availability check failed', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return false;
    }
}
