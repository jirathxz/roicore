<?php

declare(strict_types=1);

namespace RoiCore\Data\Repositories;

use DateTimeImmutable;
use Exception;
use RoiCore\Data\Services\SupabaseClient;
use RoiCore\Domain\Entities\FloodReport;
use RoiCore\Domain\Enums\ReportStatus;
use RoiCore\Domain\Enums\Severity;
use RoiCore\Domain\Interfaces\IFloodReportRepository;
use RoiCore\Domain\ValueObjects\GeoPoint;

final class SupabaseFloodReportRepository implements IFloodReportRepository
{
    private bool $isFallbackActive = false;

    public function __construct(
        private readonly SupabaseClient $client,
        private readonly ?IFloodReportRepository $fallbackRepository = null
    ) {
    }

    public function isOffline(): bool
    {
        return $this->isFallbackActive || !$this->client->isConfigured();
    }

    public function save(FloodReport $report): FloodReport
    {
        try {
            $payload = [
                'id' => $report->id,
                'latitude' => $report->location->latitude,
                'longitude' => $report->location->longitude,
                'severity' => $report->severity->value,
                'description' => $report->description,
                'phone' => $report->phone,
                'status' => $report->status->value,
                'reported_at' => $report->reportedAt->format(DateTimeImmutable::ATOM),
                'verified_at' => $report->verifiedAt?->format(DateTimeImmutable::ATOM),
            ];

            $this->client->insert('flood_reports', $payload);
            $this->fallbackRepository?->save($report);
            return $report;
        } catch (Exception $e) {
            if ($this->fallbackRepository !== null) {
                $this->isFallbackActive = true;
                return $this->fallbackRepository->save($report);
            }
            throw $e;
        }
    }

    public function findById(string $id): ?FloodReport
    {
        try {
            $rows = $this->client->select('flood_reports', [
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

    public function listAll(): array
    {
        try {
            $rows = $this->client->select('flood_reports', [
                'select' => '*',
                'order' => 'reported_at.desc',
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

    public function listPending(): array
    {
        try {
            $rows = $this->client->select('flood_reports', [
                'status' => 'eq.PENDING',
                'select' => '*',
                'order' => 'reported_at.desc',
            ]);

            return array_map([$this, 'mapRowToEntity'], $rows);
        } catch (Exception $e) {
            if ($this->fallbackRepository !== null) {
                $this->isFallbackActive = true;
                return $this->fallbackRepository->listPending();
            }
            throw $e;
        }
    }

    /**
     * @param array<string, mixed> $row
     */
    private function mapRowToEntity(array $row): FloodReport
    {
        return new FloodReport(
            id: (string) ($row['id'] ?? ''),
            location: new GeoPoint((float) ($row['latitude'] ?? 0.0), (float) ($row['longitude'] ?? 0.0)),
            severity: Severity::from((int) ($row['severity'] ?? 1)),
            description: (string) ($row['description'] ?? ''),
            phone: isset($row['phone']) ? (string) $row['phone'] : null,
            status: ReportStatus::from((string) ($row['status'] ?? 'PENDING')),
            reportedAt: isset($row['reported_at']) ? new DateTimeImmutable((string) $row['reported_at']) : new DateTimeImmutable(),
            verifiedAt: !empty($row['verified_at']) ? new DateTimeImmutable((string) $row['verified_at']) : null,
            photos: $row['photos'] ?? []
        );
    }
}
