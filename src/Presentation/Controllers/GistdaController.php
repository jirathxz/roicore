<?php

declare(strict_types=1);

namespace RoiCore\Presentation\Controllers;

use RoiCore\Data\Services\GistdaDisasterService;
use RoiCore\Presentation\Http\Request;
use RoiCore\Presentation\Http\Response;

final class GistdaController
{
    public function __construct(
        private readonly GistdaDisasterService $gistdaService
    ) {
    }

    public function show(Request $request): Response
    {
        $isOffline = $this->gistdaService->isOffline();
        $status = $isOffline ? 'OFFLINE' : 'LIVE';

        // ?all=1 หรือ ?scope=national → ดึงข้อมูลระดับประเทศทุกจังหวัด
        $allProvinces = filter_var(
            $request->queryParams['all'] ?? $request->queryParams['national'] ?? false,
            FILTER_VALIDATE_BOOLEAN
        );
        $scope = (string) ($request->queryParams['scope'] ?? 'province');

        if ($allProvinces || $scope === 'national') {
            $data = $this->gistdaService->getAllProvincesFloodData();
            $message = $isOffline
                ? 'ดึงข้อมูลภาพถ่ายดาวเทียมระดับประเทศ GISTDA Disaster Platform (สถานะ: ออฟไลน์ จำลองข้อมูล)'
                : 'ดึงข้อมูลภาพถ่ายดาวเทียมพื้นที่น้ำท่วมทุกจังหวัดจาก GISTDA Disaster Open API สำเร็จ';
        } else {
            $province = (string) ($request->queryParams['province'] ?? 'ร้อยเอ็ด');
            $data = $this->gistdaService->getFloodObservation($province);
            $message = $isOffline
                ? 'ดึงข้อมูลภาพถ่ายดาวเทียมพื้นที่น้ำท่วมจาก GISTDA Disaster Platform (สถานะ: ออฟไลน์ จำลองข้อมูล)'
                : 'ดึงข้อมูลภาพถ่ายดาวเทียมพื้นที่น้ำท่วมจาก GISTDA Disaster Open API สำเร็จ';
        }

        return Response::json([
            'success' => true,
            'status' => $status,
            'is_offline' => $isOffline,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
