<?php

declare(strict_types=1);

namespace RoiCore\Domain\ValueObjects;

final class BoundingBox
{
    public function __construct(
        public readonly float $minLat,
        public readonly float $minLng,
        public readonly float $maxLat,
        public readonly float $maxLng
    ) {
    }

    public function contains(GeoPoint $point): bool
    {
        return $point->latitude >= $this->minLat
            && $point->latitude <= $this->maxLat
            && $point->longitude >= $this->minLng
            && $point->longitude <= $this->maxLng;
    }
}
