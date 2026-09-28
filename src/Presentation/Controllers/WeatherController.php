<?php

declare(strict_types=1);

namespace RoiCore\Presentation\Controllers;

use RoiCore\Domain\UseCases\GetWeatherUseCase;
use RoiCore\Domain\ValueObjects\WeatherData;
use RoiCore\Presentation\Http\Request;
use RoiCore\Presentation\Http\Response;

final class WeatherController
{
    public function __construct(
        private readonly GetWeatherUseCase $getWeatherUseCase
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

        return Response::success($weather->toArray(), 'ดึงข้อมูลสภาพอากาศและฝนเรียบร้อยแล้ว');
    }
}
