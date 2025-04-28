<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Enums\Status;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    public function successResponse(
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

    public function errorResponse(
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
