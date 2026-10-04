<?php

declare(strict_types=1);

namespace RoiCore\Core;

use RoiCore\Data\Repositories\JsonFileFloodPointRepository;
use RoiCore\Data\Repositories\JsonFileFloodReportRepository;
use RoiCore\Data\Repositories\SupabaseFloodPointRepository;
use RoiCore\Data\Repositories\SupabaseFloodReportRepository;
use RoiCore\Data\Services\GistdaDisasterService;
use RoiCore\Data\Services\OpenWeatherService;
use RoiCore\Data\Services\SupabaseClient;
use RoiCore\Domain\Interfaces\IFloodPointRepository;
use RoiCore\Domain\Interfaces\IFloodReportRepository;
use RoiCore\Domain\Interfaces\IWeatherService;
use RoiCore\Domain\Strategies\CompositeRiskStrategy;
use RoiCore\Domain\Strategies\RainfallRiskStrategy;
use RoiCore\Domain\Strategies\WaterLevelStrategy;
use RoiCore\Domain\UseCases\CheckFloodPointsUseCase;
use RoiCore\Domain\UseCases\GetReportsUseCase;
use RoiCore\Domain\UseCases\GetWeatherUseCase;
use RoiCore\Domain\UseCases\SubmitReportUseCase;
use RoiCore\Presentation\Controllers\FloodPointController;
use RoiCore\Presentation\Controllers\GistdaController;
use RoiCore\Presentation\Controllers\ReportController;
use RoiCore\Presentation\Controllers\WeatherController;
use RoiCore\Presentation\Routing\ApiRouter;

final class AppContainer
{
    private static ?self $instance = null;

    public readonly IFloodReportRepository $reportRepository;
    public readonly IFloodPointRepository $floodPointRepository;
    public readonly IWeatherService $weatherService;
    public readonly GistdaDisasterService $gistdaService;
    public readonly CompositeRiskStrategy $riskStrategy;
    public readonly ApiRouter $apiRouter;

    public function __construct()
    {
        // 1. Data Layer Configuration
        $dbDriver = strtolower($_ENV['DB_DRIVER'] ?? ($_SERVER['DB_DRIVER'] ?? (getenv('DB_DRIVER') ?: 'supabase')));
        $supabaseUrl = $_ENV['SUPABASE_URL'] ?? ($_SERVER['SUPABASE_URL'] ?? (getenv('SUPABASE_URL') ?: 'http://127.0.0.1:54321'));
        $supabaseKey = $_ENV['SUPABASE_ANON_KEY'] ?? ($_SERVER['SUPABASE_ANON_KEY'] ?? (getenv('SUPABASE_ANON_KEY') ?: ''));

        $jsonReportRepo = new JsonFileFloodReportRepository();
        $jsonPointRepo = new JsonFileFloodPointRepository();

        if ($dbDriver === 'supabase' && !empty($supabaseKey)) {
            $supabaseClient = new SupabaseClient($supabaseUrl, $supabaseKey);
            $this->reportRepository = new SupabaseFloodReportRepository($supabaseClient, $jsonReportRepo);
            $this->floodPointRepository = new SupabaseFloodPointRepository($supabaseClient, $jsonPointRepo);
        } else {
            $this->reportRepository = $jsonReportRepo;
            $this->floodPointRepository = $jsonPointRepo;
        }

        $this->weatherService = new OpenWeatherService();
        $this->gistdaService = new GistdaDisasterService();

        // 2. Strategies (Composite Pattern)
        $this->riskStrategy = new CompositeRiskStrategy();
        $this->riskStrategy->addStrategy(new WaterLevelStrategy(), 1.5);
        $this->riskStrategy->addStrategy(new RainfallRiskStrategy(), 1.0);

        // 3. Domain Use Cases
        $submitReportUseCase = new SubmitReportUseCase($this->reportRepository);
        $getReportsUseCase = new GetReportsUseCase($this->reportRepository);
        $checkFloodPointsUseCase = new CheckFloodPointsUseCase(
            $this->floodPointRepository,
            $this->riskStrategy,
            $this->weatherService
        );
        $getWeatherUseCase = new GetWeatherUseCase($this->weatherService);

        // 4. Presentation Controllers
        $reportController = new ReportController($submitReportUseCase, $getReportsUseCase, $this->reportRepository);
        $floodPointController = new FloodPointController($checkFloodPointsUseCase, $this->floodPointRepository);
        $weatherController = new WeatherController($getWeatherUseCase, $this->weatherService);
        $gistdaController = new GistdaController($this->gistdaService);
        $systemController = new \RoiCore\Presentation\Controllers\SystemController($this->floodPointRepository, $this->reportRepository);

        // 5. Router
        $this->apiRouter = new ApiRouter($reportController, $floodPointController, $weatherController, $gistdaController, $systemController);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
