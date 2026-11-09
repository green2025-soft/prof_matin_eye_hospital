<?php

namespace Modules\Prescription\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Models\Setting;
use Modules\Prescription\Models\GlassPrescription;
use Modules\Prescription\Models\Prescription;
use Modules\Prescription\Services\RxNumberService;
use Modules\Prescription\Validation\RxNumberPattern;
use Modules\Prescription\View\Composers\MenuComposer;
use Modules\Prescription\View\Composers\PrintComposer;

class PrescriptionServiceProvider extends ServiceProvider
{
    public function boot(RxNumberService $numbers): void
    {
        Validator::extend(
            'prescription_rx_pattern',
            RxNumberPattern::class,
            'Use one {SEQ} or {SEQ:1} to {SEQ:12} token and supported date/branch tokens within 80 characters.',
        );

        View::composer('core::layouts.app', MenuComposer::class);
        View::composer('prescription::prints.layout', PrintComposer::class);

        Prescription::created(function (Prescription $record) use ($numbers): void {
            $settings = Setting::values('prescription', $record->getConnectionName());
            $numbers->assign($record, $settings['general_rx_pattern']);
        });

        GlassPrescription::created(function (GlassPrescription $record) use ($numbers): void {
            $settings = Setting::values('prescription', $record->getConnectionName());
            $numbers->assign($record, $settings['glass_rx_pattern']);
        });

        $this->loadViewsFrom(module_path('Prescription', 'resources/views'), 'prescription');
        $this->loadRoutesFrom(module_path('Prescription', 'routes/web.php'));
    }
}
