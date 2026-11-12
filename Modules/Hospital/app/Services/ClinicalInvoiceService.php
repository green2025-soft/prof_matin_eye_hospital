<?php

namespace Modules\Hospital\Services;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Hospital\Models\BaseModel;
use Modules\Hospital\Models\Consultation;
use Modules\Hospital\Models\DueCollection;
use Modules\Hospital\Models\PatientInvestigation;
use Modules\Hospital\Models\PatientOperation;
use Modules\Hospital\Models\Refund;

class ClinicalInvoiceService
{
    public function source(Refund|DueCollection $record, bool $lock = false): BaseModel
    {
        $query = $record->{$record->serviceType()}()->where('branch_id', $record->branch_id);
        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->firstOrFail();
    }

    public function find(Refund|DueCollection $context, string $invoice, int $branchId, bool $lock = false): BaseModel
    {
        // Form restoration also calls this with old input, outside Form Request validation.
        if (! preg_match('/\A(consultation|investigation|operation):([1-9]\d{0,17})\z/', $invoice, $matches)) {
            throw ValidationException::withMessages(['invoice' => 'Select an available clinical invoice.']);
        }
        [, $relation, $id] = $matches;
        $query = $context->{$relation}()->getRelated()->newQuery()->whereKey($id)->where('branch_id', $branchId);
        if ($lock) {
            $query->lockForUpdate();
        }
        $source = $query->first();
        if ($source === null) {
            throw ValidationException::withMessages(['invoice' => 'Select an invoice from this branch.']);
        }

        return $source;
    }

    public function listBalances(Consultation|PatientInvestigation|PatientOperation $source, array $invoiceIds, int $branchId): array
    {
        if ($invoiceIds === []) {
            return [];
        }

        $records = $this->withLedger(
            $source->newQuery()->where('branch_id', $branchId)->whereKey($invoiceIds))->get();
        $balances = [];
        foreach ($records as $invoice) {
            $balances[$invoice->getKey()] = $this->balanceValues($invoice);
        }

        return $balances;
    }

    // The same ledger arithmetic applies to every clinical invoice.
    public function balance(Consultation|PatientInvestigation|PatientOperation $source, ?Collection $refunds = null): array
    {
        $refunds ??= $source->{$source::REFUND_RELATION}()->get(['id', 'amount', 'due_adjustment']);
        $cashRefunded   = 0;
        $dueAdjusted    = 0;
        foreach ($refunds as $refund) {
            $cashRefunded   += Money::minor($refund->amount);
            $dueAdjusted    += Money::minor($refund->due_adjustment);
        }

        $collected      = Money::minor((string) $source->dueCollections()->sum('amount'));
        $remainingPaid  = Money::minor($source->paid_amount) + $collected - $cashRefunded;
        $remainingDue   = Money::minor($source->due_amount) - $collected - $dueAdjusted;
        $remainingAmount = Money::minor($source->payable_amount) - $cashRefunded - $dueAdjusted;
        if (min($remainingPaid, $remainingDue, $remainingAmount) < 0 || $remainingAmount !== $remainingPaid + $remainingDue) {
            throw ValidationException::withMessages(['invoice' => 'The invoice and refund balances are inconsistent. Review the saved payments before refunding.']);
        }

        return [
            'cash_refunded'     => $cashRefunded,
            'due_adjusted'      => $dueAdjusted,
            'credit'            => $cashRefunded + $dueAdjusted,
            'collected'         => $collected,
            'remaining_paid'    => $remainingPaid,
            'remaining_due'     => $remainingDue,
            'remaining_amount'  => $remainingAmount,
            'has_refunds'       => $refunds->isNotEmpty(),
        ];
    }

    public function sources(int $branchId): array
    {
        return [
            $this->withLedger(Consultation::where('branch_id', $branchId)),
            $this->withLedger(PatientInvestigation::where('branch_id', $branchId)),
            $this->withLedger(PatientOperation::where('branch_id', $branchId)),
        ];
    }

    public function withLedger(Builder $query): Builder
    {
        $model      = $query->getModel();
        $refunds    = $model::REFUND_RELATION;

        return $query->select($model->qualifyColumn('*'))
            ->withSum($refunds.' as cash_refunded', 'amount')
            ->withSum($refunds.' as due_adjusted', 'due_adjustment')
            ->withCount($refunds.' as refund_count')
            ->withSum('dueCollections as collected', 'amount')
            ->withRefundedGross();
    }

