<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Customer\CreateCustomer;
use App\Livewire\Customer\CustomerIndex;
use App\Livewire\Customer\EditCustomer;
use App\Livewire\Customer\ShowCustomer;
use App\Livewire\Dashboard\DashboardComponent;
use App\Livewire\Plan\CreatePlan;
use App\Livewire\Plan\EditPlan;
use App\Livewire\Plan\PlanIndex;
use App\Livewire\Router\CreateRouter;
use App\Livewire\Router\EditRouter;
use App\Livewire\Router\RouterIndex;
use App\Livewire\Setting\NotificationSetting;
use App\Livewire\Setting\SettingComponent;
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
    ->group(function () {
        Route::get('/dashboard', DashboardComponent::class)->name('analytics.index');
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
                Route::get('/{customer}', ShowCustomer::class)->name('show');
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

Route::middleware('auth')->get('/settings', SettingComponent::class)->name('settings');

Route::get('/bill-checks', BillCheck::class)->name('bill_checks');
Route::get('/payment/{id}', CreateOnlinePayment::class)->name('payment.index');
Route::get('/invoices/read', ViewInvoice::class)->name('invoices');

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
