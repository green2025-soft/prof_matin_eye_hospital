<?php

use Illuminate\Support\Facades\Route;
use Modules\Hospital\Http\Controllers\AppointmentController;
use Modules\Hospital\Http\Controllers\CommissionController;
use Modules\Hospital\Http\Controllers\ConsultationController;
use Modules\Hospital\Http\Controllers\DashboardController;
use Modules\Hospital\Http\Controllers\DeliveryInvestigationController;
use Modules\Hospital\Http\Controllers\DepartmentController;
use Modules\Hospital\Http\Controllers\DoctorController;
use Modules\Hospital\Http\Controllers\DueCollectionController;
use Modules\Hospital\Http\Controllers\InvestigationCategoryController;
use Modules\Hospital\Http\Controllers\InvestigationController;
use Modules\Hospital\Http\Controllers\OperationCategoryController;
use Modules\Hospital\Http\Controllers\OperationController;
use Modules\Hospital\Http\Controllers\PatientController;
use Modules\Hospital\Http\Controllers\PatientInvestigationController;
use Modules\Hospital\Http\Controllers\PatientOperationController;
use Modules\Hospital\Http\Controllers\ReferenceController;
use Modules\Hospital\Http\Controllers\RefundCauseController;
use Modules\Hospital\Http\Controllers\RefundController;
use Modules\Hospital\Http\Controllers\SettingController;

Route::middleware(['web', 'auth', 'tenant'])->prefix('hospital')->name('hospital.')->group(function (): void {
    Route::get('investigation-categories/options', [InvestigationCategoryController::class, 'options'])->name('investigation-categories.options');
    Route::get('investigations/options', [InvestigationController::class, 'options'])->name('investigations.options');
    Route::get('operation-categories/options', [OperationCategoryController::class, 'options'])->name('operation-categories.options');
    Route::get('operations/options', [OperationController::class, 'options'])->name('operations.options');
    Route::get('departments/options', [DepartmentController::class, 'options'])->name('departments.options');
    Route::get('patients/options', [PatientController::class, 'options'])->name('patients.options');
    Route::get('references/options', [ReferenceController::class, 'options'])->middleware('branch:required')->name('references.options');
    Route::get('doctors/options', [DoctorController::class, 'options'])->name('doctors.options');

    Route::middleware(['subscribed', 'hospital.permission'])->group(function (): void {
        Route::resource('settings', SettingController::class)->only(['index', 'update']);
    });

    Route::middleware(['subscribed', 'branch:required', 'hospital.permission'])->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('investigation-categories', InvestigationCategoryController::class)->parameters(['investigation-categories' => 'investigation_category'])->except('show');
        Route::resource('investigations', InvestigationController::class)->except('show');
        Route::resource('operation-categories', OperationCategoryController::class)->parameters(['operation-categories' => 'operation_category'])->except('show');
        Route::resource('operations', OperationController::class)->except('show');
        Route::resource('appointments', AppointmentController::class)->except('show');
        Route::resource('consultations', ConsultationController::class);
        Route::resource('patient-investigations', PatientInvestigationController::class)->parameters(['patient-investigations' => 'patient_investigation']);
        Route::controller(DeliveryInvestigationController::class)->prefix('delivery-investigations')->name('delivery-investigations.')->group(function (): void {
            Route::get('/', 'index')->name('index');
            Route::put('{patient_investigation}', 'update')->name('update');
        });
        Route::resource('patient-operations', PatientOperationController::class)->parameters(['patient-operations' => 'patient_operation']);
        Route::controller(CommissionController::class)->prefix('commissions')->name('commissions.')->group(function (): void {
            Route::get('/', 'index')->name('index');
            Route::put('{service}/{invoice}', 'update')->whereIn('service', ['consultation', 'investigation', 'operation'])->whereNumber('invoice')->name('update');
        });

        Route::controller(RefundController::class)->prefix('refunds')->name('refunds.')->group(function (): void {
            Route::get('invoices', 'invoices')->name('invoices');
            Route::get('invoice', 'invoice')->name('invoice');
        });
        Route::resource('refunds', RefundController::class);

        Route::controller(DueCollectionController::class)->prefix('due-collections')->name('due-collections.')->group(function (): void {
            Route::get('invoices', 'invoices')->name('invoices');
            Route::get('invoice', 'invoice')->name('invoice');
        });
        Route::resource('due-collections', DueCollectionController::class)->parameters(['due-collections' => 'due_collection']);
        Route::get('patients/modal', [PatientController::class, 'modal'])->name('patients.modal');
        Route::resource('patients', PatientController::class)->except('show');
        Route::resource('references', ReferenceController::class)->except('show');
        Route::resource('departments', DepartmentController::class)->except('show');
        Route::resource('refund-causes', RefundCauseController::class)->parameters(['refund-causes' => 'refund_cause'])->except('show');
        Route::controller(DoctorController::class)->prefix('doctor-permissions')->name('doctors.')->group(function (): void {
            Route::get('/', 'permissions')->name('permissions');
            Route::put('/', 'savePermissions')->name('permissions-update');
        });
        Route::resource('doctors', DoctorController::class);
    });
});
