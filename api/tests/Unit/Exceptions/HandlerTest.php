<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\ErrorContextException;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;
use Throwable;

class HandlerTest extends TestCase
{
    #[Test]
    #[DataProvider('exceptionDataProvider')]
    public function render_handles_exceptions_correctly(Throwable $exception, array $expectedResponse, int $expectedStatusCode): void
    {
        // Arrange
        $handler = new Handler(app());
        $request = Request::create('/api/test');

        Log::spy(); // Spy on the logger to verify logging behavior
        Log::shouldReceive('error')->once()->with(
            $exception->getMessage(),
            [
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]
        );

        // Act
        $response = $handler->render($request, $exception);

        // Assert
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public static function exceptionDataProvider(): array
    {
        return [
            'generic exception' => [
                'exception' => new \Exception('Something went wrong', 500),
                'expectedResponse' => [
                    'status' => 'ERROR',
                    'errors' => [
                        'message' => 'Something went wrong',
                        'code' => 500,
                    ],
                ],
                'expectedStatusCode' => 500,
            ],
            'http exception' => [
                'exception' => new HttpException(404, 'Not Found'),
                'expectedResponse' => [
                    'status' => 'ERROR',
                    'errors' => [
                        'message' => 'Not Found',
                        'code' => 404,
                    ],
                ],
                'expectedStatusCode' => 404,
            ],
            'error context exception' => [
                'exception' => new class extends \Exception implements ErrorContextException
                {
                    public function errors(): array
                    {
                        return ['custom' => 'Custom error message'];
                    }
                },
                'expectedResponse' => [
                    'status' => 'ERROR',
                    'errors' => ['custom' => 'Custom error message'],
                ],
                'expectedStatusCode' => 500,
            ],
        ];
    }
}
