<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total logs statistics
        $totalLogs = ServerLog::count();
        $criticalLogs = ServerLog::whereIn('severity', ['critical', 'emergency'])->count();
        $warningLogs = ServerLog::where('severity', 'warning')->count();
        
        // Logs by classification (last 7 days)
        $logsByClassification = ServerLog::where('created_at', '>=', now()->subDays(7))
            ->select('classification', DB::raw('count(*) as total'))
            ->groupBy('classification')
            ->get();

        // Logs by severity (last 30 days for trend)
        $logsBySeverity = ServerLog::where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw("DATE(created_at) as date"),
                'severity',
                DB::raw('count(*) as total')
            )
            ->groupBy('date', 'severity')
            ->orderBy('date')
            ->get();

        // Recent critical logs
        $recentCritical = ServerLog::whereIn('severity', ['critical', 'emergency'])
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Top action types
        $topActions = ServerLog::where('created_at', '>=', now()->subDays(7))
            ->select('action_type', DB::raw('count(*) as total'))
            ->groupBy('action_type')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Hourly log distribution (last 24 hours)
        $hourlyDistribution = ServerLog::where('created_at', '>=', now()->subHours(24))
            ->select(
                DB::raw("EXTRACT(HOUR FROM created_at) as hour"),
                DB::raw('count(*) as total')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('admin.dashboard', compact(
            'totalLogs', 'criticalLogs', 'warningLogs',
            'logsByClassification', 'logsBySeverity', 'recentCritical',
            'topActions', 'hourlyDistribution'
        ));
    }

    public function realtimeStats(Request $request)
    {
        $lastMinute = now()->subMinute();
        
        return response()->json([
            'logs_last_minute' => ServerLog::where('created_at', '>=', $lastMinute)->count(),
            'critical_last_hour' => ServerLog::where('created_at', '>=', now()->subHour())
                ->whereIn('severity', ['critical', 'emergency'])
                ->count(),
            'unique_ips' => ServerLog::where('created_at', '>=', now()->subMinutes(5))
                ->distinct('source_ip')
                ->count('source_ip')
        ]);
    }
}