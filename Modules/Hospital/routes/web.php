<?php

use Illuminate\Support\Facades\Route;
use Modules\Hospital\Http\Controllers\AppointmentController;
use Modules\Hospital\Http\Controllers\CommissionController;
use Modules\Hospital\Http\Controllers\ConsultationController;
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
use Modules\TenantCore\Http\Controllers\ModuleDashboardController;

Route::middleware(['web', 'auth', 'tenant'])
    ->prefix('hospital')
    ->name('hospital.')
    ->group(function (): void {
        Route::get('investigation-categories/options', [InvestigationCategoryController::class, 'options'])->name('investigation-categories.options');
        Route::get('investigations/options', [InvestigationController::class, 'options'])->name('investigations.options');
        Route::get('operation-categories/options', [OperationCategoryController::class, 'options'])->name('operation-categories.options');
        Route::get('operations/options', [OperationController::class, 'options'])->name('operations.options');
        Route::get('departments/options', [DepartmentController::class, 'options'])->name('departments.options');
        Route::get('patients/options', [PatientController::class, 'options'])->name('patients.options');
        Route::get('references/options', [ReferenceController::class, 'options'])->middleware('branch:required')->name('references.options');
        Route::get('doctors/options', [DoctorController::class, 'options'])->name('doctors.options');

        Route::middleware(['subscribed', 'permission:hospital.view', 'branch:required'])->group(function (): void {
            Route::get('/', [ModuleDashboardController::class, 'show'])->defaults('module', 'Hospital')->name('dashboard');
            Route::resource('investigation-categories', InvestigationCategoryController::class)
                ->parameters(['investigation-categories' => 'investigation_category'])->except('show');
            Route::resource('investigations', InvestigationController::class)->except('show');
            Route::resource('operation-categories', OperationCategoryController::class)
                ->parameters(['operation-categories' => 'operation_category'])->except('show');
            Route::resource('operations', OperationController::class)->except('show');
            Route::resource('appointments', AppointmentController::class)->except('show');
            Route::resource('consultations', ConsultationController::class);
            Route::resource('patient-investigations', PatientInvestigationController::class)
                ->parameters(['patient-investigations' => 'patient_investigation']);
            Route::get('delivery-investigations', [DeliveryInvestigationController::class, 'index'])->name('delivery-investigations.index');
            Route::put('delivery-investigations/{patient_investigation}', [DeliveryInvestigationController::class, 'update'])->name('delivery-investigations.update');
            Route::resource('patient-operations', PatientOperationController::class)
                ->parameters(['patient-operations' => 'patient_operation']);
            Route::get('commissions', [CommissionController::class, 'index'])->name('commissions.index');
            Route::put('commissions/{service}/{invoice}', [CommissionController::class, 'update'])
                ->whereIn('service', ['consultation', 'investigation', 'operation'])->whereNumber('invoice')->name('commissions.update');
            Route::get('refunds/invoices', [RefundController::class, 'invoices'])->name('refunds.invoices');
            Route::get('refunds/invoice', [RefundController::class, 'invoice'])->name('refunds.invoice');
            Route::resource('refunds', RefundController::class);
            Route::get('due-collections/invoices', [DueCollectionController::class, 'invoices'])->name('due-collections.invoices');
            Route::get('due-collections/invoice', [DueCollectionController::class, 'invoice'])->name('due-collections.invoice');
            Route::resource('due-collections', DueCollectionController::class)
                ->parameters(['due-collections' => 'due_collection']);
            Route::get('patients/modal', [PatientController::class, 'modal'])->name('patients.modal');
            Route::resource('patients', PatientController::class)->except('show');
            Route::resource('references', ReferenceController::class)->except('show');
            Route::resource('departments', DepartmentController::class)->except('show');
            Route::resource('refund-causes', RefundCauseController::class)
                ->parameters(['refund-causes' => 'refund_cause'])->except('show');
            Route::get('doctor-permissions', [DoctorController::class, 'permissions'])->name('doctors.permissions');
            Route::put('doctor-permissions', [DoctorController::class, 'savePermissions'])->name('doctors.permissions-update');
            Route::resource('doctors', DoctorController::class);
        });
    });
