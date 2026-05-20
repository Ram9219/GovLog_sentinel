<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LogCreationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LogApiController extends Controller
{
    public function __construct(private readonly LogCreationService $logCreationService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Log API is ready.',
            'endpoint' => url('/api/agent/logs'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $expectedToken = config('services.log_agent.token');

        if (empty($expectedToken)) {
            return response()->json([
                'message' => 'Log agent token is not configured on the server.',
            ], 500);
        }

        $providedToken = $request->header('X-Agent-Token');

        if (!hash_equals($expectedToken, (string) $providedToken)) {
            return response()->json([
                'message' => 'Unauthorized agent token.',
            ], 401);
        }

        $validated = $request->validate([
            'action_type' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string'],
            'severity' => ['nullable', 'in:debug,info,warning,error,critical,emergency'],
            'classification' => ['nullable', 'string', 'max:100'],
            'source_ip' => ['nullable', 'ip'],
            'context' => ['nullable', 'array'],
            'metadata' => ['nullable', 'array'],
            'request_id' => ['nullable', 'string', 'max:36'],
            'timestamp' => ['nullable', 'date'],
        ]);

        $log = $this->logCreationService->create([
            'action_type' => $validated['action_type'],
            'message' => $validated['message'],
            'severity' => $validated['severity'] ?? 'info',
            'classification' => $validated['classification'] ?? 'general',
            'source_ip' => $validated['source_ip'] ?? $request->ip(),
            'context' => $validated['context'] ?? [],
            'metadata' => $validated['metadata'] ?? [],
            'request_id' => $validated['request_id'] ?? (string) Str::uuid(),
            'timestamp' => $validated['timestamp'] ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Log received successfully.',
            'log_id' => $log->id,
            'severity' => $log->severity,
            'classification' => $log->classification,
            'is_notified' => $log->is_notified,
        ], 201);
    }
}
