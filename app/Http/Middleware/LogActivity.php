<?php

namespace App\Http\Middleware;

use App\Services\LogCreationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    protected LogCreationService $logService;

    /**
     * Routes/patterns to exclude from logging (to prevent infinite loops and noise)
     */
    protected array $excludedPaths = [
        'admin/dashboard/realtime-stats',
        '_debugbar/*',
        'sanctum/*',
        'build/*',
        'favicon.ico',
        'storage/*',
    ];

    public function __construct(LogCreationService $logService)
    {
        $this->logService = $logService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Don't log excluded paths
        if ($this->shouldExclude($request)) {
            return $response;
        }

        // Don't log OPTIONS (CORS preflight) requests
        if ($request->method() === 'OPTIONS') {
            return $response;
        }

        try {
            $this->logRequest($request, $response);
        } catch (\Exception $e) {
            // Never let logging break the actual request
            \Log::error('LogActivity middleware error: ' . $e->getMessage());
        }

        return $response;
    }

    protected function logRequest(Request $request, Response $response): void
    {
        $statusCode = $response->getStatusCode();
        $method = $request->method();
        $path = $request->path();

        // Determine severity based on response status
        $severity = $this->determineSeverity($statusCode, $method, $path);

        // Determine action type
        $actionType = $this->determineActionType($method, $path);

        // Build message
        $message = $this->buildMessage($method, $path, $statusCode, $request);

        // Build context
        $context = [
            'method' => $method,
            'url' => $request->fullUrl(),
            'path' => $path,
            'status_code' => $statusCode,
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'response_time_ms' => defined('LARAVEL_START')
                ? round((microtime(true) - LARAVEL_START) * 1000, 2)
                : null,
        ];

        // Add request payload for non-GET requests (exclude sensitive fields)
        if (!in_array($method, ['GET', 'HEAD'])) {
            $context['request_data'] = $request->except([
                'password', 'password_confirmation', 'otp',
                '_token', 'remember_token'
            ]);
        }

        $this->logService->create([
            'action_type' => $actionType,
            'message' => $message,
            'severity' => $severity,
            'source_ip' => $request->ip(),
            'context' => $context,
            'metadata' => [
                'route_name' => $request->route()?->getName(),
                'middleware' => $request->route()?->middleware() ?? [],
            ],
        ]);
    }

    protected function determineSeverity(int $statusCode, string $method, string $path): string
    {
        // 5xx errors = error/critical
        if ($statusCode >= 500) {
            return 'error';
        }

        // 403 forbidden = warning (potential unauthorized access)
        if ($statusCode === 403) {
            return 'warning';
        }

        // 401 unauthorized = warning
        if ($statusCode === 401) {
            return 'warning';
        }

        // 404 = info (normal)
        if ($statusCode === 404) {
            return 'info';
        }

        // Destructive actions = warning
        if (in_array($method, ['DELETE'])) {
            return 'warning';
        }

        // Mutation actions = info (but notable)
        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            return 'info';
        }

        return 'info';
    }

    protected function determineActionType(string $method, string $path): string
    {
        // Auth-related
        if (str_contains($path, 'login')) return 'auth_login';
        if (str_contains($path, 'register')) return 'auth_register';
        if (str_contains($path, 'logout')) return 'auth_logout';
        if (str_contains($path, 'forgot-password')) return 'auth_password_reset';
        if (str_contains($path, 'verify-otp')) return 'auth_otp_verify';

        // Admin actions
        if (str_contains($path, 'admin/logs') && $method === 'DELETE') return 'log_deletion';
        if (str_contains($path, 'admin/logs/bulk-delete')) return 'log_bulk_deletion';
        if (str_contains($path, 'admin/logs/export')) return 'log_export';
        if (str_contains($path, 'admin/logs/verify')) return 'integrity_check';
        if (str_contains($path, 'admin/logs/critical')) return 'view_critical_logs';
        if (str_contains($path, 'admin/logs')) return 'view_logs';

        // Reports
        if (str_contains($path, 'admin/reports')) return 'view_report';
        if (str_contains($path, 'admin/classification')) return 'manage_classification';

        // Dashboard
        if (str_contains($path, 'admin/dashboard')) return 'view_dashboard';

        // Profile
        if (str_contains($path, 'profile')) return 'profile_update';

        // Default: method + simplified path
        return strtolower($method) . '_' . str_replace('/', '_', trim($path, '/'));
    }

    protected function buildMessage(string $method, string $path, int $statusCode, Request $request): string
    {
        $user = $request->user();
        $userStr = $user ? $user->email : 'Guest';

        if ($statusCode >= 500) {
            return "[{$userStr}] Server error ({$statusCode}) on {$method} /{$path}";
        }

        if ($statusCode === 403) {
            return "[{$userStr}] Forbidden access attempt on {$method} /{$path}";
        }

        if ($statusCode === 401) {
            return "[{$userStr}] Unauthorized access attempt on {$method} /{$path}";
        }

        // Auth messages
        if (str_contains($path, 'login') && $method === 'POST') {
            return $statusCode < 400
                ? "[{$userStr}] Successful login from IP " . $request->ip()
                : "[{$userStr}] Failed login attempt from IP " . $request->ip();
        }

        if (str_contains($path, 'register') && $method === 'POST') {
            return "[{$userStr}] New user registration initiated";
        }

        if (str_contains($path, 'logout')) {
            return "[{$userStr}] User logged out";
        }

        return "[{$userStr}] {$method} /{$path} → {$statusCode}";
    }

    protected function shouldExclude(Request $request): bool
    {
        $path = $request->path();

        foreach ($this->excludedPaths as $pattern) {
            if (str_contains($pattern, '*')) {
                $regex = str_replace('*', '.*', $pattern);
                if (preg_match("#^{$regex}$#", $path)) {
                    return true;
                }
            } elseif ($path === $pattern) {
                return true;
            }
        }

        return false;
    }
}
