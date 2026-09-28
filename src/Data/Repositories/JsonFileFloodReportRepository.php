<?php

declare(strict_types=1);

namespace RoiCore\Data\Repositories;

use DateTimeImmutable;
use RoiCore\Domain\Entities\FloodReport;
use RoiCore\Domain\Enums\ReportStatus;
use RoiCore\Domain\Enums\Severity;
use RoiCore\Domain\Interfaces\IFloodReportRepository;
use RoiCore\Domain\ValueObjects\GeoPoint;

final class JsonFileFloodReportRepository implements IFloodReportRepository
{
    private string $filePath;

    /**
     * @var array<string, FloodReport>
     */
    private array $reports = [];

    public function __construct(?string $storageDir = null)
    {
        $dir = $storageDir ?? dirname(__DIR__, 3) . '/storage/data';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $this->filePath = $dir . '/flood_reports.json';
        $this->load();
    }

    public function save(FloodReport $report): FloodReport
    {
        $this->reports[$report->id] = $report;
        $this->persist();
        return $report;
    }

    public function findById(string $id): ?FloodReport
    {
        return $this->reports[$id] ?? null;
    }

    public function listAll(): array
    {
        return array_values($this->reports);
    }

    public function listPending(): array
    {
        return array_values(array_filter(
            $this->reports,
            fn (FloodReport $r) => $r->status === ReportStatus::PENDING
        ));
    }

    private function load(): void
    {
        if (!file_exists($this->filePath)) {
            $this->seedInitialReports();
            return;
        }

        $content = file_get_contents($this->filePath);
        if ($content === false || trim($content) === '') {
            $this->seedInitialReports();
            return;
        }

        $data = json_decode($content, true);
        if (!is_array($data)) {
            $this->seedInitialReports();
            return;
        }

        foreach ($data as $item) {
            $report = new FloodReport(
                id: (string) $item['id'],
                location: new GeoPoint((float) $item['location']['latitude'], (float) $item['location']['longitude']),
                severity: Severity::tryFrom((int) $item['severity']) ?? Severity::LOW,
                description: (string) $item['description'],
                phone: isset($item['phone']) ? (string) $item['phone'] : null,
                status: ReportStatus::tryFrom((string) $item['status']) ?? ReportStatus::PENDING,
                reportedAt: new DateTimeImmutable($item['reported_at'] ?? 'now'),
                verifiedAt: isset($item['verified_at']) ? new DateTimeImmutable($item['verified_at']) : null,
                photos: (array) ($item['photos'] ?? [])
            );

            $this->reports[$report->id] = $report;
        }
    }

    private function persist(): void
    {
        $data = [];
        foreach ($this->reports as $report) {
            $data[] = $report->toArray();
        }
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function seedInitialReports(): void
    {
        $sample1 = new FloodReport(
            id: 'rep_101',
            location: new GeoPoint(16.0538, 103.6520),
            severity: Severity::HIGH,
            description: 'น้ำท่วมเอ่อล้นตลิ่งแม่น้ำชี เอ่อท่วมถนนสายหลักระดับ 40 เซนติเมตร รถเล็กสัญจรลำบาก',
            phone: '0812345678',
            status: ReportStatus::VERIFIED,
            reportedAt: new DateTimeImmutable('-2 hours')
        );

        $sample2 = new FloodReport(
            id: 'rep_102',
            location: new GeoPoint(15.2287, 104.8564),
            severity: Severity::MEDIUM,
            description: 'ระดับน้ำในลำเซบกสูงขึ้นต่อเนื่อง ท่วมพื้นที่ลุ่มต่ำใกล้สะพาน',
            phone: '0898765432',
            status: ReportStatus::PENDING,
            reportedAt: new DateTimeImmutable('-40 minutes')
        );

        $this->save($sample1);
        $this->save($sample2);
    }
}
