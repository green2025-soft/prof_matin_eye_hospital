<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\DashboardController;
use Modules\Core\Http\Controllers\PasswordController;
use Modules\Core\Http\Controllers\SessionController;

Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('home');

Route::controller(SessionController::class)->prefix('auth')->name('auth.')->group(function (): void {
    Route::get('tenant-entry/{token}', 'enterTenant')->middleware(['tenant', 'signed:relative', 'throttle:30,1'])->name('tenant-entry');

    Route::middleware('guest')->group(function (): void {
        Route::get('login', 'create')->name('login');
        Route::post('login', 'store')->middleware('throttle:login')->name('store');

        Route::controller(PasswordController::class)->name('password.')->group(function (): void {
            Route::get('forgot-password', 'create')->name('request');
            Route::post('forgot-password', 'store')->middleware('throttle:password-reset')->name('email');
            Route::get('reset-password/{token}', 'create')->name('reset');
            Route::post('reset-password', 'store')->middleware('throttle:password-reset')->name('update');
        });
    });

    Route::post('logout', 'destroy')->middleware('auth')->name('logout');
});
