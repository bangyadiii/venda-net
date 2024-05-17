<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\dashboard\AnalyticsController;
use App\Http\Controllers\PacketController;
use App\Http\Controllers\RouterController;
use App\Livewire\Analytics\AnalyticIndex;
use App\Livewire\Customer\CreateCustomer;
use App\Livewire\Customer\CustomerIndex;
use App\Livewire\Customer\EditCustomer;
use App\Livewire\NotificationSetting;
use App\Livewire\Plan\CreatePlan;
use App\Livewire\Plan\EditPlan;
use App\Livewire\Plan\PlanIndex;
use App\Livewire\Router\CreateRouter;
use App\Livewire\Router\EditRouter;
use App\Livewire\Router\RouterIndex;
use App\Livewire\Transaction\CreateTransaction;
use App\Livewire\Transaction\TransactionIndex;

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

        Route::name('plans.')
            ->prefix('plans')->group(function () {
                Route::get('/', PlanIndex::class)->name('index');
                Route::get('/create', CreatePlan::class)->name('create');
                Route::get('/{id}/edit', EditPlan::class)->name('edit');
            });

        Route::name('customers.')
            ->prefix('customers')->group(function () {
                Route::get('/', CustomerIndex::class)->name('index');
                Route::get('/create', CreateCustomer::class)->name('create');
                Route::get('/{customer}/edit', EditCustomer::class)->name('edit');
                Route::delete('/{customer}/delete', CreateCustomer::class)->name('delete');
                // Route::get('/{id}/edit', EditPlan::class)->name('edit');
            });
    });


Route::middleware('auth')
    ->prefix('transactions')
    ->name('transactions.')
    ->group(function () {
        Route::get('/', TransactionIndex::class)->name('index');
        Route::get('/create', CreateTransaction::class)->name('create');
    });


Route::middleware('auth')
    ->prefix('settings')
    ->group(function () {
        Route::get('/notification', NotificationSetting::class)->name('notifications.index');
    });
