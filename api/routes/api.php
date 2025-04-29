<?php

declare(strict_types=1);

use App\Http\Controllers\API\HealthCheckController;
use App\Http\Controllers\API\V1\Book\BookHistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/healthcheck', HealthCheckController::class);
Route::prefix('v1')->group(function () {
    Route::prefix('books')->group(function () {
        Route::get('/history', BookHistoryController::class);
    });
});
