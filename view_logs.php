<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\ServerLog;

echo "═══════════════════════════════════════════════════════════════\n";
echo "WINDOWS AGENT - LIVE LOGS RECEIVED\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$total = ServerLog::count();
echo "Total Logs in Database: $total\n\n";

echo "LATEST 10 LOGS RECEIVED:\n";
echo "───────────────────────────────────────────────────────────────\n";

$logs = ServerLog::latest()->limit(10)->get();

foreach ($logs as $index => $log) {
    echo ($index + 1) . ". ID: {$log->id}\n";
    echo "   Time: {$log->created_at}\n";
    echo "   Type: {$log->action_type}\n";
    echo "   Severity: {$log->severity}\n";
    echo "   Classification: {$log->classification}\n";
    echo "   Source: " . ($log->source ?? 'Unknown') . "\n";
    echo "   Message: " . substr($log->message, 0, 80) . (strlen($log->message) > 80 ? '...' : '') . "\n\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
?>
