<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\ServerLog;

echo "==========================================\n";
echo "WINDOWS AGENT - LOG COLLECTION SUMMARY\n";
echo "==========================================\n\n";

echo "System Date/Time: " . date('Y-m-d H:i:s') . "\n";
echo "Agent Token: " . (env('LOG_AGENT_TOKEN') ? '✓ Configured' : '✗ Missing') . "\n";
echo "API URL: " . env('GOVLOG_API_URL') . "\n\n";

echo "LOGS RECEIVED FROM WINDOWS AGENT:\n";
echo "-----------------------------------------\n";

try {
    $logs = ServerLog::where('source', 'like', '%windows%')
        ->orWhere('action_type', 'like', '%event%')
        ->orWhere('created_at', '>=', now()->subHours(2))
        ->latest()
        ->limit(10)
        ->get();
    
    if ($logs->count() === 0) {
        echo "No Windows agent logs found yet.\n";
        echo "\nShowing latest 10 logs from any source:\n";
        $logs = ServerLog::latest()->limit(10)->get();
    }
    
    foreach ($logs as $log) {
        echo "\n📋 Log ID: {$log->id}\n";
        echo "   Time: " . $log->created_at->format('Y-m-d H:i:s') . "\n";
        echo "   Type: {$log->action_type}\n";
        echo "   Severity: {$log->severity}\n";
        echo "   Classification: {$log->classification}\n";
        echo "   Message: " . substr($log->message, 0, 100) . (strlen($log->message) > 100 ? '...' : '') . "\n";
        echo "   Source: " . ($log->source ?? 'Unknown') . "\n";
    }
    
    echo "\n\nTOTAL LOGS IN DATABASE: " . ServerLog::count() . "\n";
    echo "Logs from last 24 hours: " . ServerLog::where('created_at', '>=', now()->subHours(24))->count() . "\n";
    echo "Logs from last 2 hours: " . ServerLog::where('created_at', '>=', now()->subHours(2))->count() . "\n";
    
} catch (Exception $e) {
    echo "Error reading logs: " . $e->getMessage() . "\n";
}

echo "\n==========================================\n";
?>
