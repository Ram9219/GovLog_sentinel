<?php

namespace App\Http\Controllers\Viewer;

use App\Http\Controllers\Controller;
use App\Models\ServerLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ServerLog::with('user')
            ->whereNotIn('severity', ['critical', 'emergency'])
            ->whereNotIn('classification', ['security_breach', 'data_breach']);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($builder) use ($search) {
                $builder->where('message', 'ilike', "%{$search}%")
                    ->orWhere('action_type', 'ilike', "%{$search}%")
                    ->orWhere('classification', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('classification')) {
            $query->where('classification', $request->classification);
        }

        $logs = $query->latest()->paginate(25)->withQueryString();

        $classifications = ServerLog::whereNotIn('classification', ['security_breach', 'data_breach'])
            ->distinct()
            ->orderBy('classification')
            ->pluck('classification');

        $severities = ['info', 'warning', 'error'];

        return view('viewer.logs', compact('logs', 'classifications', 'severities'));
    }
}
