<?php

declare(strict_types=1);

namespace RoiCore\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RoiCore\Data\Repositories\SupabaseFloodPointRepository;
use RoiCore\Data\Repositories\SupabaseFloodReportRepository;
use RoiCore\Data\Services\SupabaseClient;
use RoiCore\Domain\Entities\FloodPoint;
use RoiCore\Domain\Entities\FloodReport;
use RoiCore\Domain\Enums\RiskLevel;
use RoiCore\Domain\Enums\Severity;
use RoiCore\Domain\ValueObjects\GeoPoint;

final class SupabaseRepositoryTest extends TestCase
{
    public function testSupabaseFloodReportRepositorySaveAndList(): void
    {
        $mock = new MockHandler([
            // 1. Response for insert
            new Response(201, ['Content-Type' => 'application/json'], json_encode([
                [
                    'id' => 'test-report-id',
                    'latitude' => 16.0538,
                    'longitude' => 103.6520,
                    'severity' => 3,
                    'description' => 'น้ำท่วมถนนหลัก',
                    'phone' => '0812345678',
                    'status' => 'PENDING',
                    'reported_at' => '2026-10-02T12:00:00+00:00',
                ]
            ])),
            // 2. Response for select
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                [
                    'id' => 'test-report-id',
                    'latitude' => 16.0538,
                    'longitude' => 103.6520,
                    'severity' => 3,
                    'description' => 'น้ำท่วมถนนหลัก',
                    'phone' => '0812345678',
                    'status' => 'PENDING',
                    'reported_at' => '2026-10-02T12:00:00+00:00',
                ]
            ]))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $supabaseClient = new SupabaseClient('http://localhost:54321', 'test-key', $httpClient);
        $repo = new SupabaseFloodReportRepository($supabaseClient);

        $report = new FloodReport(
            id: 'test-report-id',
            location: new GeoPoint(16.0538, 103.6520),
            severity: Severity::HIGH,
            description: 'น้ำท่วมถนนหลัก',
            phone: '0812345678'
        );

        $saved = $repo->save($report);
        $this->assertSame('test-report-id', $saved->id);

        $list = $repo->listAll();
        $this->assertCount(1, $list);
        $this->assertSame('test-report-id', $list[0]->id);
        $this->assertSame(16.0538, $list[0]->location->latitude);
    }

    public function testSupabaseFloodPointRepositoryListAll(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                [
                    'id' => 'point-1',
                    'title' => 'สะพานข้ามแม่น้ำชี',
                    'latitude' => 16.0538,
                    'longitude' => 103.6520,
                    'radius_meters' => 250,
                    'water_level_meters' => 2.8,
                    'risk_level' => 'CRITICAL',
                    'report_count' => 4,
                    'updated_at' => '2026-10-02T12:00:00+00:00',
                ]
            ]))
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $supabaseClient = new SupabaseClient('http://localhost:54321', 'test-key', $httpClient);
        $repo = new SupabaseFloodPointRepository($supabaseClient);

        $points = $repo->listAll();
        $this->assertCount(1, $points);
        $this->assertSame('point-1', $points[0]->id);
        $this->assertSame(RiskLevel::CRITICAL, $points[0]->riskLevel);
    }
}
