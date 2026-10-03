<?php

declare(strict_types=1);

namespace RoiCore\Presentation\Controllers;

use RoiCore\Domain\Entities\FloodPoint;
use RoiCore\Domain\Interfaces\IFloodPointRepository;
use RoiCore\Domain\UseCases\CheckFloodPointsUseCase;
use RoiCore\Presentation\Http\Request;
use RoiCore\Presentation\Http\Response;

final class FloodPointController
{
    public function __construct(
        private readonly CheckFloodPointsUseCase $checkFloodPointsUseCase,
        private readonly ?IFloodPointRepository $repository = null
    ) {
    }

    public function index(Request $request): Response
    {
        $lat = isset($request->queryParams['lat']) ? (float) $request->queryParams['lat'] : null;
        $lng = isset($request->queryParams['lng']) ? (float) $request->queryParams['lng'] : null;
        $radius = isset($request->queryParams['radius']) ? (float) $request->queryParams['radius'] : 50000.0;

        $result = $this->checkFloodPointsUseCase->execute($lat, $lng, $radius);

        if ($result->isFailure()) {
            return Response::error($result->error ?? 'ไม่สามารถดึงข้อมูลจุดน้ำท่วมได้');
        }

        $points = $result->value;
        $formatted = array_map(fn (FloodPoint $p) => $p->toArray(), $points);
        $isOffline = $this->repository?->isOffline() ?? true;
        $statusStr = $isOffline ? 'OFFLINE' : 'ONLINE';
        $statusMsg = $isOffline
            ? 'ดึงข้อมูลจุดน้ำท่วมและความเสี่ยงเรียบร้อยแล้ว (สถานะ: ออฟไลน์)'
            : 'ดึงข้อมูลจุดน้ำท่วมและความเสี่ยงเรียบร้อยแล้ว';

        return Response::success($formatted, $statusMsg, 200, $statusStr, $isOffline);
    }
}
