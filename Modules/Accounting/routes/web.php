<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix('accounting')
    ->name('accounting.')
    ->group(function (): void {
        // Add authorized routes when this module has an implemented feature.
    });
