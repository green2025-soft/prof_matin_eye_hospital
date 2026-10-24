<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\AdminController;
use Modules\Core\Http\Controllers\PermissionController;
use Modules\Core\Http\Controllers\RoleController;
use Modules\Core\Http\Controllers\TenantController;

Route::name('core.')->group(function (): void {
    Route::middleware('central')->group(function (): void {
        Route::resource('tenants', TenantController::class)->except(['show', 'destroy']);
        Route::resource('admins', AdminController::class)->parameters(['admins' => 'account'])->names('accounts')->except('show');
    });

    Route::resource('roles', RoleController::class)->except('show');
    Route::resource('permissions', PermissionController::class)->parameters(['permissions' => 'role'])->only(['index', 'update']);
});
