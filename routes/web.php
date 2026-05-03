<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ClassificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========== PUBLIC ROUTES ==========
Route::get('/', function () {
    return view('welcome');
})->name('landing');

// ========== AUTHENTICATED ROUTES ==========
Route::middleware(['auth', 'verified'])->group(function () {
    
    // User Dashboard (Default Breeze)
    Route::get('/dashboard', function () {
        $user = request()->user();

        if ($user->isAdmin() || $user->isOperator()) {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ========== ADMIN ROUTES (Protected by Auth & Permissions) ==========
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/realtime-stats', [DashboardController::class, 'realtimeStats'])->name('dashboard.realtime');
    
    // Log Management
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [LogController::class, 'index'])->name('index');
        Route::get('/critical', [LogController::class, 'critical'])->name('critical');
        Route::get('/{log}', [LogController::class, 'show'])->name('show');
        Route::delete('/{log}', [LogController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [LogController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/export/excel', [LogController::class, 'export'])->name('export');
        Route::get('/verify/integrity', [LogController::class, 'verifyIntegrity'])->name('verify-integrity');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/audit', [ReportController::class, 'audit'])->name('audit');
        Route::get('/compliance', [ReportController::class, 'compliance'])->name('compliance');
        Route::post('/generate', [ReportController::class, 'generate'])->name('generate');
        Route::get('/download/{filename}', [ReportController::class, 'download'])->name('download');
    });
    
    // Classification Rules (Admin only)
    Route::middleware(['permission:manage classifications'])->prefix('classification')->name('classification.')->group(function () {
        Route::get('/', [ClassificationController::class, 'index'])->name('index');
        Route::post('/', [ClassificationController::class, 'store'])->name('store');
        Route::put('/{rule}', [ClassificationController::class, 'update'])->name('update');
        Route::delete('/{rule}', [ClassificationController::class, 'destroy'])->name('destroy');
        Route::post('/{rule}/toggle', [ClassificationController::class, 'toggle'])->name('toggle');
    });
});

// ========== TEST ROUTE (Remove in Production) ==========
Route::get('/test-critical-log', function () {
    if (!app()->environment('production')) {
        $logService = app(\App\Services\LogCreationService::class);
        $log = $logService->create([
            'action_type' => 'test_emergency',
            'message' => 'TEST: Critical security event detected! Multiple unauthorized access attempts from unknown IPs.',
            'severity' => 'critical',
            'classification' => 'security_breach',
            'context' => [
                'test' => true,
                'attempts' => 15,
                'source_ips' => ['203.0.113.45', '198.51.100.23'],
                'endpoint' => '/api/admin/config'
            ]
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Test critical log created!',
            'log_id' => $log->id,
            'severity' => $log->severity,
            'timestamp' => $log->created_at
        ]);
    }
    
    return response()->json(['error' => 'Test route disabled in production'], 403);
})->middleware(['auth']);

// ========== INCLUDE AUTH ROUTES ==========
require __DIR__.'/auth.php';