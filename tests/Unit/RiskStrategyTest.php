<?php

declare(strict_types=1);

namespace RoiCore\Tests\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use RoiCore\Domain\Entities\FloodPoint;
use RoiCore\Domain\Enums\RiskLevel;
use RoiCore\Domain\Strategies\CompositeRiskStrategy;
use RoiCore\Domain\Strategies\RainfallRiskStrategy;
use RoiCore\Domain\Strategies\WaterLevelStrategy;
use RoiCore\Domain\ValueObjects\GeoPoint;
use RoiCore\Domain\ValueObjects\WeatherData;

final class RiskStrategyTest extends TestCase
{
    public function testWaterLevelStrategyEvaluatesCorrectly(): void
    {
        $strategy = new WaterLevelStrategy();

        $pointLow = new FloodPoint('p1', 'Point 1', new GeoPoint(16.0, 103.0), 100, 0.1, RiskLevel::LOW);
        $this->assertSame(RiskLevel::LOW, $strategy->evaluate($pointLow));

        $pointMedium = new FloodPoint('p2', 'Point 2', new GeoPoint(16.0, 103.0), 100, 0.3, RiskLevel::LOW);
        $this->assertSame(RiskLevel::MEDIUM, $strategy->evaluate($pointMedium));

        $pointHigh = new FloodPoint('p3', 'Point 3', new GeoPoint(16.0, 103.0), 100, 0.6, RiskLevel::LOW);
        $this->assertSame(RiskLevel::HIGH, $strategy->evaluate($pointHigh));

        $pointCritical = new FloodPoint('p4', 'Point 4', new GeoPoint(16.0, 103.0), 100, 1.2, RiskLevel::LOW);
        $this->assertSame(RiskLevel::CRITICAL, $strategy->evaluate($pointCritical));
    }

    public function testCompositeStrategyCombinesFactors(): void
    {
        $composite = new CompositeRiskStrategy();
        $composite->addStrategy(new WaterLevelStrategy(), 1.0);
        $composite->addStrategy(new RainfallRiskStrategy(), 1.0);

        $point = new FloodPoint('p1', 'Point 1', new GeoPoint(16.0, 103.0), 100, 1.0, RiskLevel::LOW); // Critical (4.0)
        $weather = new WeatherData(28, 80, 55.0, 100.0, 'Heavy Rain', 5, new DateTimeImmutable()); // Critical (4.0)

        $risk = $composite->evaluate($point, $weather);
        $this->assertSame(RiskLevel::CRITICAL, $risk);
    }
}
