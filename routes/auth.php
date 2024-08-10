<?php

use App\Livewire\LoginComponent;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', LoginComponent::class)->name('login');
});
