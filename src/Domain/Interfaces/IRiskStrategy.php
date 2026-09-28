<?php

declare(strict_types=1);

namespace RoiCore\Domain\Interfaces;

use RoiCore\Domain\Entities\FloodPoint;
use RoiCore\Domain\Enums\RiskLevel;
use RoiCore\Domain\ValueObjects\WeatherData;

interface IRiskStrategy
{
    public function evaluate(FloodPoint $point, ?WeatherData $weather = null): RiskLevel;
}
