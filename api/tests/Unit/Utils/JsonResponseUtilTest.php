<?php

declare(strict_types=1);

namespace Tests\Unit\Utils;

use App\Enums\Status;
use App\Utils\JsonResponseUtil;
use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class JsonResponseUtilTest extends TestCase
{
    #[Test]
    #[DataProvider('successResponseDataProvider')]
    public function success_response(array $data, int $statusCode, array $expectedResponse): void
    {
        // Act
        $response = JsonResponseUtil::successResponse($data, $statusCode);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getData(true));
    }

    #[Test]
    #[DataProvider('errorResponseDataProvider')]
    public function error_response(array $errorsData, int $statusCode, array $expectedResponse): void
    {
        // Act
        $response = JsonResponseUtil::errorResponse($errorsData, $statusCode);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $response->getData(true));
    }

    public static function successResponseDataProvider(): array
    {
        return [
            'basic success response' => [
                'data' => ['key' => 'value'],
                'statusCode' => Response::HTTP_OK,
                'expectedResponse' => [
                    'status' => Status::SUCCESS->value,
                    'data' => ['key' => 'value'],
                ],
            ],
            'empty data' => [
                'data' => [],
                'statusCode' => Response::HTTP_NO_CONTENT,
                'expectedResponse' => [
                    'status' => Status::SUCCESS->value,
                    'data' => [],
                ],
            ],
        ];
    }

    public static function errorResponseDataProvider(): array
    {
        return [
            'basic error response' => [
                'errorsData' => ['error' => 'Invalid input'],
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'expectedResponse' => [
                    'status' => Status::ERROR->value,
                    'errors' => ['error' => 'Invalid input'],
                ],
            ],
            'multiple errors' => [
                'errorsData' => ['error1' => 'Error 1', 'error2' => 'Error 2'],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'expectedResponse' => [
                    'status' => Status::ERROR->value,
                    'errors' => ['error1' => 'Error 1', 'error2' => 'Error 2'],
                ],
            ],
        ];
    }
}
