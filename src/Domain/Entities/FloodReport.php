<?php

declare(strict_types=1);

namespace RoiCore\Domain\Entities;

use DateTimeImmutable;
use RoiCore\Domain\Enums\ReportStatus;
use RoiCore\Domain\Enums\Severity;
use RoiCore\Domain\ValueObjects\GeoPoint;

final class FloodReport
{
    /**
     * @param string[] $photos
     */
    public function __construct(
        public readonly string $id,
        public readonly GeoPoint $location,
        public readonly Severity $severity,
        public readonly string $description,
        public readonly ?string $phone = null,
        public ReportStatus $status = ReportStatus::PENDING,
        public readonly DateTimeImmutable $reportedAt = new DateTimeImmutable(),
        public ?DateTimeImmutable $verifiedAt = null,
        public array $photos = []
    ) {
    }

    public function verify(): void
    {
        $this->status = ReportStatus::VERIFIED;
        $this->verifiedAt = new DateTimeImmutable();
    }

    public function reject(): void
    {
        $this->status = ReportStatus::REJECTED;
    }

    public function resolve(): void
    {
        $this->status = ReportStatus::RESOLVED;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'location' => $this->location->toArray(),
            'severity' => $this->severity->value,
            'severity_label' => $this->severity->labelThai(),
            'description' => $this->description,
            'phone' => $this->phone ? substr($this->phone, 0, 3) . 'XXXX' . substr($this->phone, -3) : null,
            'status' => $this->status->value,
            'status_label' => $this->status->labelThai(),
            'reported_at' => $this->reportedAt->format(DateTimeImmutable::ATOM),
            'verified_at' => $this->verifiedAt?->format(DateTimeImmutable::ATOM),
            'photos' => $this->photos,
        ];
    }
}
