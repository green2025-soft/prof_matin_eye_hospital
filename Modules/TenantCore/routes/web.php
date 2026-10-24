<?php

use Illuminate\Support\Facades\Route;
use Modules\TenantCore\Http\Controllers\UserController;

Route::middleware('tenant')->name('tenantcore.')->group(function (): void {
    Route::resource('users', UserController::class)->parameters(['users' => 'account'])->names('accounts')->except('show');
});
