<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use RoiCore\Core\AppContainer;
use RoiCore\Presentation\Http\Request;

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

$container = AppContainer::getInstance();
$request = Request::fromGlobals();

// 1. Handle JSON API requests (e.g. /api/points, /api/reports, /api/weather, or ?api=points)
$response = $container->apiRouter->dispatch($request);
if ($response !== null) {
    $response->send();
    exit;
}

// 2. Check if user is navigating to specific web views: /dashboard, /api, /map
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$pageParam = $request->queryParams['page'] ?? ($request->queryParams['view'] ?? '');

$isApiConsole = (
    preg_match('#/(api/?)$#', $path) === 1
    || $pageParam === 'api'
);

if ($isApiConsole) {
    require __DIR__ . '/api_console.php';
    exit;
}

$isDashboard = (
    preg_match('#/(dashboard/?)$#', $path) === 1
    || $pageParam === 'dashboard'
);

if ($isDashboard) {
    require __DIR__ . '/dashboard_view.php';
    exit;
}

$isPresentation = (
    preg_match('#/(presentation|slides?/?)$#', $path) === 1
    || in_array($pageParam, ['presentation', 'slide', 'slides'], true)
);

if ($isPresentation) {
    require __DIR__ . '/presentation.html';
    exit;
}

// 3. Main landing page: OpenStreetMap Interactive Flood Map
require __DIR__ . '/map_view.php';
