<?php

declare(strict_types=1);

$url = $argv[1] ?? '';
$key = $argv[2] ?? '';

if (empty($url) || empty($key)) {
    echo "Usage: php bin/configure_supabase.php <SUPABASE_URL> <SUPABASE_ANON_KEY>\n";
    exit(1);
}

$envPath = __DIR__ . '/../.env';
$content = file_exists($envPath) ? file_get_contents($envPath) : '';

if (preg_match('/^SUPABASE_URL=/m', $content)) {
    $content = preg_replace('/^SUPABASE_URL=.*$/m', 'SUPABASE_URL=' . trim($url), $content);
} else {
    $content .= "\nSUPABASE_URL=" . trim($url);
}

if (preg_match('/^SUPABASE_ANON_KEY=/m', $content)) {
    $content = preg_replace('/^SUPABASE_ANON_KEY=.*$/m', 'SUPABASE_ANON_KEY=' . trim($key), $content);
} else {
    $content .= "\nSUPABASE_ANON_KEY=" . trim($key);
}

file_put_contents($envPath, $content);
echo "✅ Successfully updated .env with Supabase credentials!\n";
