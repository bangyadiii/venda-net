<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\dashboard\AnalyticsController;
use App\Http\Controllers\PacketController;
use App\Http\Controllers\RouterController;
use App\Livewire\Analytics\AnalyticIndex;
use App\Livewire\Router\CreateRouter;
use App\Livewire\Router\EditRouter;
use App\Livewire\Router\RouterIndex;

require __DIR__ . '/auth.php';
require __DIR__ . '/vendor.php';

// Main Page Route
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('analytics.index');
    });
    // Route::get('/', [AnalyticsController::class, 'index'])->name('dashboard-analytics');

    Route::resource('/router-settings', RouterController::class);
    Route::resource('/packet-settings', PacketController::class);
    Route::resource('customers', CustomerController::class);
});

Route::middleware('auth')
    ->prefix('dashboard')
    ->group(function () {
        Route::get('/', AnalyticIndex::class)->name('analytics.index');
        Route::prefix('routers')->group(function () {
            Route::get('/', RouterIndex::class)->name('routers.index');
            Route::get('/create', CreateRouter::class)->name('routers.create');
            Route::get('/{id}/edit', EditRouter::class)->name('routers.edit');
        });
    });
