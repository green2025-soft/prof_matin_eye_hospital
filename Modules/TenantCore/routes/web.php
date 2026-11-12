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
use Modules\TenantCore\Http\Controllers\ExpenseController;
use Modules\TenantCore\Http\Controllers\ExpenseHeadController;
use Modules\TenantCore\Http\Controllers\IncomeController;
use Modules\TenantCore\Http\Controllers\IncomeHeadController;
use Modules\TenantCore\Http\Controllers\ModuleDashboardController;
use Modules\TenantCore\Http\Controllers\PaymentTypeController;
use Modules\TenantCore\Http\Controllers\Reports\CashReportController;
use Modules\TenantCore\Http\Controllers\Reports\DailyReportController;
use Modules\TenantCore\Http\Controllers\Reports\MonthlyCashReportController;
use Modules\TenantCore\Http\Controllers\SmsController;
use Modules\TenantCore\Http\Controllers\SmsRechargeController;
use Modules\TenantCore\Http\Controllers\ThanaController;

Route::middleware(['web', 'auth', 'tenant', 'tenantcore.permission'])->name('tenantcore.')->group(function (): void {
    Route::get('module-dashboard', [ModuleDashboardController::class, 'index'])->name('module-dashboard.index');
    Route::get('workspace', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('sms')->group(function (): void {
        Route::get('/', [SmsController::class, 'index'])->name('sms.index');
        Route::get('recharges/{sms_recharge}/proof', [SmsRechargeController::class, 'proof'])->name('sms-recharges.proof');
        Route::resource('recharges', SmsRechargeController::class)->parameters(['recharges' => 'sms_recharge'])->names('sms-recharges')->only(['index', 'create', 'store']);
    });

    Route::controller(BranchDashboardController::class)->prefix('branch-dashboard')->name('branch-dashboard.')->group(function (): void {
        Route::get('/', 'index')->name('index');
        Route::post('{branch}', 'store')->name('store');
        Route::delete('/', 'destroy')->name('destroy');
    });

    Route::get('divisions/options', [DivisionController::class, 'options'])->name('divisions.options');
    Route::get('districts/options', [DistrictController::class, 'options'])->name('districts.options');
    Route::get('thanas/options', [ThanaController::class, 'options'])->name('thanas.options');
    Route::get('income-heads/options', [IncomeHeadController::class, 'options'])->name('income-heads.options');
    Route::get('expense-heads/options', [ExpenseHeadController::class, 'options'])->name('expense-heads.options');
    Route::get('audit-logs/branches', [AuditLogController::class, 'branches'])->name('audit-logs.branches');

    Route::middleware('subscribed')->group(function (): void {
        Route::resource('income-heads', IncomeHeadController::class)
            ->parameters(['income-heads' => 'income_head'])->except('show');
        Route::resource('expense-heads', ExpenseHeadController::class)
            ->parameters(['expense-heads' => 'expense_head'])->except('show');
        Route::middleware('branch:required')->group(function (): void {
            Route::prefix('reports')->name('reports.')->group(function (): void {
                Route::get('daily', [DailyReportController::class, 'index'])->name('daily');
                Route::get('cash', [CashReportController::class, 'index'])->name('cash');
                Route::get('monthly-cash', [MonthlyCashReportController::class, 'index'])->name('monthly-cash');
            });
            Route::resource('incomes', IncomeController::class)->except('show');
            Route::resource('expenses', ExpenseController::class)->except('show');
        });

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
