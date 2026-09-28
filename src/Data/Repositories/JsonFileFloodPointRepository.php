<?php

declare(strict_types=1);

namespace RoiCore\Data\Repositories;

use DateTimeImmutable;
use RoiCore\Domain\Entities\FloodPoint;
use RoiCore\Domain\Enums\RiskLevel;
use RoiCore\Domain\Interfaces\IFloodPointRepository;
use RoiCore\Domain\ValueObjects\GeoPoint;

final class JsonFileFloodPointRepository implements IFloodPointRepository
{
    private string $filePath;

    /**
     * @var array<string, FloodPoint>
     */
    private array $points = [];

    public function __construct(?string $storageDir = null)
    {
        $dir = $storageDir ?? dirname(__DIR__, 3) . '/storage/data';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $this->filePath = $dir . '/flood_points.json';
        $this->load();
    }

    public function listAll(): array
    {
        return array_values($this->points);
    }

    public function queryNearby(GeoPoint $center, float $radiusMeters): array
    {
        $results = [];
        foreach ($this->points as $point) {
            $dist = $center->distanceTo($point->location);
            if ($dist <= $radiusMeters) {
                $results[] = $point;
            }
        }
        return $results;
    }

    public function findById(string $id): ?FloodPoint
    {
        return $this->points[$id] ?? null;
    }

    public function upsert(FloodPoint $point): FloodPoint
    {
        $this->points[$point->id] = $point;
        $this->persist();
        return $point;
    }

    private function load(): void
    {
        if (!file_exists($this->filePath)) {
            $this->seedInitialPoints();
            return;
        }

        $content = file_get_contents($this->filePath);
        if ($content === false || trim($content) === '') {
            $this->seedInitialPoints();
            return;
        }

        $data = json_decode($content, true);
        if (!is_array($data)) {
            $this->seedInitialPoints();
            return;
        }

        foreach ($data as $item) {
            $point = new FloodPoint(
                id: (string) $item['id'],
                title: (string) ($item['title'] ?? 'จุดตรวจน้ำท่วม'),
                location: new GeoPoint((float) $item['location']['latitude'], (float) $item['location']['longitude']),
                radiusMeters: (float) ($item['radius_meters'] ?? 200.0),
                waterLevelMeters: (float) ($item['water_level_meters'] ?? 0.0),
                riskLevel: RiskLevel::tryFrom((string) $item['risk_level']) ?? RiskLevel::MEDIUM,
                reportCount: (int) ($item['report_count'] ?? 1),
                updatedAt: new DateTimeImmutable($item['updated_at'] ?? 'now')
            );

            $this->points[$point->id] = $point;
        }
    }

    private function persist(): void
    {
        $data = [];
        foreach ($this->points as $point) {
            $data[] = $point->toArray();
        }
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function seedInitialPoints(): void
    {
        $points = [
            new FloodPoint(
                id: 'pt_101',
                title: 'จุดเสี่ยงริมตลิ่งแม่น้ำชี อ.เมือง จ.ร้อยเอ็ด',
                location: new GeoPoint(16.0538, 103.6520),
                radiusMeters: 500.0,
                waterLevelMeters: 0.65,
                riskLevel: RiskLevel::HIGH,
                reportCount: 14,
                updatedAt: new DateTimeImmutable('-15 minutes')
            ),
            new FloodPoint(
                id: 'pt_102',
                title: 'ชุมชนท่ากอไผ่ อ.วารินชำราบ จ.อุบลราชธานี',
                location: new GeoPoint(15.2012, 104.8624),
                radiusMeters: 800.0,
                waterLevelMeters: 1.15,
                riskLevel: RiskLevel::CRITICAL,
                reportCount: 38,
                updatedAt: new DateTimeImmutable('-5 minutes')
            ),
            new FloodPoint(
                id: 'pt_103',
                title: 'พื้นที่ลุ่มต่ำริมคลองบางเขน กรุงเทพฯ',
                location: new GeoPoint(13.8560, 100.5750),
                radiusMeters: 300.0,
                waterLevelMeters: 0.25,
                riskLevel: RiskLevel::MEDIUM,
                reportCount: 5,
                updatedAt: new DateTimeImmutable('-1 hour')
            ),
            new FloodPoint(
                id: 'pt_104',
                title: 'จุดเฝ้าระวังน้ำป่าไหลหลาก อ.แม่ริม จ.เชียงใหม่',
                location: new GeoPoint(18.9142, 98.9431),
                radiusMeters: 400.0,
                waterLevelMeters: 0.15,
                riskLevel: RiskLevel::LOW,
                reportCount: 2,
                updatedAt: new DateTimeImmutable('-3 hours')
            ),
        ];

        foreach ($points as $p) {
            $this->upsert($p);
        }
    }
}
