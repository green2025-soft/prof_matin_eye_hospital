<?php

namespace Modules\Hospital\Services;

use Brick\Math\BigInteger;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\LazyCollection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Hospital\Models\BaseModel;
use Modules\Hospital\Models\Consultation;
use Modules\Hospital\Models\PatientInvestigation;
use Modules\Hospital\Models\PatientOperation;

class CommissionService
{
    public function __construct(private ClinicalInvoiceService $invoices) {}

    private function allowsCommissions(Account $actor, string $action): bool
    {
        $permissions = request()->attributes->get('navigation_permissions', []);

        return $actor->matchesApplication()
            && ($permissions['hospital.view'] ?? false)
            && ($permissions['commissions.'.$action] ?? false);
    }

    public function commissions(Account $actor, array $filters, int $branchId): LengthAwarePaginator
    {
        if (! $this->allowsCommissions($actor, 'view')) {
            throw new AuthorizationException('Your account or plan does not include commissions.');
        }

        $queries = $this->invoices->sources($branchId);
        $total = 0;
        foreach ($queries as $query) {
            $query->with('reference:id,reference_id,name')
                ->whereHas('reference', fn (Builder $reference) => $reference->where('branch_id', $branchId))
                ->whereNotNull('reference_commission_percent')
                ->whereNotNull('reference_discount_mode')
                ->whereNotNull('reference_due_mode');
            if (filled($filters['date_from'] ?? null)) {
                $query->where('entry_date', '>=', $filters['date_from']);
            }
            if (filled($filters['date_to'] ?? null)) {
                $query->where('entry_date', '<=', $filters['date_to']);
            }
            if (filled($filters['filter_reference_id'] ?? null)) {
                $query->where('reference_id', $filters['filter_reference_id']);
            }

            $total += (clone $query)->count();
            $query->orderByDesc('entry_date')
                ->orderByDesc('id');
        }

        $size = $filters['per_page'] ?? config('pagination.default');
        $all = $size === 'all';
        $size = $all ? 'all' : (int) $size;
        $page = $all ? 1 : max(1, (int) ($filters['page'] ?? 1));
        $perPage = $all ? max(1, $total) : $size;
        $offset = ($page - 1) * $perPage;
        $rows = collect();
        if ($offset < $total) {
            $rows = $this->orderedInvoices($queries)
                ->skip($offset)
                ->take($perPage)
                ->map($this->commissionRow(...))
                ->collect();
        }

        $records = new LengthAwarePaginator($rows, $total, $perPage, $page, [
            'path' => request()->url(),
        ]);
        $records->appends(Arr::only($filters, ['date_from', 'date_to', 'filter_reference_id']) + ['per_page' => $size]);

        return $records;
    }

    public function setCommissionStatus(Account $actor, string $service, int $invoiceId, string $status, int $branchId): BaseModel
    {
        return $actor->withAccessLock(function (Account $freshActor) use ($service, $invoiceId, $status, $branchId): BaseModel {
            if (! $this->allowsCommissions($freshActor, 'update')) {
                throw new AuthorizationException('Your account or plan does not include commission updates.');
            }
            $query = match ($service) {
                'consultation' => Consultation::whereKey($invoiceId),
                'investigation' => PatientInvestigation::whereKey($invoiceId),
                'operation' => PatientOperation::whereKey($invoiceId),
            };
            $entry = $query->where('branch_id', $branchId)
                ->lockForUpdate()
                ->firstOrFail();
            if ($entry->reference_id === null || $entry->reference_commission_percent === null
                || $entry->reference_discount_mode === null || $entry->reference_due_mode === null) {
                throw ValidationException::withMessages(['target_status' => 'Only new invoices with a Reference commission can be marked Paid or Unpaid.']);
            }
            if ($status === 'paid' && $entry->reference_commission_paid_at !== null) {
                return $entry;
            }

            if ($status === 'unpaid') {
                $entry->reference_commission_paid_amount = null;
                $entry->reference_commission_paid_at = null;
            } else {
                $source = $this->invoices->withLedger($entry->newQuery()->whereKey($entry->getKey()))
                    ->firstOrFail();
                $amounts = $this->referenceCommissionAmounts($entry, $this->invoices->balanceValues($source));
                if ($amounts['commission'] === '0.00' || $amounts['pending'] !== '0.00') {
                    throw ValidationException::withMessages(['target_status' => 'The full commission must be eligible before it can be marked Paid.']);
                }
                $entry->reference_commission_paid_amount = $amounts['commission'];
                $entry->reference_commission_paid_at = now(config('app.timezone'));
            }
            if ($entry->isDirty(['reference_commission_paid_amount', 'reference_commission_paid_at'])) {
                $entry->save();
            }

            return $entry;
        });
    }

