<?php

declare(strict_types=1);

namespace RoiCore\Domain\Strategies;

use RoiCore\Domain\Entities\FloodPoint;
use RoiCore\Domain\Enums\RiskLevel;
use RoiCore\Domain\Interfaces\IRiskStrategy;
use RoiCore\Domain\ValueObjects\WeatherData;

final class RainfallRiskStrategy implements IRiskStrategy
{
    public function evaluate(FloodPoint $point, ?WeatherData $weather = null): RiskLevel
    {
        if ($weather === null) {
            return RiskLevel::LOW;
        }

        $rain1h = $weather->rainfall1h;

        if ($rain1h >= 50.0) {
            return RiskLevel::CRITICAL;
        }

        if ($rain1h >= 25.0) {
            return RiskLevel::HIGH;
        }

        if ($rain1h >= 10.0) {
            return RiskLevel::MEDIUM;
        }

        return RiskLevel::LOW;
    }
}
