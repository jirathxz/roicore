<?php

declare(strict_types=1);

namespace RoiCore\Presentation\Routing;

use RoiCore\Presentation\Controllers\FloodPointController;
use RoiCore\Presentation\Controllers\ReportController;
use RoiCore\Presentation\Controllers\WeatherController;
use RoiCore\Presentation\Http\Request;
use RoiCore\Presentation\Http\Response;

final class ApiRouter
{
    public function __construct(
        private readonly ReportController $reportController,
        private readonly FloodPointController $floodPointController,
        private readonly WeatherController $weatherController
    ) {
    }

    public function dispatch(Request $request): ?Response
    {
        $path = $this->normalizePath($request);
        $method = $request->method;

        // Routing table
        if ($path === '/api/reports' || $path === 'api/reports') {
            if ($method === 'GET') {
                return $this->reportController->index($request);
            }
            if ($method === 'POST') {
                return $this->reportController->store($request);
            }
            return Response::error('Method Not Allowed', 405);
        }

        if ($path === '/api/points' || $path === 'api/points') {
            if ($method === 'GET') {
                return $this->floodPointController->index($request);
            }
            return Response::error('Method Not Allowed', 405);
        }

        if ($path === '/api/weather' || $path === 'api/weather') {
            if ($method === 'GET') {
                return $this->weatherController->show($request);
            }
            return Response::error('Method Not Allowed', 405);
        }

        // Return null if not an API route
        if (str_starts_with($path, '/api') || str_starts_with($path, 'api') || isset($request->queryParams['api'])) {
            return Response::error('ไม่พบเส้นทาง API ที่ร้องขอ', 404);
        }

        return null;
    }

    private function normalizePath(Request $request): string
    {
        // Support query param fallback: ?api=reports
        if (isset($request->queryParams['api'])) {
            return '/api/' . ltrim((string) $request->queryParams['api'], '/');
        }

        $path = $request->path;

        // If running in subfolder like /roicore/public/api/...
        if (preg_match('#/(api/[a-zA-Z0-9_\-/]+)#', $path, $matches)) {
            return '/' . $matches[1];
        }

        return $path;
    }
}
