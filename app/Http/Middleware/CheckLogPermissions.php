<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogPermissions
{
    /**
     * Permission mapping: which roles can access which permissions
     */
    protected array $rolePermissions = [
        'admin' => ['view logs', 'manage logs', 'delete logs', 'export logs', 'manage classifications', 'view reports', 'manage settings'],
        'operator' => ['view logs', 'manage logs', 'export logs', 'view reports'],
        'viewer' => ['view logs', 'view reports'],
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized — please log in.');
        }

        $userRole = $user->role ?? 'viewer';
        $allowedPermissions = $this->rolePermissions[$userRole] ?? [];

        // Check if user has at least one of the required permissions
        foreach ($permissions as $permission) {
            if (in_array($permission, $allowedPermissions)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this resource.');
    }
}
