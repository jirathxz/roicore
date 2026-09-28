<?php

declare(strict_types=1);

namespace RoiCore\Domain\UseCases;

use InvalidArgumentException;
use RoiCore\Core\Result;
use RoiCore\Domain\Entities\FloodReport;
use RoiCore\Domain\Enums\Severity;
use RoiCore\Domain\Interfaces\IFloodReportRepository;
use RoiCore\Domain\ValueObjects\GeoPoint;

final class SubmitReportUseCase
{
    public function __construct(
        private readonly IFloodReportRepository $reportRepository
    ) {
    }

    /**
     * @param array{
     *     latitude: float,
     *     longitude: float,
     *     severity: int,
     *     description: string,
     *     phone?: string|null,
     *     photos?: string[]
     * } $input
     * @return Result
     */
    public function execute(array $input): Result
    {
        try {
            $lat = (float) ($input['latitude'] ?? 0);
            $lng = (float) ($input['longitude'] ?? 0);
            $geo = new GeoPoint($lat, $lng);

            $severityVal = (int) ($input['severity'] ?? 1);
            $severity = Severity::tryFrom($severityVal) ?? Severity::LOW;

            $description = trim((string) ($input['description'] ?? ''));
            if ($description === '') {
                return Result::fail('กรุณาระบุรายละเอียดสถานการณ์');
            }

            $phone = isset($input['phone']) && trim((string)$input['phone']) !== ''
                ? trim((string)$input['phone'])
                : null;

            $photos = (array) ($input['photos'] ?? []);

            $id = 'rep_' . bin2hex(random_bytes(6));

            $report = new FloodReport(
                id: $id,
                location: $geo,
                severity: $severity,
                description: $description,
                phone: $phone,
                photos: $photos
            );

            $saved = $this->reportRepository->save($report);

            return Result::ok($saved);
        } catch (InvalidArgumentException $e) {
            return Result::fail($e->getMessage());
        }
    }
}
