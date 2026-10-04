<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use RoiCore\Data\Services\SupabaseClient;

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

$supabaseUrl = $_ENV['SUPABASE_URL'] ?? (getenv('SUPABASE_URL') ?: '');
$supabaseKey = $_ENV['SUPABASE_ANON_KEY'] ?? (getenv('SUPABASE_ANON_KEY') ?: '');
$serviceRoleKey = $_ENV['SUPABASE_SERVICE_ROLE_KEY'] ?? (getenv('SUPABASE_SERVICE_ROLE_KEY') ?: '');

echo "=================================================================\n";
echo "  ROiCORE — Supabase Database Management & Diagnostic Tool\n";
echo "=================================================================\n\n";

if (empty($supabaseUrl) || empty($supabaseKey)) {
    echo "⚠️  SUPABASE_URL or SUPABASE_ANON_KEY is not configured in .env\n";
    echo "   Please set SUPABASE_URL and SUPABASE_ANON_KEY in your .env or environment.\n\n";
    exit(1);
}

echo "1. Configuration:\n";
echo "   - URL: {$supabaseUrl}\n";
echo "   - Anon Key: " . substr($supabaseKey, 0, 12) . "... (length: " . strlen($supabaseKey) . ")\n\n";

echo "2. Testing Supabase REST Connectivity...\n";
$client = new Client(['timeout' => 5.0]);

try {
    $res = $client->get(rtrim($supabaseUrl, '/') . '/rest/v1/flood_points?select=id,title,risk_level&limit=5', [
        'headers' => [
            'apikey' => $supabaseKey,
            'Authorization' => "Bearer {$supabaseKey}",
        ],
    ]);
    
    $status = $res->getStatusCode();
    $data = json_decode((string) $res->getBody(), true);
    
    echo "   ✅ Connection Successful! (HTTP {$status})\n";
    echo "   📊 Found " . count($data) . " sample flood points in database.\n\n";
    
    foreach ($data as $idx => $pt) {
        echo "      [" . ($idx + 1) . "] {$pt['title']} (Risk: {$pt['risk_level']})\n";
    }
    echo "\n";
} catch (Throwable $e) {
    echo "   ❌ Connection failed or tables do not exist yet.\n";
    echo "   Error: " . $e->getMessage() . "\n\n";
    echo "   👉 If this is a new Supabase Cloud project, please run the SQL in:\n";
    echo "      supabase/cloud_setup.sql\n";
    echo "      in the Supabase Cloud SQL Editor (https://app.supabase.com).\n\n";
}

echo "3. Testing flood_reports table...\n";
try {
    $res = $client->get(rtrim($supabaseUrl, '/') . '/rest/v1/flood_reports?select=id,description,status,reported_at&limit=5', [
        'headers' => [
            'apikey' => $supabaseKey,
            'Authorization' => "Bearer {$supabaseKey}",
        ],
    ]);
    
    $data = json_decode((string) $res->getBody(), true);
    echo "   ✅ flood_reports table accessible! (Found " . count($data) . " reports)\n";
    foreach ($data as $idx => $rep) {
        echo "      [" . ($idx + 1) . "] Status: {$rep['status']} | {$rep['description']}\n";
    }
    echo "\n";
} catch (Throwable $e) {
    echo "   ❌ Failed to query flood_reports: " . $e->getMessage() . "\n\n";
}

echo "=================================================================\n";
echo "Diagnostic complete.\n";
