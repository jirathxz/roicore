<?php

declare(strict_types=1);

namespace RoiCore\Domain\ValueObjects;

use DateTimeImmutable;

final class WeatherData
{
    public function __construct(
        public readonly float $tempC,
        public readonly float $humidity,
        public readonly float $rainfall1h,
        public readonly float $rainfall24h,
        public readonly string $condition,
        public readonly float $windSpeed,
        public readonly DateTimeImmutable $fetchedAt
    ) {
    }

    public function toArray(): array
    {
        return [
            'temp_c' => $this->tempC,
            'humidity' => $this->humidity,
            'rainfall_1h_mm' => $this->rainfall1h,
            'rainfall_24h_mm' => $this->rainfall24h,
            'condition' => $this->condition,
            'wind_speed_mps' => $this->windSpeed,
            'fetched_at' => $this->fetchedAt->format(DateTimeImmutable::ATOM),
        ];
    }
}
