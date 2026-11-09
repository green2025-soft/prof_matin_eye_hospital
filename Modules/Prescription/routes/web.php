<?php

use Illuminate\Support\Facades\Route;
use Modules\Prescription\Http\Controllers\DashboardController;
use Modules\Prescription\Http\Controllers\GlassPrescriptionController;
use Modules\Prescription\Http\Controllers\PrescriptionController;
use Modules\Prescription\Http\Controllers\PrescriptionOptionController;
use Modules\Prescription\Http\Controllers\SettingController;

Route::middleware(['web', 'auth', 'tenant', 'subscribed', 'permission:prescription.view'])
    ->prefix('prescription')
    ->name('prescription.')
    ->group(function (): void {
        Route::get('options/selector', [PrescriptionOptionController::class, 'options'])->name('options.selector');
        Route::resource('options', PrescriptionOptionController::class)
            ->parameters(['options' => 'prescription_option'])
            ->except('show');

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings/{setting}', [SettingController::class, 'store'])->name('settings.update');

        Route::middleware('branch:required')->group(function (): void {
            Route::get('/', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::resource('prescriptions', PrescriptionController::class);
            Route::resource('glass-prescriptions', GlassPrescriptionController::class)
                ->parameters(['glass-prescriptions' => 'glass_prescription']);
        });
    });
