<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Utils\JsonResponseUtil;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    public static function successResponse(
        array $data,
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        return JsonResponseUtil::successResponse($data, $statusCode);
    }

    public static function errorResponse(
        array $errorsData = [],
        int $statusCode = Response::HTTP_BAD_REQUEST,
    ): JsonResponse {
        return JsonResponseUtil::errorResponse($errorsData, $statusCode);
    }
}
