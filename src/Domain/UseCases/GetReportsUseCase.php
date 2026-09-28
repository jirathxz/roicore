<?php

declare(strict_types=1);

namespace RoiCore\Domain\UseCases;

use RoiCore\Core\Result;
use RoiCore\Domain\Interfaces\IFloodReportRepository;

final class GetReportsUseCase
{
    public function __construct(
        private readonly IFloodReportRepository $reportRepository
    ) {
    }

    public function execute(?string $status = null): Result
    {
        $reports = $this->reportRepository->listAll();

        if ($status !== null && $status !== '') {
            $reports = array_values(array_filter(
                $reports,
                fn ($r) => $r->status->value === strtoupper($status)
            ));
        }

        return Result::ok($reports);
    }
}
