<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\LoginComponent;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', LoginComponent::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])
        ->name('logout');
});
