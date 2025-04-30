<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Enums\Status;
use App\Exceptions\HealthCheck\HealthCheckFailedException;
use App\Services\HealthCheckService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HealthCheckServiceTest extends TestCase
{
    #[Test]
    #[DataProvider('serviceAvailabilityProvider')]
    public function check_services_returns_correct_statuses(array $methodMocks, array $expectedStatus, bool $shouldThrow): void
    {
        $mock = $this->getMockBuilder(HealthCheckService::class)
            ->onlyMethods(array_keys($methodMocks))
            ->getMock();

        foreach ($methodMocks as $method => $returnValue) {
            $mock->method($method)->willReturn($returnValue);
        }

        if ($shouldThrow) {
            $this->expectException(HealthCheckFailedException::class);
            try {
                $mock->checkServices();
            } catch (HealthCheckFailedException $e) {
                $this->assertEquals($expectedStatus, $e->getContext());
                throw $e;
            }
        } else {
            $result = $mock->checkServices();
            $this->assertEquals($expectedStatus, $result);
        }
    }

    public static function serviceAvailabilityProvider(): array
    {
        return [
            'all services OK' => [
                'methodMocks' => [
                    'isRedisAvailable' => true,
                    'isCacheAvailable' => true,
                ],
                'expectedStatus' => [
                    'redis' => Status::SUCCESS->value,
                    'cache' => Status::SUCCESS->value,
                ],
                'shouldThrow' => false,
            ],
            'redis fails' => [
                'methodMocks' => [
                    'isRedisAvailable' => false,
                    'isCacheAvailable' => true,
                ],
                'expectedStatus' => [
                    'redis' => Status::ERROR->value,
                    'cache' => Status::SUCCESS->value,
                ],
                'shouldThrow' => true,
            ],
            'cache fails' => [
                'methodMocks' => [
                    'isRedisAvailable' => true,
                    'isCacheAvailable' => false,
                ],
                'expectedStatus' => [
                    'redis' => Status::SUCCESS->value,
                    'cache' => Status::ERROR->value,
                ],
                'shouldThrow' => true,
            ],
            'both fail' => [
                'methodMocks' => [
                    'isRedisAvailable' => false,
                    'isCacheAvailable' => false,
                ],
                'expectedStatus' => [
                    'redis' => Status::ERROR->value,
                    'cache' => Status::ERROR->value,
                ],
                'shouldThrow' => true,
            ],
        ];
    }
}
