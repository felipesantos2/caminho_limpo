<?php

use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\MunicipalityGeofenceController;
use App\Http\Controllers\Api\V1\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('reports', [ReportController::class, 'index'])->name('api.v1.reports.index');
    Route::get('reports/{report}', [ReportController::class, 'show'])->name('api.v1.reports.show');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('dashboard', DashboardController::class)->name('api.v1.dashboard');
        Route::apiResource('municipality-geofences', MunicipalityGeofenceController::class)
            ->names('api.v1.municipality-geofences');
        Route::post('reports', [ReportController::class, 'store'])->name('api.v1.reports.store');
        Route::match(['put', 'patch'], 'reports/{report}', [ReportController::class, 'update'])
            ->name('api.v1.reports.update');
        Route::delete('reports/{report}', [ReportController::class, 'destroy'])->name('api.v1.reports.destroy');
    });
});
