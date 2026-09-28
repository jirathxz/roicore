<?php

declare(strict_types=1);

namespace RoiCore\Domain\UseCases;

use InvalidArgumentException;
use RoiCore\Core\Result;
use RoiCore\Domain\Interfaces\IWeatherService;
use RoiCore\Domain\ValueObjects\GeoPoint;

final class GetWeatherUseCase
{
    public function __construct(
        private readonly IWeatherService $weatherService
    ) {
    }

    public function execute(float $lat, float $lng): Result
    {
        try {
            $location = new GeoPoint($lat, $lng);
            $weather = $this->weatherService->getCurrentWeather($location);
            return Result::ok($weather);
        } catch (InvalidArgumentException $e) {
            return Result::fail($e->getMessage());
        }
    }
}
