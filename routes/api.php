<?php

use App\Http\Controllers\Api\LogApiController;
use Illuminate\Support\Facades\Route;

Route::get('/agent/logs', [LogApiController::class, 'index']);
Route::post('/agent/logs', [LogApiController::class, 'store']);