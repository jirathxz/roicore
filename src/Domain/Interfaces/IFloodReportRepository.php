<?php

declare(strict_types=1);

namespace RoiCore\Domain\Interfaces;

use RoiCore\Domain\Entities\FloodReport;

interface IFloodReportRepository
{
    public function save(FloodReport $report): FloodReport;

    public function findById(string $id): ?FloodReport;

    /**
     * @return FloodReport[]
     */
    public function listAll(): array;

    /**
     * @return FloodReport[]
     */
    public function listPending(): array;
}
