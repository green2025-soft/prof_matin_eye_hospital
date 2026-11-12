<?php

namespace Modules\Hospital\Services;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Helpers\Money;
use Modules\Hospital\Models\Consultation;
use Modules\Hospital\Models\DueCollection;
use Modules\Hospital\Models\PatientInvestigation;
use Modules\Hospital\Models\PatientOperation;
use Modules\Hospital\Models\Refund;
use Modules\TenantCore\Contracts\CashReportSource;

class HospitalCashReportSource implements CashReportSource
{
    public function modules(): array
    {
        return ['hospital'];
    }

    public function collect(string $through, Closure $record): void
    {
        $this->invoices(Consultation::query(), 'Consultation', $through, $record);
        $this->invoices(PatientInvestigation::query(), 'Investigation', $through, $record);
        $this->invoices(PatientOperation::query(), 'Operation', $through, $record);

        DueCollection::where('collection_date', '<=', $through)
            ->select(['id', 'collection_date', 'amount'])
            ->chunkById(200, function (Collection $collections) use ($record): void {
                foreach ($collections as $collection) {
                    $amount = Money::minor($collection->amount);
                    $record([
                        'date' => $collection->collection_date->toDateString(),
                        'module' => 'hospital',
                        'label' => 'Due collection',
                        'received' => $amount,
                        'paid' => 0,
                        'receivable' => -$amount,
                        'payable' => 0,
                    ]);
                }
            });

        Refund::whereDate('created_at', '<=', $through)
            ->select(['id', 'created_at', 'amount', 'due_adjustment'])
            ->chunkById(200, function (Collection $refunds) use ($record): void {
                foreach ($refunds as $refund) {
                    $record([
                        'date' => $refund->created_at->toDateString(),
                        'module' => 'hospital',
                        'label' => 'Refund',
                        'received' => 0,
                        'paid' => Money::minor($refund->amount),
                        'receivable' => -Money::minor($refund->due_adjustment),
                        'payable' => 0,
                    ]);
                }
            });

        $this->commissions(Consultation::query(), $through, $record);
        $this->commissions(PatientInvestigation::query(), $through, $record);
        $this->commissions(PatientOperation::query(), $through, $record);
    }

    private function invoices(Builder $query, string $label, string $through, Closure $record): void
    {
        // Header payments remain the original receipt; collections are separate dated events.
        $query->where('entry_date', '<=', $through)
            ->select(['id', 'entry_date', 'paid_amount', 'due_amount'])
            ->chunkById(200, function (Collection $invoices) use ($label, $record): void {
                foreach ($invoices as $invoice) {
                    $record([
                        'date' => $invoice->entry_date->toDateString(),
                        'module' => 'hospital',
                        'label' => $label,
                        'received' => Money::minor($invoice->paid_amount),
                        'paid' => 0,
                        'receivable' => Money::minor($invoice->due_amount),
                        'payable' => 0,
                    ]);
                }
            });
    }

    private function commissions(Builder $query, string $through, Closure $record): void
    {
        // Only retained paid state has a reliable payment date; no commission event history exists.
        $query->whereNotNull('reference_commission_paid_at')
            ->whereDate('reference_commission_paid_at', '<=', $through)
            ->select(['id', 'reference_commission_paid_at', 'reference_commission_paid_amount'])
            ->chunkById(200, function (Collection $invoices) use ($record): void {
                foreach ($invoices as $invoice) {
                    $record([
                        'date' => $invoice->reference_commission_paid_at->toDateString(),
                        'module' => 'hospital',
                        'label' => 'Reference commission paid',
                        'received' => 0,
                        'paid' => Money::minor($invoice->reference_commission_paid_amount),
                        'receivable' => 0,
                        'payable' => 0,
                    ]);
                }
            });
    }
}
