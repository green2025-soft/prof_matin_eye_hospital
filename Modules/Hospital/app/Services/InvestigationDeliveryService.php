<?php

namespace Modules\Hospital\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Services\SmsService;
use Modules\Hospital\Models\PatientInvestigation;

class InvestigationDeliveryService
{
    public function __construct(
        private SmsService $sms,
        private ClinicalInvoiceService $invoices,
    ) {}

    private function allowsInvestigationDelivery(Account $actor): bool
    {
        $permissions = request()->attributes->get('navigation_permissions', []);

        return $actor->matchesApplication()
            && ($permissions['hospital.view'] ?? false)
            && ($permissions['delivery-investigations.update'] ?? false);
    }

    public function setInvestigationDeliveryStatus(Account $actor, PatientInvestigation $record, string $status): PatientInvestigation
    {
        return $actor->withAccessLock(function (Account $freshActor) use ($record, $status): PatientInvestigation {
            $entry = $record->newQuery()
                ->whereKey($record->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            if (! $this->allowsInvestigationDelivery($freshActor)) {
                throw new AuthorizationException('Your account or plan does not include investigation delivery updates.');
            }

            $entry->delivery_status = $status;
            if (! $entry->isDirty('delivery_status')) {
                return $entry;
            }
            $entry->save();

            if ($status !== 'delivered') {
                return $entry;
            }
            $settings = Setting::values('hospital', $entry->getConnectionName());
            if (! $settings['sms_investigation_delivery_enabled']) {
                return $entry;
            }

            $patient = $entry->patient()->firstOrFail();
            $balance = $this->invoices->balance($entry);
            $this->sms->queue(
                $entry,
                'hospital.investigation_delivery',
                'patient-investigation:'.$entry->getKey().':delivered',
                $patient->mobile_no,
                true,
                $settings['sms_investigation_delivery_template'],
                [
                    ...$patient->smsValues(),
                    'name' => $patient->name,
                    'invoice' => $entry->invoice_no ?: '#'.$entry->getKey(),
                    'date' => now(config('app.timezone'))->format('d-m-Y'),
                    'delivery_date' => $entry->delivery_date?->format('d-m-Y') ?? '',
                    'total' => Money::decimal($balance['remaining_amount']),
                    'paid' => Money::decimal($balance['remaining_paid']),
                    'due' => Money::decimal($balance['remaining_due']),
                    'amount' => Money::decimal($balance['remaining_amount']),
                ],
            );

            return $entry;
        });
    }
}
