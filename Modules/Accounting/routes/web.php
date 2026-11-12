<?php

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Http\Controllers\AccountHeadController;
use Modules\Accounting\Http\Controllers\AccountingSelectorController;
use Modules\Accounting\Http\Controllers\AccountModuleController;
use Modules\Accounting\Http\Controllers\DashboardController;
use Modules\Accounting\Http\Controllers\JournalEntryController;
use Modules\Accounting\Http\Controllers\PaymentAccountController;
use Modules\Accounting\Http\Controllers\Reports\BalanceSheetReportController;
use Modules\Accounting\Http\Controllers\Reports\CashBankBookReportController;
use Modules\Accounting\Http\Controllers\Reports\CashFlowReportController;
use Modules\Accounting\Http\Controllers\Reports\ChangesInEquityReportController;
use Modules\Accounting\Http\Controllers\Reports\IncomeExpenseReportController;
use Modules\Accounting\Http\Controllers\Reports\LedgerReportController;
use Modules\Accounting\Http\Controllers\Reports\ReceiptsPaymentsReportController;
use Modules\Accounting\Http\Controllers\Reports\TrialBalanceReportController;

Route::middleware(['web', 'auth', 'tenant', 'subscribed', 'accounting.permission'])->prefix('accounting')->name('accounting.')->group(function (): void {
    Route::get('ledgers/options', [AccountingSelectorController::class, 'ledgers'])->name('ledgers.options');
    Route::resource('chart-of-accounts', AccountHeadController::class)->parameters(['chart-of-accounts' => 'account_head'])->except('show');
    Route::resource('account-modules', AccountModuleController::class)->parameters(['account-modules' => 'account_module'])->only(['index', 'edit', 'update']);
    Route::resource('payment-accounts', PaymentAccountController::class)->parameters(['payment-accounts' => 'payment_account'])->except('show');

    Route::middleware('branch:required')->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('journal-entries', JournalEntryController::class)->parameters(['journal-entries' => 'journal_entry']);

        Route::prefix('reports')->name('reports.')->group(function (): void {
            Route::get('ledger', [LedgerReportController::class, 'index'])->name('ledger');
            Route::get('trial-balance', [TrialBalanceReportController::class, 'index'])->name('trial-balance');
            Route::get('income-expense', [IncomeExpenseReportController::class, 'index'])->name('income-expense');
            Route::get('balance-sheet', [BalanceSheetReportController::class, 'index'])->name('balance-sheet');
            Route::get('cash-flow', [CashFlowReportController::class, 'index'])->name('cash-flow');
            Route::get('cash-bank-book', [CashBankBookReportController::class, 'index'])->name('cash-bank-book');
            Route::get('changes-in-equity', [ChangesInEquityReportController::class, 'index'])->name('changes-in-equity');
            Route::get('receipts-payments', [ReceiptsPaymentsReportController::class, 'index'])->name('receipts-payments');
        });
    });
});
