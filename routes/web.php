<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\DashboardController;
use Modules\Core\Http\Controllers\SessionController;

Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('home');

Route::controller(SessionController::class)->prefix('auth')->name('auth.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('login', 'create')->name('login');
        Route::post('login', 'store')->middleware('throttle:login')->name('store');
    });

    Route::post('logout', 'destroy')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function (): void {
    require base_path('Modules/Core/routes/web.php');
    require base_path('Modules/TenantCore/routes/web.php');
});
