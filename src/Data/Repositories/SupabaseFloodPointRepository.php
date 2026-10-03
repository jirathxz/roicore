<?php

declare(strict_types=1);

namespace RoiCore\Data\Repositories;

use DateTimeImmutable;
use Exception;
use RoiCore\Data\Services\SupabaseClient;
use RoiCore\Domain\Entities\FloodPoint;
use RoiCore\Domain\Enums\RiskLevel;
use RoiCore\Domain\Interfaces\IFloodPointRepository;
use RoiCore\Domain\ValueObjects\GeoPoint;

final class SupabaseFloodPointRepository implements IFloodPointRepository
{
    private bool $isFallbackActive = false;

    public function __construct(
        private readonly SupabaseClient $client,
        private readonly ?IFloodPointRepository $fallbackRepository = null
    ) {
    }

    public function isOffline(): bool
    {
        return $this->isFallbackActive || !$this->client->isConfigured();
    }

    public function listAll(): array
    {
        try {
            $rows = $this->client->select('flood_points', [
                'select' => '*',
                'status' => 'eq.ACTIVE',
                'order' => 'updated_at.desc',
            ]);

            return array_map([$this, 'mapRowToEntity'], $rows);
        } catch (Exception $e) {
            if ($this->fallbackRepository !== null) {
                $this->isFallbackActive = true;
                return $this->fallbackRepository->listAll();
            }
            throw $e;
        }
    }

    public function queryNearby(GeoPoint $center, float $radiusMeters): array
    {
        try {
            $all = $this->listAll();
            $results = [];
            foreach ($all as $point) {
                if ($center->distanceTo($point->location) <= $radiusMeters) {
                    $results[] = $point;
                }
            }
            return $results;
        } catch (Exception $e) {
            if ($this->fallbackRepository !== null) {
                $this->isFallbackActive = true;
                return $this->fallbackRepository->queryNearby($center, $radiusMeters);
            }
            throw $e;
        }
    }

    public function findById(string $id): ?FloodPoint
    {
        try {
            $rows = $this->client->select('flood_points', [
                'id' => "eq.{$id}",
                'select' => '*',
                'limit' => '1',
            ]);

            if (empty($rows)) {
                return null;
            }

            return $this->mapRowToEntity($rows[0]);
        } catch (Exception $e) {
            if ($this->fallbackRepository !== null) {
                $this->isFallbackActive = true;
                return $this->fallbackRepository->findById($id);
            }
            throw $e;
        }
    }

    public function upsert(FloodPoint $point): FloodPoint
    {
        try {
            $payload = [
                'id' => $point->id,
                'title' => $point->title,
                'latitude' => $point->location->latitude,
                'longitude' => $point->location->longitude,
                'radius_meters' => $point->radiusMeters,
                'water_level_meters' => $point->waterLevelMeters,
                'risk_level' => $point->riskLevel->value,
                'report_count' => $point->reportCount,
                'updated_at' => $point->updatedAt->format(DateTimeImmutable::ATOM),
            ];

            $this->client->insert('flood_points', $payload);
            $this->fallbackRepository?->upsert($point);
            return $point;
        } catch (Exception $e) {
            if ($this->fallbackRepository !== null) {
                $this->isFallbackActive = true;
                return $this->fallbackRepository->upsert($point);
            }
            throw $e;
        }
    }

    /**
     * @param array<string, mixed> $row
     */
    private function mapRowToEntity(array $row): FloodPoint
    {
        return new FloodPoint(
            id: (string) ($row['id'] ?? ''),
            title: (string) ($row['title'] ?? 'จุดเสี่ยงน้ำท่วม'),
            location: new GeoPoint((float) ($row['latitude'] ?? 0.0), (float) ($row['longitude'] ?? 0.0)),
            radiusMeters: (float) ($row['radius_meters'] ?? 200.0),
            waterLevelMeters: (float) ($row['water_level_meters'] ?? 0.0),
            riskLevel: RiskLevel::from((string) ($row['risk_level'] ?? 'MEDIUM')),
            reportCount: (int) ($row['report_count'] ?? 1),
            updatedAt: isset($row['updated_at']) ? new DateTimeImmutable((string) $row['updated_at']) : new DateTimeImmutable()
        );
    }
}
