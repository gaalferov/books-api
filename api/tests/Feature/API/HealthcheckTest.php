<?php

declare(strict_types=1);

namespace Tests\Feature\API;

use App\Exceptions\HealthCheck\HealthCheckFailedException;
use App\Services\HealthCheckService;
use BadMethodCallException;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HealthcheckTest extends TestCase
{
    #[Test]
    public function healthcheck_returns_successful_response(): void
    {
        // Mock the HealthCheckService to simulate a successful response
        $this->mock(HealthCheckService::class, function ($mock) {
            $mock->shouldReceive('checkServices')
                ->andReturn([
                    'redis' => 'OK',
                    'cache' => 'OK',
                ]);
        });

        // Act
        $response = $this->getJson('/api/healthcheck');

        // Assert
        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'OK',
                'data' => [
                    'redis' => 'OK',
                    'cache' => 'OK',
                ],
            ]);
    }

    #[Test]
    public function healthcheck_returns_error_response(): void
    {
        // Simulate a failed service by mocking the HealthCheckService
        $this->mock(HealthCheckService::class, function ($mock) {
            $mock->shouldReceive('checkServices')
                ->andThrow(new HealthCheckFailedException([
                    'redis' => 'ERROR',
                    'cache' => 'OK',
                ]));
        });

        // Act
        $response = $this->getJson('/api/healthcheck');

        // Assert
        $response
            ->assertStatus(503)
            ->assertJson([
                'status' => 'ERROR',
                'errors' => [
                    'code' => 503,
                    'message' => 'Health check failed',
                    'context' => [
                        'redis' => 'ERROR',
                        'cache' => 'OK',
                    ],
                ],
            ]);
    }

    #[Test]
    public function healthcheck_returns_internal_server_error(): void
    {
        // Simulate an unexpected error by throwing an exception
        $this->mock(HealthCheckService::class, function ($mock) {
            $mock->shouldReceive('checkServices')
                ->andThrow(new Exception('Unexpected error'));
        });

        // Act
        $response = $this->getJson('/api/healthcheck');

        // Assert
        $response
            ->assertStatus(500)
            ->assertJson([
                'status' => 'ERROR',
                'errors' => [
                    'code' => 500,
                    'message' => 'Unexpected error',
                ],
            ]);
    }

    #[Test]
    public function healthcheck_returns_bad_method_call_error(): void
    {
        // Simulate a bad method call by throwing a BadMethodCallException
        $this->mock(HealthCheckService::class, function ($mock) {
            $mock->shouldReceive('checkServices')
                ->andThrow(new BadMethodCallException('Method does not exist'));
        });

        // Act
        $response = $this->getJson('/api/healthcheck');

        // Assert
        $response
            ->assertStatus(500)
            ->assertJson([
                'status' => 'ERROR',
                'errors' => [
                    'code' => 500,
                    'message' => 'Method does not exist',
                ],
            ]);
    }
}
