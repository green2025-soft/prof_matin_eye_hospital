<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\AccountController;
use Modules\Core\Http\Controllers\BillingController;
use Modules\Core\Http\Controllers\CheckoutController;
use Modules\Core\Http\Controllers\DashboardController;
use Modules\Core\Http\Controllers\InvoiceController;
use Modules\Core\Http\Controllers\SettingController;
use Modules\TenantCore\Http\Controllers\AuditLogController;
use Modules\TenantCore\Http\Controllers\BranchController;
use Modules\TenantCore\Http\Controllers\BranchDashboardController;
use Modules\TenantCore\Http\Controllers\DistrictController;
use Modules\TenantCore\Http\Controllers\DivisionController;
use Modules\TenantCore\Http\Controllers\ModuleDashboardController;
use Modules\TenantCore\Http\Controllers\PaymentTypeController;
use Modules\TenantCore\Http\Controllers\ThanaController;

Route::middleware(['web', 'auth', 'tenant'])->name('tenantcore.')->group(function (): void {
    Route::get('module-dashboard', [ModuleDashboardController::class, 'index'])->name('module-dashboard.index');
    Route::get('workspace', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(BranchDashboardController::class)->prefix('branch-dashboard')->name('branch-dashboard.')->group(function (): void {
        Route::get('/', 'index')->name('index');
        Route::post('{branch}', 'store')->name('store');
        Route::delete('/', 'destroy')->name('destroy');
    });

    Route::get('divisions/options', [DivisionController::class, 'options'])->name('divisions.options');
    Route::get('districts/options', [DistrictController::class, 'options'])->name('districts.options');
    Route::get('thanas/options', [ThanaController::class, 'options'])->name('thanas.options');
    Route::get('audit-logs/branches', [AuditLogController::class, 'branches'])->name('audit-logs.branches');

    Route::middleware('subscribed')->group(function (): void {
        Route::resource('audit-logs', AuditLogController::class)->parameters(['audit-logs' => 'audit_log'])->only(['index', 'show']);
        Route::resource('branches', BranchController::class)->except('show');
        Route::resource('divisions', DivisionController::class)->except('show');
        Route::resource('districts', DistrictController::class)->except('show');
        Route::resource('thanas', ThanaController::class)->except('show');
        Route::resource('payment-types', PaymentTypeController::class)
            ->parameters(['payment-types' => 'payment_type'])->except('show');
        Route::resource('users', AccountController::class)->parameters(['users' => 'account'])->names('accounts')->except('show');
        Route::resource('settings', SettingController::class)->only(['index', 'edit', 'update']);
    });

    Route::prefix('billing')->name('billing.')->group(function (): void {
        Route::get('/', [BillingController::class, 'index'])->name('index');
        Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('show');

        Route::middleware('throttle:checkout')->group(function (): void {
            Route::post('invoices/{invoice}/checkouts', [CheckoutController::class, 'store'])->name('checkouts.store');
        });
    });
});
