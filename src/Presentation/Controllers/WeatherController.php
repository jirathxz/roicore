<?php

declare(strict_types=1);

namespace RoiCore\Presentation\Controllers;

use RoiCore\Data\Services\OpenWeatherService;
use RoiCore\Domain\Interfaces\IWeatherService;
use RoiCore\Domain\UseCases\GetWeatherUseCase;
use RoiCore\Domain\ValueObjects\WeatherData;
use RoiCore\Presentation\Http\Request;
use RoiCore\Presentation\Http\Response;

final class WeatherController
{
    public function __construct(
        private readonly GetWeatherUseCase $getWeatherUseCase,
        private readonly ?IWeatherService $weatherService = null
    ) {
    }

    public function show(Request $request): Response
    {
        $lat = isset($request->queryParams['lat']) ? (float) $request->queryParams['lat'] : null;
        $lng = isset($request->queryParams['lng']) ? (float) $request->queryParams['lng'] : null;

        if ($lat === null || $lng === null) {
            return Response::error('กรุณาระบุพิกัดละติจูดและลองจิจูด');
        }

        $result = $this->getWeatherUseCase->execute($lat, $lng);

        if ($result->isFailure()) {
            return Response::error($result->error ?? 'ไม่สามารถดึงข้อมูลสภาพอากาศได้');
        }

        /** @var WeatherData $weather */
        $weather = $result->value;
        $isOffline = ($this->weatherService instanceof OpenWeatherService)
            ? $this->weatherService->isOffline()
            : true;
        $statusStr = $isOffline ? 'OFFLINE' : 'ONLINE';
        $statusMsg = $isOffline
            ? 'ดึงข้อมูลสภาพอากาศและฝนเรียบร้อยแล้ว (สถานะ: ออฟไลน์)'
            : 'ดึงข้อมูลสภาพอากาศและฝนเรียบร้อยแล้ว';

        return Response::success($weather->toArray(), $statusMsg, 200, $statusStr, $isOffline);
    }
}
