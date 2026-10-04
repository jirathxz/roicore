<?php

declare(strict_types=1);

namespace RoiCore\Presentation\Controllers;

use GuzzleHttp\Client;
use RoiCore\Domain\Interfaces\IFloodPointRepository;
use RoiCore\Domain\Interfaces\IFloodReportRepository;
use RoiCore\Presentation\Http\Request;
use RoiCore\Presentation\Http\Response;
use Throwable;

final class SystemController
{
    public function __construct(
        private readonly IFloodPointRepository $floodPointRepository,
        private readonly IFloodReportRepository $reportRepository
    ) {
    }

    public function status(Request $request): Response
    {
        $dbDriver = strtolower($_ENV['DB_DRIVER'] ?? (getenv('DB_DRIVER') ?: 'supabase'));
        $supabaseUrl = $_ENV['SUPABASE_URL'] ?? (getenv('SUPABASE_URL') ?: '');
        $supabaseKey = $_ENV['SUPABASE_ANON_KEY'] ?? (getenv('SUPABASE_ANON_KEY') ?: '');
        
        $supabaseConnected = false;
        $supabaseError = null;

        if (!empty($supabaseUrl) && !empty($supabaseKey)) {
            try {
                $client = new Client(['timeout' => 3.0]);
                $res = $client->get(rtrim($supabaseUrl, '/') . '/rest/v1/flood_points?select=id&limit=1', [
                    'headers' => [
                        'apikey' => $supabaseKey,
                        'Authorization' => "Bearer {$supabaseKey}",
                    ],
                ]);
                $supabaseConnected = ($res->getStatusCode() === 200);
            } catch (Throwable $e) {
                $supabaseConnected = false;
                $supabaseError = $e->getMessage();
            }
        }

        try {
            $pointsCount = count($this->floodPointRepository->findAll());
        } catch (Throwable $e) {
            $pointsCount = 0;
        }

        try {
            $reportsCount = count($this->reportRepository->findAll());
        } catch (Throwable $e) {
            $reportsCount = 0;
        }

        return Response::json([
            'status' => 'ok',
            'app' => 'ROiCORE Flood Intelligence Platform',
            'version' => '1.0.0',
            'environment' => $_ENV['APP_ENV'] ?? (getenv('APP_ENV') ?: 'local'),
            'database' => [
                'driver' => $dbDriver,
                'supabase_configured' => (!empty($supabaseUrl) && !empty($supabaseKey)),
                'supabase_url' => !empty($supabaseUrl) ? preg_replace('/^(https?:\/\/[^\/]+).*$/', '$1', $supabaseUrl) : null,
                'supabase_connected' => $supabaseConnected,
                'supabase_error' => $supabaseError,
                'stats' => [
                    'flood_points' => $pointsCount,
                    'flood_reports' => $reportsCount,
                ],
            ],
            'timestamp' => date('c'),
        ]);
    }
}
