<?php

declare(strict_types=1);

namespace RoiCore\Domain\Interfaces;

use RoiCore\Domain\Entities\FloodPoint;
use RoiCore\Domain\ValueObjects\GeoPoint;

interface IFloodPointRepository
{
    /**
     * @return FloodPoint[]
     */
    public function listAll(): array;

    /**
     * @return FloodPoint[]
     */
    public function queryNearby(GeoPoint $center, float $radiusMeters): array;

    public function findById(string $id): ?FloodPoint;

    public function upsert(FloodPoint $point): FloodPoint;
}
