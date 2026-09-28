<?php

declare(strict_types=1);

namespace RoiCore\Presentation\Controllers;

use RoiCore\Domain\Entities\FloodReport;
use RoiCore\Domain\UseCases\GetReportsUseCase;
use RoiCore\Domain\UseCases\SubmitReportUseCase;
use RoiCore\Presentation\Http\Request;
use RoiCore\Presentation\Http\Response;

final class ReportController
{
    public function __construct(
        private readonly SubmitReportUseCase $submitReportUseCase,
        private readonly GetReportsUseCase $getReportsUseCase
    ) {
    }

    public function index(Request $request): Response
    {
        $status = $request->queryParams['status'] ?? null;
        $result = $this->getReportsUseCase->execute($status ? (string)$status : null);

        if ($result->isFailure()) {
            return Response::error($result->error ?? 'ไม่สามารถดึงข้อมูลรายงานได้');
        }

        $reports = $result->value;
        $formatted = array_map(fn (FloodReport $r) => $r->toArray(), $reports);

        return Response::success($formatted, 'ดึงข้อมูลรายการรายงานเรียบร้อยแล้ว');
    }

    public function store(Request $request): Response
    {
        $input = [
            'latitude' => (float) $request->get('latitude', 0),
            'longitude' => (float) $request->get('longitude', 0),
            'severity' => (int) $request->get('severity', 1),
            'description' => (string) $request->get('description', ''),
            'phone' => $request->get('phone'),
            'photos' => (array) $request->get('photos', []),
        ];

        $result = $this->submitReportUseCase->execute($input);

        if ($result->isFailure()) {
            return Response::error($result->error ?? 'การส่งรายงานไม่ถูกต้อง');
        }

        /** @var FloodReport $report */
        $report = $result->value;

        return Response::success($report->toArray(), 'บันทึกรายงานสถานการณ์น้ำท่วมเรียบร้อยแล้ว', 201);
    }
}
