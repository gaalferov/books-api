<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\HealthCheckService;
use App\Utils\JsonResponseUtil;
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
        return JsonResponseUtil::successResponse(
            $this->healthCheckService->checkServices()
        );
    }
}
