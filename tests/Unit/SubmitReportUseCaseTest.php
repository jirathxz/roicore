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
}
