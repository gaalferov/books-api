<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Domain\Services\HealthCheckService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * Class HealthCheck
 */
class HealthCheckController extends Controller
{
    public function __construct(
        private readonly HealthCheckService $healthCheckService
    ) {}

    public function __invoke(): JsonResponse
    {
        return $this->successResponse(
            $this->healthCheckService->checkServices()
        );
    }
}
