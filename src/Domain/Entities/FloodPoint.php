<?php

declare(strict_types=1);

namespace RoiCore\Domain\Entities;

use DateTimeImmutable;
use RoiCore\Domain\Enums\RiskLevel;
use RoiCore\Domain\ValueObjects\GeoPoint;

final class FloodPoint
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly GeoPoint $location,
        public readonly float $radiusMeters,
        public float $waterLevelMeters,
        public RiskLevel $riskLevel,
        public int $reportCount = 1,
        public DateTimeImmutable $updatedAt = new DateTimeImmutable()
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'location' => $this->location->toArray(),
            'radius_meters' => $this->radiusMeters,
            'water_level_meters' => $this->waterLevelMeters,
            'risk_level' => $this->riskLevel->value,
            'risk_level_label' => $this->riskLevel->labelThai(),
            'report_count' => $this->reportCount,
            'updated_at' => $this->updatedAt->format(DateTimeImmutable::ATOM),
        ];
    }
}
