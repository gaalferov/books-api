<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Services\HealthCheckService;
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
        return self::successResponse($this->healthCheckService->checkServices());
    }
}
