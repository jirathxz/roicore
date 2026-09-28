<?php

declare(strict_types=1);

namespace RoiCore\Core;

use RoiCore\Data\Repositories\JsonFileFloodPointRepository;
use RoiCore\Data\Repositories\JsonFileFloodReportRepository;
use RoiCore\Data\Services\OpenWeatherService;
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
use RoiCore\Presentation\Controllers\ReportController;
use RoiCore\Presentation\Controllers\WeatherController;
use RoiCore\Presentation\Routing\ApiRouter;

final class AppContainer
{
    private static ?self $instance = null;

    public readonly IFloodReportRepository $reportRepository;
    public readonly IFloodPointRepository $floodPointRepository;
    public readonly IWeatherService $weatherService;
    public readonly CompositeRiskStrategy $riskStrategy;
    public readonly ApiRouter $apiRouter;

    public function __construct()
    {
        // 1. Data Layer
        $this->reportRepository = new JsonFileFloodReportRepository();
        $this->floodPointRepository = new JsonFileFloodPointRepository();
        $this->weatherService = new OpenWeatherService();

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
        $reportController = new ReportController($submitReportUseCase, $getReportsUseCase);
        $floodPointController = new FloodPointController($checkFloodPointsUseCase);
        $weatherController = new WeatherController($getWeatherUseCase);

        // 5. Router
        $this->apiRouter = new ApiRouter($reportController, $floodPointController, $weatherController);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
