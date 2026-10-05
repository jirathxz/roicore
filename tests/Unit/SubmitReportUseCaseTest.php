<?php

declare(strict_types=1);

namespace RoiCore\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RoiCore\Data\Repositories\JsonFileFloodReportRepository;
use RoiCore\Domain\Entities\FloodReport;
use RoiCore\Domain\Enums\Severity;
use RoiCore\Domain\UseCases\SubmitReportUseCase;

final class SubmitReportUseCaseTest extends TestCase
{
    public function testSubmitReportSuccess(): void
    {
        $repo = new JsonFileFloodReportRepository(sys_get_temp_dir() . '/roicore_test_' . uniqid());
        $useCase = new SubmitReportUseCase($repo);

        $result = $useCase->execute([
            'latitude' => 16.0538,
            'longitude' => 103.6520,
            'severity' => 3,
            'description' => 'น้ำล้นตลิ่งระดับสูงมาก',
            'phone' => '0812345678',
        ]);

        $this->assertTrue($result->isSuccess);
        /** @var FloodReport $report */
        $report = $result->value;
        $this->assertSame(Severity::HIGH, $report->severity);
        $this->assertSame('น้ำล้นตลิ่งระดับสูงมาก', $report->description);
    }

    public function testSubmitReportFailsWithoutDescription(): void
    {
        $repo = new JsonFileFloodReportRepository(sys_get_temp_dir() . '/roicore_test_' . uniqid());
        $useCase = new SubmitReportUseCase($repo);

        $result = $useCase->execute([
            'latitude' => 16.0538,
            'longitude' => 103.6520,
            'severity' => 1,
            'description' => '',
        ]);

        $this->assertFalse($result->isSuccess);
        $this->assertStringContainsString('กรุณาระบุรายละเอียดสถานการณ์', (string)$result->error);
    }

    public function testSubmitReportWithin1KmRadiusSuccess(): void
    {
        $repo = new JsonFileFloodReportRepository(sys_get_temp_dir() . '/roicore_test_' . uniqid());
        $useCase = new SubmitReportUseCase($repo);

        // User location: 16.0538, 103.6520
        // Pinned location: ~300 meters away (16.0560, 103.6520)
        $result = $useCase->execute([
            'latitude' => 16.0560,
            'longitude' => 103.6520,
            'user_latitude' => 16.0538,
            'user_longitude' => 103.6520,
            'max_distance_meters' => 1000.0,
            'severity' => 2,
            'description' => 'น้ำท่วมขังทางเท้าประมาณ 20 ซม.',
            'phone' => '0899998888',
        ]);

        $this->assertTrue($result->isSuccess);
        /** @var FloodReport $report */
        $report = $result->value;
        $this->assertSame(Severity::MEDIUM, $report->severity);
    }

    public function testSubmitReportWithin5KmRadiusSuccess(): void
    {
        $repo = new JsonFileFloodReportRepository(sys_get_temp_dir() . '/roicore_test_' . uniqid());
        $useCase = new SubmitReportUseCase($repo);

        // User location: 16.0538, 103.6520
        // Pinned location: ~3,000 meters away (16.0800, 103.6520) - within 5 km
        $result = $useCase->execute([
            'latitude' => 16.0800,
            'longitude' => 103.6520,
            'user_latitude' => 16.0538,
            'user_longitude' => 103.6520,
            'max_distance_meters' => 5000.0,
            'severity' => 3,
            'description' => 'น้ำท่วมสูง รถเล็กผ่านไม่ได้',
            'phone' => '0899998888',
        ]);

        $this->assertTrue($result->isSuccess);
        /** @var FloodReport $report */
        $report = $result->value;
        $this->assertSame(Severity::HIGH, $report->severity);
    }

    public function testSubmitReportExceeding5KmRadiusFails(): void
    {
        $repo = new JsonFileFloodReportRepository(sys_get_temp_dir() . '/roicore_test_' . uniqid());
        $useCase = new SubmitReportUseCase($repo);

        // User location: 16.0538, 103.6520
        // Pinned location: ~7,500 meters away (16.1200, 103.6520)
        $result = $useCase->execute([
            'latitude' => 16.1200,
            'longitude' => 103.6520,
            'user_latitude' => 16.0538,
            'user_longitude' => 103.6520,
            'max_distance_meters' => 5000.0,
            'severity' => 3,
            'description' => 'น้ำท่วมสูงมากเกิน 5 กม.',
        ]);

        $this->assertFalse($result->isSuccess);
        $this->assertStringContainsString('จุดที่ปักหมุดอยู่นอกรัศมีที่กำหนด', (string)$result->error);
    }
}
