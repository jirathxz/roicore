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

    public function isOffline(): bool
    {
        return true;
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
        if (!is_array($data) || empty($data)) {
            $this->seedInitialReports();
            return;
        }

        $this->reports = [];
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

    public function seedInitialReports(): void
    {
        $this->reports = [];

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
            location: new GeoPoint(16.0350, 103.7890),
            severity: Severity::MEDIUM,
            description: 'มีน้ำท่วมขังรอการระบายบริเวณตลาดสดเสลภูมิ ระดับ 15 เซนติเมตร',
            phone: '0898765432',
            status: ReportStatus::PENDING,
            reportedAt: new DateTimeImmutable('-40 minutes')
        );

        $sample3 = new FloodReport(
            id: 'rep_103',
            location: new GeoPoint(16.3015, 103.9850),
            severity: Severity::HIGH,
            description: 'ระดับน้ำในลำน้ำยังเอ่อท่วมถนนสายรองเข้าหมู่บ้าน รถจักรยานยนต์ผ่านไม่ได้',
            phone: '0861112233',
            status: ReportStatus::VERIFIED,
            reportedAt: new DateTimeImmutable('-1 hour')
        );

        $sample4 = new FloodReport(
            id: 'rep_104',
            location: new GeoPoint(16.0680, 103.6850),
            severity: Severity::LOW,
            description: 'น้ำท่วมขังผิวจราจรฝั่งซ้ายทางเลี่ยงเมืองร้อยเอ็ด รอการระบาย',
            phone: '0879998877',
            status: ReportStatus::RESOLVED,
            reportedAt: new DateTimeImmutable('-3 hours')
        );

        $this->save($sample1);
        $this->save($sample2);
        $this->save($sample3);
        $this->save($sample4);
    }
}
