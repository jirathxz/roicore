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

    public function isOffline(): bool
    {
        return true;
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
        if (!is_array($data) || empty($data)) {
            $this->seedInitialPoints();
            return;
        }

        $this->points = [];
        foreach ($data as $item) {
            $point = new FloodPoint(
                id: (string) $item['id'],
                title: (string) ($item['title'] ?? 'จุดตรวจน้ำท่วม'),
                location: new GeoPoint((float) $item['location']['latitude'], (float) $item['location']['longitude']),
                radiusMeters: (float) ($item['radius_meters'] ?? 200.0),
                waterLevelMeters: (float) ($item['water_level_meters'] ?? 0.0),
                riskLevel: RiskLevel::tryFrom((string) $item['risk_level']) ?? RiskLevel::MEDIUM,
                reportCount: (int) ($item['report_count'] ?? 1),
                updatedAt: new DateTimeImmutable($item['updated_at'] ?? 'now'),
                polygonCoordinates: $item['polygon_coordinates'] ?? null,
                zoneName: $item['zone_name'] ?? null,
                affectedAreaSqkm: isset($item['affected_area_sqkm']) ? (float)$item['affected_area_sqkm'] : null
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

    public function seedInitialPoints(): void
    {
        $mockPoints = [
            new FloodPoint(
                id: 'd3b07384-d113-4c91-9c62-124b89f53e01',
                title: 'สะพานข้ามแม่น้ำชี (ธวัชบุรี)',
                location: new GeoPoint(16.0538, 103.6520),
                radiusMeters: 250.0,
                waterLevelMeters: 2.85,
                riskLevel: RiskLevel::CRITICAL,
                reportCount: 5,
                updatedAt: new DateTimeImmutable('2026-10-03T07:00:00+07:00'),
                polygonCoordinates: [
                    [16.0650, 103.6410],
                    [16.0680, 103.6560],
                    [16.0620, 103.6690],
                    [16.0480, 103.6650],
                    [16.0430, 103.6500],
                    [16.0470, 103.6380]
                ],
                zoneName: 'เขตพื้นที่ลุ่มน้ำชีเอ่อท่วม ธวัชบุรี',
                affectedAreaSqkm: 2.45
            ),
            new FloodPoint(
                id: 'd3b07384-d113-4c91-9c62-124b89f53e02',
                title: 'จุดตัดคลองส่งน้ำเสลภูมิ',
                location: new GeoPoint(16.0350, 103.7890),
                radiusMeters: 180.0,
                waterLevelMeters: 1.40,
                riskLevel: RiskLevel::HIGH,
                reportCount: 3,
                updatedAt: new DateTimeImmutable('2026-10-03T07:00:00+07:00'),
                polygonCoordinates: [
                    [16.0440, 103.7780],
                    [16.0470, 103.7950],
                    [16.0390, 103.8030],
                    [16.0270, 103.7940],
                    [16.0280, 103.7810]
                ],
                zoneName: 'เขตพื้นที่ล้นคลองส่งน้ำเสลภูมิ',
                affectedAreaSqkm: 1.35
            ),
            new FloodPoint(
                id: 'd3b07384-d113-4c91-9c62-124b89f53e03',
                title: 'ถนนสายเลี่ยงเมืองร้อยเอ็ด ทิศตะวันออก',
                location: new GeoPoint(16.0680, 103.6850),
                radiusMeters: 150.0,
                waterLevelMeters: 0.45,
                riskLevel: RiskLevel::MEDIUM,
                reportCount: 2,
                updatedAt: new DateTimeImmutable('2026-10-03T07:00:00+07:00'),
                polygonCoordinates: [
                    [16.0740, 103.6780],
                    [16.0760, 103.6920],
                    [16.0670, 103.6960],
                    [16.0610, 103.6870],
                    [16.0630, 103.6780]
                ],
                zoneName: 'แนวท่วมขังผิวถนนเลี่ยงเมือง',
                affectedAreaSqkm: 0.75
            ),
            new FloodPoint(
                id: 'd3b07384-d113-4c91-9c62-124b89f53e04',
                title: 'อ่างเก็บน้ำธวัชชัย ระดับเฝ้าระวัง',
                location: new GeoPoint(16.0120, 103.7200),
                radiusMeters: 300.0,
                waterLevelMeters: 0.20,
                riskLevel: RiskLevel::LOW,
                reportCount: 1,
                updatedAt: new DateTimeImmutable('2026-10-03T07:00:00+07:00'),
                polygonCoordinates: [
                    [16.0220, 103.7110],
                    [16.0250, 103.7290],
                    [16.0150, 103.7350],
                    [16.0030, 103.7230],
                    [16.0070, 103.7100]
                ],
                zoneName: 'พื้นที่เฝ้าระวังอ่างธวัชชัย',
                affectedAreaSqkm: 1.10
            ),
            new FloodPoint(
                id: 'd3b07384-d113-4c91-9c62-124b89f53e05',
                title: 'สะพานข้ามลำน้ำยัง (โพนทอง)',
                location: new GeoPoint(16.3015, 103.9850),
                radiusMeters: 200.0,
                waterLevelMeters: 2.10,
                riskLevel: RiskLevel::HIGH,
                reportCount: 4,
                updatedAt: new DateTimeImmutable('2026-10-03T07:00:00+07:00'),
                polygonCoordinates: [
                    [16.3120, 103.9740],
                    [16.3160, 103.9900],
                    [16.3080, 103.9990],
                    [16.2920, 103.9930],
                    [16.2900, 103.9800]
                ],
                zoneName: 'เขตตลิ่งลำน้ำยัง โพนทอง',
                affectedAreaSqkm: 1.85
            ),
        ];

        $this->points = [];
        foreach ($mockPoints as $p) {
            $this->points[$p->id] = $p;
        }
        $this->persist();
    }
}
