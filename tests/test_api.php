<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use RoiCore\Core\AppContainer;
use RoiCore\Presentation\Http\Request;

$container = AppContainer::getInstance();

echo "=== 1. Test GET /api/points ===\n";
$req1 = new Request('GET', '/api/points', [], [], []);
$res1 = $container->apiRouter->dispatch($req1);
echo json_encode($res1->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

echo "=== 2. Test GET /api/weather ===\n";
$req2 = new Request('GET', '/api/weather', ['lat' => 16.0538, 'lng' => 103.6520], [], []);
$res2 = $container->apiRouter->dispatch($req2);
echo json_encode($res2->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

echo "=== 3. Test POST /api/reports ===\n";
$req3 = new Request('POST', '/api/reports', [], [
    'latitude' => 16.0538,
    'longitude' => 103.6520,
    'severity' => 3,
    'description' => 'ทดสอบส่งรายงานน้ำท่วมจากผู้ประสบภัย',
    'phone' => '0812345678',
], []);
$res3 = $container->apiRouter->dispatch($req3);
echo json_encode($res3->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

echo "=== 4. Test GET /api/reports ===\n";
$req4 = new Request('GET', '/api/reports', [], [], []);
$res4 = $container->apiRouter->dispatch($req4);
echo json_encode($res4->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
