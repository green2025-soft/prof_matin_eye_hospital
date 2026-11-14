<?php

namespace Modules\Hospital\Services;

use Carbon\CarbonImmutable;
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

    public function heads(): array
    {
        return [
            'Consultation' => 'received',
            'Investigation' => 'received',
            'Operation' => 'received',
            'Due collection' => 'received',
            'Refund' => 'paid',
            'Reference commission paid' => 'paid',
        ];
    }

    public function collect(string $through, Closure $record, ?string $from = null): void
    {
        $until = CarbonImmutable::parse($through)->addDay()->toDateString();

        $this->invoices(Consultation::query(), 'Consultation', $through, $record, $from);
        $this->invoices(PatientInvestigation::query(), 'Investigation', $through, $record, $from);
        $this->invoices(PatientOperation::query(), 'Operation', $through, $record, $from);

        DueCollection::where('collection_date', '<=', $through)
            ->when($from, fn (Builder $query) => $query->where('collection_date', '>=', $from))
            ->select(['id', 'consultation_id', 'investigation_id', 'operation_id', 'invoice_no', 'collection_date', 'amount'])
            ->with(['consultation:id,patient_id', 'investigation:id,patient_id', 'operation:id,patient_id', 'patient:id,name'])
            ->chunkById(200, function (Collection $collections) use ($record): void {
                foreach ($collections as $collection) {
                    $amount = Money::minor($collection->amount);
                    $record([
                        'date' => $collection->collection_date->toDateString(),
                        'module' => 'hospital',
                        'label' => 'Due collection',
                        'reference' => $collection->invoice_no,
                        'description' => $collection->patient?->name ?? '',
                        'received' => $amount,
                        'paid' => 0,
                        'receivable' => -$amount,
                        'payable' => 0,
                    ]);
                }
            });

        Refund::where('created_at', '<', $until)
            ->when($from, fn (Builder $query) => $query->where('created_at', '>=', $from))
            ->select(['id', 'consultation_id', 'investigation_id', 'operation_id', 'invoice_no', 'created_at', 'amount', 'due_adjustment'])
            ->with(['consultation:id,patient_id', 'investigation:id,patient_id', 'operation:id,patient_id', 'patient:id,name'])
            ->chunkById(200, function (Collection $refunds) use ($record): void {
                foreach ($refunds as $refund) {
                    $record([
                        'date' => $refund->created_at->toDateString(),
                        'module' => 'hospital',
                        'label' => 'Refund',
                        'reference' => $refund->invoice_no ?: '#'.$refund->id,
                        'description' => $refund->patient?->name ?? '',
                        'received' => 0,
                        'paid' => Money::minor($refund->amount),
                        'receivable' => -Money::minor($refund->due_adjustment),
                        'payable' => 0,
                    ]);
                }
            });

        $this->commissions(Consultation::query(), $until, $record, $from);
        $this->commissions(PatientInvestigation::query(), $until, $record, $from);
        $this->commissions(PatientOperation::query(), $until, $record, $from);
    }

    private function invoices(Builder $query, string $label, string $through, Closure $record, ?string $from): void
    {
        // Header payments remain the original receipt; collections are separate dated events.
        $query->where('entry_date', '<=', $through)
            ->when($from, fn (Builder $query) => $query->where('entry_date', '>=', $from))
            ->select(['id', 'patient_id', 'invoice_no', 'entry_date', 'paid_amount', 'due_amount'])
            ->with('patient:id,name')
            ->chunkById(200, function (Collection $invoices) use ($label, $record): void {
                foreach ($invoices as $invoice) {
                    $record([
                        'date' => $invoice->entry_date->toDateString(),
                        'module' => 'hospital',
                        'label' => $label,
                        'reference' => $invoice->invoice_no ?: '#'.$invoice->id,
                        'description' => $invoice->patient?->name ?? '',
                        'received' => Money::minor($invoice->paid_amount),
                        'paid' => 0,
                        'receivable' => Money::minor($invoice->due_amount),
                        'payable' => 0,
                    ]);
                }
            });
    }

    private function commissions(Builder $query, string $until, Closure $record, ?string $from): void
    {
        // Only retained paid state has a reliable payment date; no commission event history exists.
        $query->whereNotNull('reference_commission_paid_at')
            ->where('reference_commission_paid_at', '<', $until)
            ->when($from, fn (Builder $query) => $query->where('reference_commission_paid_at', '>=', $from))
            ->select(['id', 'patient_id', 'reference_id', 'invoice_no', 'reference_commission_paid_at', 'reference_commission_paid_amount'])
            ->with(['reference:id,name', 'patient:id,name'])
            ->chunkById(200, function (Collection $invoices) use ($record): void {
                foreach ($invoices as $invoice) {
                    $record([
                        'date' => $invoice->reference_commission_paid_at->toDateString(),
                        'module' => 'hospital',
                        'label' => 'Reference commission paid',
                        'reference' => $invoice->invoice_no ?: '#'.$invoice->id,
                        'description' => $invoice->reference?->name ?? $invoice->patient?->name ?? '',
                        'received' => 0,
                        'paid' => Money::minor($invoice->reference_commission_paid_amount),
                        'receivable' => 0,
                        'payable' => 0,
                    ]);
                }
            });
    }
}