    private function commissionRow(Consultation|PatientInvestigation|PatientOperation $source): array
    {
        $amounts = $this->referenceCommissionAmounts($source, $this->invoices->balanceValues($source));
        $paidAmount = $source->reference_commission_paid_amount ?? '0.00';
        $isPaid = $source->reference_commission_paid_at !== null;

        return [
            'service' => $source::SERVICE,
            'source_id' => $source->getKey(),
            'service_label' => clinicalServiceOptions()[$source::SERVICE],
            'invoice_no' => $source->invoice_no,
            'entry_date' => $source->entry_date->toDateString(),
            'reference_id' => $source->reference_id,
            'reference_code' => $source->reference->reference_id,
            'reference_name' => $source->reference->name,
            'percent' => $amounts['percent'],
            'commission' => $amounts['commission'],
            'eligible' => $amounts['eligible'],
            'pending' => $amounts['pending'],
            'paid_amount' => $paidAmount,
            'paid_at' => $source->reference_commission_paid_at?->format('d M Y'),
            'status' => $isPaid ? 'paid' : 'unpaid',
            'can_pay' => ! $isPaid && $amounts['commission'] !== '0.00' && $amounts['pending'] === '0.00',
            'can_unpay' => $isPaid,
            'overpaid' => Money::decimal(max(0, Money::minor($paidAmount) - Money::minor($amounts['eligible']))),
        ];
    }

    private function referenceCommissionAmounts(Consultation|PatientInvestigation|PatientOperation $source, array $balance): array
    {
        $result = [
            'percent' => $source->reference_commission_percent,
            'commission' => '0.00',
            'eligible' => '0.00',
            'pending' => '0.00',
        ];

        $net = Money::minor($balance['remaining_amount']);
        $paid = Money::minor($balance['remaining_paid']);
        $due = Money::minor($balance['remaining_due']);
        $credit = Money::minor($balance['credit']);
        if ($credit > 0 && $net === 0) {
            return $result;
        }

        $gross = Money::minor($source->amount) - Money::minor($balance['refunded_gross']);
        if ($gross < $net || $net !== $paid + $due) {
            throw ValidationException::withMessages(['invoice' => 'The invoice and refund balances are inconsistent. Review its payments before calculating Reference commission.']);
        }
        $basis = $source->reference_discount_mode === 'net' ? $net : $gross;
        $basisPoints = Money::minor($source->reference_commission_percent);
        $commission = BigInteger::of($basis)->multipliedBy($basisPoints)->plus(5000)->quotient(10000)->toInt();
        if ($source->reference_discount_mode === 'subtract_discount') {
            $commission = max(0, $commission - ($gross - $net));
        }

        $eligible = match ($source->reference_due_mode) {
            'full_payment' => $due === 0 ? $commission : 0,
            'deduct_due' => max(0, $commission - $due),
            'proportional' => $net === 0 ? 0 : BigInteger::of($commission)->multipliedBy($paid)
                ->plus(intdiv($net, 2))->quotient($net)->toInt(),
        };

        return array_replace($result, [
            'commission' => Money::decimal($commission),
            'eligible' => Money::decimal($eligible),
            'pending' => Money::decimal($commission - $eligible),
        ]);
    }

    // Merge sorted sources without loading the entire commission ledger.
    private function orderedInvoices(array $queries): LazyCollection
    {
        return new LazyCollection(function () use ($queries) {
            $streams = [];
            foreach ($queries as $rank => $query) {
                $stream = (clone $query)->lazy(100)->getIterator();
                $stream->rewind();
                if ($stream->valid()) {
                    $streams[$rank] = $stream;
                }
            }

            while ($streams !== []) {
                $selected = array_key_first($streams);
                foreach ($streams as $rank => $stream) {
                    if ($stream->current()->entry_date->gt($streams[$selected]->current()->entry_date)) {
                        $selected = $rank;
                    }
                }

                yield $streams[$selected]->current();
                $streams[$selected]->next();
                if (! $streams[$selected]->valid()) {
                    unset($streams[$selected]);
                }
            }
        });
    }
}
