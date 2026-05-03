<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServerLog;
use App\Services\LogIntegrityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = ServerLog::with('user');

        // Apply filters
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('classification')) {
            $query->where('classification', $request->classification);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('message', 'ilike', "%{$search}%")
                  ->orWhere('action_type', 'ilike', "%{$search}%")
                  ->orWhere('source_ip', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->orderBy('created_at', 'desc')
                      ->paginate(50)
                      ->withQueryString();

        // Get filter options
        $classifications = ServerLog::distinct()->pluck('classification');
        $severities = ['debug', 'info', 'warning', 'error', 'critical', 'emergency'];

        return view('admin.logs.index', compact('logs', 'classifications', 'severities'));
    }

    public function show(ServerLog $log)
    {
        // Get adjacent logs for context
        $previousLog = ServerLog::where('id', '<', $log->id)->latest()->first();
        $nextLog = ServerLog::where('id', '>', $log->id)->first();

        return view('admin.logs.show', compact('log', 'previousLog', 'nextLog'));
    }

    public function critical()
    {
        $logs = ServerLog::whereIn('severity', ['critical', 'emergency'])
            ->with('user')
            ->latest()
            ->paginate(50);

        return view('admin.logs.critical', compact('logs'));
    }

    public function export(Request $request)
    {
        $query = ServerLog::query();

        // Apply same filters as index
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        $filename = 'logs_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['ID', 'Timestamp', 'Source IP', 'User', 'Action', 'Severity', 'Classification', 'Message']);

            // Add data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at,
                    $log->source_ip,
                    $log->user?->email ?? 'System',
                    $log->action_type,
                    $log->severity,
                    $log->classification,
                    $log->message
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(ServerLog $log)
    {
        $log->delete();

        return redirect()->route('admin.logs.index')
            ->with('success', 'Log deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:server_logs,id'
        ]);

        ServerLog::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.logs.index')
            ->with('success', 'Logs deleted successfully.');
    }

    public function verifyIntegrity()
    {
        $result = LogIntegrityService::verifyChain();

        return view('admin.logs.integrity', compact('result'));
    }
}