    public function balanceValues(Consultation|PatientInvestigation|PatientOperation $source): array
    {
        $cash           = Money::minor((string) ($source->getAttribute('cash_refunded') ?? '0.00'));
        $dueAdjustment  = Money::minor((string) ($source->getAttribute('due_adjusted') ?? '0.00'));
        $collected      = Money::minor((string) ($source->getAttribute('collected') ?? '0.00'));

        return [
            'remaining_amount'  => Money::decimal(Money::minor($source->payable_amount) - $cash - $dueAdjustment),
            'remaining_paid'    => Money::decimal(Money::minor($source->paid_amount) + $collected - $cash),
            'remaining_due'     => Money::decimal(Money::minor($source->due_amount) - $collected - $dueAdjustment),
            'credit'            => Money::decimal($cash + $dueAdjustment),
            'refunded_gross'    => Money::decimal(Money::minor((string) ($source->getAttribute('refunded_gross') ?? '0.00'))),
        ];
    }

    public function options(array $queries, array $filters, string $defaultService, Closure $eligible): Paginator
    {
        $service    = $filters['service'] ?? $defaultService;
        $page       = max(1, (int) ($filters['page'] ?? 1));
        $offset     = ($page - 1) * 20;
        $skipped    = 0;
        $rows       = [];

        foreach ($queries as $rank => $query) {
            if ($service !== 'all' && $query->getModel()::SERVICE !== $service) {
                continue;
            }

            $query->with('patient:id,patient_id,name,mobile_no')->whereHas('patient');
            $search = trim($filters['search'] ?? '');
            if ($search !== '') {
                $pattern = '%'.addcslashes($search, '\\%_').'%';
                $query->where(fn (Builder $invoices) => $invoices
                    ->whereLike('invoice_no', $pattern)
                    ->orWhereHas('patient', fn (Builder $patients) => $patients
                        ->whereLike('patient_id', $pattern)
                        ->orWhereLike('name', $pattern)
                        ->orWhereLike('mobile_no', $pattern)));
            }

            $query->orderBy('id');
            foreach ($query->lazy(100) as $source) {
                $balance = $this->balanceValues($source);
                if (! $eligible($balance)) {
                    continue;
                }
                if ($skipped < $offset) {
                    $skipped++;

                    continue;
                }

                $rows[] = $this->invoiceRow($source, $balance, $rank + 1);
                if (count($rows) === 21) {
                    break 2;
                }
            }
        }

        return new Paginator($rows, 20, $page, [
            'path' => request()->url(),
        ]);
    }

    private function invoiceRow(Consultation|PatientInvestigation|PatientOperation $source, array $balance, int $rank): object
    {
        return (object) [
            'service'       => $source::SERVICE,
            'service_rank'  => $rank,
            'source_id'     => $source->getKey(),
            'invoice_no'    => $source->invoice_no,
            'paid_amount'   => $source->paid_amount,
            'due_amount'    => $source->due_amount,
            'payable_amount' => $source->payable_amount,
            ...$balance,
            'refund_count'  => (int) $source->getAttribute('refund_count'),
            'patient_id'    => $source->patient->patient_id,
            'patient_name'  => $source->patient->name,
            'mobile_no'     => $source->patient->mobile_no,
        ];
    }

    public function option(BaseModel $source, array $state): array
    {
        $patient    = $source->patient()->first(['id', 'name', 'mobile_no']);
        $service    = $source::SERVICE;
        $parts      = array_filter([$source->invoice_no, $patient?->name, $patient?->mobile_no], fn ($value): bool => filled($value));
        $parts[]    = ucfirst($service);

        return [
            'service'           => $service,
            'source_id'         => $source->getKey(),
            'invoice_no'        => $source->invoice_no,
            'patient_name'      => $patient?->name,
            'mobile_no'         => $patient?->mobile_no,
            'paid_amount'       => $source->paid_amount,
            'due_amount'        => $source->due_amount,
            'payable_amount'    => $source->payable_amount,
            'remaining_paid'    => Money::decimal($state['remaining_paid']),
            'remaining_due'     => Money::decimal($state['remaining_due']),
            'remaining_amount'  => Money::decimal($state['remaining_amount']),
            'id'                => $service.':'.$source->getKey(),
            'text'              => implode(' — ', $parts),
            'service_label'     => ucfirst($service),
        ];
    }
}
