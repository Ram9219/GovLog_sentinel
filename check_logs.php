<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\ServerLog;

$latest = ServerLog::latest()->first();
echo "Latest Log: " . ($latest ? $latest->id . " | " . $latest->action_type . " | " . $latest->created_at : "No logs") . PHP_EOL;

$total = ServerLog::count();
echo "Total Logs in DB: $total" . PHP_EOL;

// Show last 5 logs
echo "\nLast 5 logs:" . PHP_EOL;
$recent = ServerLog::latest()->limit(5)->get();
foreach ($recent as $log) {
    echo "  - ID: {$log->id} | Type: {$log->action_type} | Created: {$log->created_at}" . PHP_EOL;
}
?>
