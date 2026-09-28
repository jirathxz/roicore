<?php

declare(strict_types=1);

namespace RoiCore\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Value Object representing a geographical coordinate (latitude, longitude).
 */
final class GeoPoint
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude
    ) {
        if ($this->latitude < -90.0 || $this->latitude > 90.0) {
            throw new InvalidArgumentException("Latitude must be between -90 and 90 degrees. Given: {$this->latitude}");
        }

        if ($this->longitude < -180.0 || $this->longitude > 180.0) {
            throw new InvalidArgumentException("Longitude must be between -180 and 180 degrees. Given: {$this->longitude}");
        }
    }

    public function toArray(): array
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }

    public function equals(GeoPoint $other): bool
    {
        return abs($this->latitude - $other->latitude) < 0.000001
            && abs($this->longitude - $other->longitude) < 0.000001;
    }

    /**
     * Calculate Haversine distance in meters to another GeoPoint.
     */
    public function distanceTo(GeoPoint $other): float
    {
        $earthRadius = 6371000; // meters

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($other->latitude);
        $lonTo = deg2rad($other->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            sin($latDelta / 2) ** 2 +
            cos($latFrom) * cos($latTo) * sin($lonDelta / 2) ** 2
        ));

        return $angle * $earthRadius;
    }
}
