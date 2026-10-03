<?php

declare(strict_types=1);

namespace RoiCore\Presentation\Controllers;

use RoiCore\Domain\Entities\FloodReport;
use RoiCore\Domain\Interfaces\IFloodReportRepository;
use RoiCore\Domain\UseCases\GetReportsUseCase;
use RoiCore\Domain\UseCases\SubmitReportUseCase;
use RoiCore\Presentation\Http\Request;
use RoiCore\Presentation\Http\Response;

final class ReportController
{
    public function __construct(
        private readonly SubmitReportUseCase $submitReportUseCase,
        private readonly GetReportsUseCase $getReportsUseCase,
        private readonly ?IFloodReportRepository $repository = null
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
        $isOffline = $this->repository?->isOffline() ?? true;
        $statusStr = $isOffline ? 'OFFLINE' : 'ONLINE';
        $statusMsg = $isOffline
            ? 'ดึงข้อมูลรายการรายงานเรียบร้อยแล้ว (สถานะ: ออฟไลน์)'
            : 'ดึงข้อมูลรายการรายงานเรียบร้อยแล้ว';

        return Response::success($formatted, $statusMsg, 200, $statusStr, $isOffline);
    }

    public function store(Request $request): Response
    {
        $lat = (float) $request->get('latitude', 0);
        $lng = (float) $request->get('longitude', 0);
        if ($lat === 0.0 && $lng === 0.0) {
            $lat = 16.0538;
            $lng = 103.6520;
        }

        $severityVal = (int) $request->get('severity', 3);
        if ($severityVal < 1 || $severityVal > 3) {
            $severityVal = 3;
        }

        $description = trim((string) $request->get('description', ''));
        if ($description === '') {
            $description = match ($severityVal) {
                3 => 'น้ำท่วมระดับวิกฤต (ท่วมสูง รถเล็กผ่านไม่ได้)',
                2 => 'น้ำท่วมระดับปานกลาง (ท่วมสูง 10-30 ซม.)',
                default => 'น้ำท่วมขังผิวทาง (รอการระบาย)',
            };
        }

        $input = [
            'latitude' => $lat,
            'longitude' => $lng,
            'severity' => $severityVal,
            'description' => $description,
            'phone' => $request->get('phone'),
            'photos' => (array) $request->get('photos', []),
        ];

        $result = $this->submitReportUseCase->execute($input);

        if ($result->isFailure()) {
            return Response::error($result->error ?? 'การส่งรายงานไม่ถูกต้อง');
        }

        /** @var FloodReport $report */
        $report = $result->value;
        $isOffline = $this->repository?->isOffline() ?? true;
        $statusStr = $isOffline ? 'OFFLINE' : 'ONLINE';
        $statusMsg = $isOffline
            ? 'บันทึกรายงานสถานการณ์น้ำท่วมในเครื่องเรียบร้อยแล้ว (สถานะ: ออฟไลน์)'
            : 'บันทึกรายงานสถานการณ์น้ำท่วมเรียบร้อยแล้ว';

        return Response::success($report->toArray(), $statusMsg, 201, $statusStr, $isOffline);
    }
}
