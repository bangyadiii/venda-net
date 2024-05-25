<?php

use Illuminate\Support\Facades\Route;
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
use App\Livewire\Setting\TaxSetting;
use App\Livewire\Transaction\BillCheck;
use App\Livewire\Transaction\CreateOnlinePayment;
use App\Livewire\Transaction\CreateTransaction;
use App\Livewire\Transaction\TransactionIndex;
use App\Livewire\ViewInvoice;

require __DIR__ . '/auth.php';

// Main Page Route
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('analytics.index');
    });
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
        Route::get('/notifications', NotificationSetting::class)->name('notifications.index');
        Route::get('/tax', TaxSetting::class)->name('tax');
    });

Route::get('/bill-checks', BillCheck::class)->name('bill_checks');
Route::get('/payment/{id}', CreateOnlinePayment::class)->name('payment.index');
Route::get('/invoices/read', ViewInvoice::class)->name('invoices');
