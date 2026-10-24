<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\AccountController;
use Modules\Core\Http\Controllers\BillingController;
use Modules\Core\Http\Controllers\CheckoutController;
use Modules\Core\Http\Controllers\InvoiceController;
use Modules\Core\Http\Controllers\SettingController;

Route::middleware(['web', 'auth', 'tenant'])->name('tenantcore.')->group(function (): void {
    Route::resource('users', AccountController::class)->parameters(['users' => 'account'])->names('accounts')->except('show')->middleware('subscribed');
    Route::resource('settings', SettingController::class)->only(['index', 'edit', 'update']);
    Route::resource('billing', BillingController::class)->only('index');
    Route::prefix('billing')->name('billing.')->group(function (): void {
        Route::resource('invoices', InvoiceController::class)->only('show')->names(['show' => 'show']);
        Route::resource('invoices.checkouts', CheckoutController::class)->only('store')->names(['store' => 'checkouts.store'])->middleware('throttle:checkout');
    });
});
