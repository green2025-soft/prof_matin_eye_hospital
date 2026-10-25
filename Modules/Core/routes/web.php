<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\AccountController;
use Modules\Core\Http\Controllers\CheckoutController;
use Modules\Core\Http\Controllers\GatewayController;
use Modules\Core\Http\Controllers\InvoiceController;
use Modules\Core\Http\Controllers\PaymentController;
use Modules\Core\Http\Controllers\PermissionController;
use Modules\Core\Http\Controllers\PlanController;
use Modules\Core\Http\Controllers\PlanPermissionController;
use Modules\Core\Http\Controllers\RoleController;
use Modules\Core\Http\Controllers\SettingController;
use Modules\Core\Http\Controllers\SubscriptionController;
use Modules\Core\Http\Controllers\TenantController;

Route::middleware(['web', 'auth'])->prefix('core')->name('core.')->group(function (): void {
    Route::middleware('central')->group(function (): void {
        Route::post('tenants/{tenant}/enter', [TenantController::class, 'enter'])->middleware('throttle:10,1')->name('tenants.enter');
        Route::resource('tenants', TenantController::class)->except(['show', 'destroy']);
        Route::resource('admins', AccountController::class)->parameters(['admins' => 'account'])->names('accounts')->except('show');
        Route::resource('plans', PlanController::class)->except('show');
        Route::resource('plan-permissions', PlanPermissionController::class)->parameters(['plan-permissions' => 'plan'])->only(['index', 'update']);
        Route::resource('subscriptions', SubscriptionController::class)->except(['show', 'destroy']);
        Route::controller(InvoiceController::class)->prefix('invoices')->name('invoices.')->group(function (): void {
            Route::post('generate', 'generate')->name('generate');
            Route::post('{invoice}/issue', 'issue')->name('issue');
            Route::post('{invoice}/void', 'void')->name('void');
        });
        Route::resource('invoices', InvoiceController::class)->except('destroy');
        Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'show']);
        Route::resource('settings', SettingController::class)->only(['index', 'edit', 'update']);
        Route::controller(CheckoutController::class)->prefix('checkouts/{checkout}')->name('checkouts.')->group(function (): void {
            Route::post('approve', 'approve')->name('approve');
            Route::post('reject', 'reject')->name('reject');
        });
    });

    Route::middleware('subscribed')->group(function (): void {
        Route::resource('roles', RoleController::class)->except('show');
        Route::resource('permissions', PermissionController::class)->parameters(['permissions' => 'role'])->only(['index', 'update']);
    });
});

Route::middleware(['web', 'throttle:gateway-callback'])->prefix('core/payments')->name('gateway.')
    ->controller(GatewayController::class)->group(function (): void {
        Route::match(['get', 'post'], 'callback/{gateway}/{token}', 'callback')->name('callback');
        Route::post('notify/{gateway}/{token}', 'notify')->name('notify');
    });
