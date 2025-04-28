<?php

declare(strict_types=1);

namespace App\Utils;

use App\Enums\Status;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResponseUtil
 */
class JsonResponseUtil
{
    public static function successResponse(
        array $data,
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        return response()->json(
            [
                'status' => Status::SUCCESS->value,
                'data' => $data,
            ],
            $statusCode
        );
    }

    public static function errorResponse(
        array $errorsData = [],
        int $statusCode = Response::HTTP_BAD_REQUEST,
    ): JsonResponse {
        return response()->json(
            [
                'status' => Status::ERROR->value,
                'errors' => $errorsData,
            ],
            $statusCode
        );
    }
}
