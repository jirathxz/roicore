<?php

declare(strict_types=1);

namespace RoiCore\Domain\Strategies;

use RoiCore\Domain\Entities\FloodPoint;
use RoiCore\Domain\Enums\RiskLevel;
use RoiCore\Domain\Interfaces\IRiskStrategy;
use RoiCore\Domain\ValueObjects\WeatherData;

final class CompositeRiskStrategy implements IRiskStrategy
{
    /**
     * @var array<array{strategy: IRiskStrategy, weight: float}>
     */
    private array $strategies = [];

    public function addStrategy(IRiskStrategy $strategy, float $weight = 1.0): self
    {
        $this->strategies[] = [
            'strategy' => $strategy,
            'weight' => $weight,
        ];
        return $this;
    }

    public function evaluate(FloodPoint $point, ?WeatherData $weather = null): RiskLevel
    {
        if (empty($this->strategies)) {
            return RiskLevel::LOW;
        }

        $totalScore = 0.0;
        $totalWeight = 0.0;

        foreach ($this->strategies as $item) {
            /** @var IRiskStrategy $strategy */
            $strategy = $item['strategy'];
            $weight = $item['weight'];

            $level = $strategy->evaluate($point, $weather);

            $score = match ($level) {
                RiskLevel::LOW => 1.0,
                RiskLevel::MEDIUM => 2.0,
                RiskLevel::HIGH => 3.0,
                RiskLevel::CRITICAL => 4.0,
            };

            $totalScore += $score * $weight;
            $totalWeight += $weight;
        }

        $average = $totalWeight > 0 ? $totalScore / $totalWeight : 1.0;

        if ($average >= 3.5) {
            return RiskLevel::CRITICAL;
        }

        if ($average >= 2.5) {
            return RiskLevel::HIGH;
        }

        if ($average >= 1.5) {
            return RiskLevel::MEDIUM;
        }

        return RiskLevel::LOW;
    }
}
