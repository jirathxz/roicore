<?php

declare(strict_types=1);

namespace RoiCore\Domain\Strategies;

use RoiCore\Domain\Entities\FloodPoint;
use RoiCore\Domain\Enums\RiskLevel;
use RoiCore\Domain\Interfaces\IRiskStrategy;
use RoiCore\Domain\ValueObjects\WeatherData;

final class WaterLevelStrategy implements IRiskStrategy
{
    public function evaluate(FloodPoint $point, ?WeatherData $weather = null): RiskLevel
    {
        $water = $point->waterLevelMeters;

        if ($water >= 1.0) {
            return RiskLevel::CRITICAL;
        }

        if ($water >= 0.5) {
            return RiskLevel::HIGH;
        }

        if ($water >= 0.2) {
            return RiskLevel::MEDIUM;
        }

        return RiskLevel::LOW;
    }
}
