<?php

declare(strict_types=1);

namespace RoiCore\Domain\UseCases;

use InvalidArgumentException;
use RoiCore\Core\Result;
use RoiCore\Domain\Interfaces\IFloodPointRepository;
use RoiCore\Domain\Interfaces\IRiskStrategy;
use RoiCore\Domain\Interfaces\IWeatherService;
use RoiCore\Domain\ValueObjects\GeoPoint;

final class CheckFloodPointsUseCase
{
    public function __construct(
        private readonly IFloodPointRepository $pointRepository,
        private readonly ?IRiskStrategy $riskStrategy = null,
        private readonly ?IWeatherService $weatherService = null
    ) {
    }

    public function execute(?float $lat = null, ?float $lng = null, float $radiusMeters = 50000.0): Result
    {
        try {
            if ($lat !== null && $lng !== null) {
                $center = new GeoPoint($lat, $lng);
                $points = $this->pointRepository->queryNearby($center, $radiusMeters);
            } else {
                $points = $this->pointRepository->listAll();
            }

            // Recalculate risk dynamically using composite strategy if services are provided
            if ($this->riskStrategy !== null) {
                foreach ($points as $point) {
                    $weather = $this->weatherService?->getCurrentWeather($point->location);
                    $point->riskLevel = $this->riskStrategy->evaluate($point, $weather);
                }
            }

            return Result::ok($points);
        } catch (InvalidArgumentException $e) {
            return Result::fail($e->getMessage());
        }
    }
}
