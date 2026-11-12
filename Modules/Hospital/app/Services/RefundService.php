<?php

namespace Modules\Hospital\Services;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Hospital\Models\BaseModel;
use Modules\Hospital\Models\Consultation;
use Modules\Hospital\Models\Investigation;
use Modules\Hospital\Models\PatientInvestigation;
use Modules\Hospital\Models\PatientOperation;
use Modules\Hospital\Models\Refund;

class RefundService extends ClinicalEntryService
{
    public function save(Account $actor, ?Refund $record, array $values, int $branchId): Refund
    {
        try {
            return $this->write($actor, $record ?? new Refund, $branchId, function (Refund $refund) use ($values, $branchId): Refund {
                $items = [];
                $source = $refund->exists
                    ? $this->invoices->source($refund, lock: true)
                    : $this->invoices->find($refund, $values['invoice'], $branchId, lock: true);

                if (! $refund->exists) {
                    $state = $this->refundState($source);
                    if ($state['legacy'] || (! $source instanceof PatientInvestigation && $state['has_refunds'])) {
                        throw ValidationException::withMessages(['invoice' => 'This invoice has already been refunded.']);
                    }

                    $credit = $state['remaining_amount'];
                    if ($source instanceof PatientInvestigation) {
                        $selectedIds = array_map('intval', $values['item_ids']);
                        $available = array_diff_key($this->refundItemValues($source), array_flip($state['used_item_ids']));
                        if (array_diff($selectedIds, array_keys($available)) !== []) {
                            throw ValidationException::withMessages(['item_ids' => 'Select available items from this invoice. An item cannot be refunded twice.']);
                        }
                        $credit = 0;
                        foreach ($selectedIds as $id) {
                            $item = $available[$id];
                            $credit += Money::minor($item['amount']);
                            $items[$id] = [
                                'invoice_id' => $source->getKey(),
                                'rate' => $item['rate'],
                                'amount' => $item['amount'],
                                'eye_type' => $item['eye_type'],
                                'position' => $item['position'],
                            ];
                        }
                    }

                    if ($credit <= 0 || $credit > $state['remaining_amount']) {
                        throw ValidationException::withMessages(['invoice' => 'The selected invoice items have no refundable balance.']);
                    }
                    $dueAdjustment = min($credit, $state['remaining_due']);
                    $cash = $credit - $dueAdjustment;
                    $column = $source->{$source::REFUND_RELATION}()->getForeignKeyName();
                    $refund->setAttribute($column, $source->getKey());
                    $refund->amount = Money::decimal($cash);
                    $refund->due_adjustment = Money::decimal($dueAdjustment);
                    $settings = Setting::values('hospital', $refund->getConnectionName());
                    $this->assignInvoiceNumber($refund, 'refund_invoice', now()->toDateString(), $settings, $source->doctor_id);
                }

                $refund->fill($values);
                $refund->save();
                if ($items !== []) {
                    $refund->items()->attach($items);
                }

                if (! $refund->wasRecentlyCreated || ! $settings['sms_refund_enabled']) {
                    return $refund;
                }

                $patient = $source->patient()->firstOrFail();
                $this->sms->queue(
                    $refund,
                    'hospital.refund',
                    'refund:'.$refund->getKey().':created',
                    $patient->mobile_no,
                    true,
                    $settings['sms_refund_template'],
                    [
                        ...$patient->smsValues(),
                        'name' => $patient->name,
                        'invoice' => $refund->invoice_no,
                        'date' => $refund->created_at->format('d-m-Y'),
                        'total' => Money::decimal($state['remaining_amount'] - $credit),
                        'paid' => Money::decimal($state['remaining_paid'] - $cash),
                        'due' => Money::decimal($state['remaining_due'] - $dueAdjustment),
                        'amount' => $refund->amount,
                    ],
                );

                return $refund;
            });
        } catch (UniqueConstraintViolationException $exception) {
            $constraint = $exception->errorInfo[2] ?? '';
            if (str_contains($constraint, 'refunds_consultation_id_unique')
                || str_contains($constraint, 'refunds_operation_id_unique')) {
                throw ValidationException::withMessages(['invoice' => 'This invoice has already been refunded.']);
            }
            if (str_contains($constraint, 'refund_items_invoice_id_item_id_unique')) {
                throw ValidationException::withMessages(['item_ids' => 'A selected item has already been refunded. Reload the invoice.']);
            }
            throw $exception;
        }
    }

    public function preview(Account $actor, string $invoice, int $branchId, ?Refund $current = null): array
    {
        $refund = $current ?? new Refund;
        $this->authorize($actor, $refund, $refund->exists ? 'update' : 'create');
        $source = $refund->exists
            ? $this->invoices->source($refund)
            : $this->invoices->find($refund, $invoice, $branchId);
        $state = $this->refundState($source, $current);
        $items = [];
        if ($current?->exists) {
            $items = $current->items()
                ->get(['investigations.id', 'investigations.name', 'investigations.room_no'])
                ->map(fn (Investigation $item): array => [
                    'id' => $item->getKey(),
                    'name' => $item->name,
                    'room' => $item->room_no,
                    'eye_type' => $item->pivot->eye_type,
                    'eye_label' => eyeTypes()[$item->pivot->eye_type] ?? ucfirst($item->pivot->eye_type),
                    'rate' => $item->pivot->rate,
                    'amount' => $item->pivot->amount,
                    'position' => $item->pivot->position,
                ])
                ->all();
        } elseif ($source instanceof PatientInvestigation && ! $state['legacy']) {
            $items = array_values(array_diff_key($this->refundItemValues($source), array_flip($state['used_item_ids'])));
        }

        return [
            'invoice' => $this->invoices->option($source, $state),
            'service' => $source::SERVICE,
            'invoice_no' => $source->invoice_no ?: '',
            'paid_amount' => $source->paid_amount,
            'due_amount' => $source->due_amount,
            'remaining_paid' => Money::decimal($state['remaining_paid']),
            'remaining_due' => Money::decimal($state['remaining_due']),
            'remaining_amount' => Money::decimal($state['remaining_amount']),
            'items' => $items,
            'selected_item_ids' => array_column($items, 'id'),
        ];
    }

    public function invoices(Account $actor, int $branchId, array $filters): Paginator
    {
        $this->authorize($actor, new Refund, 'create');
        $consultations = Consultation::where('branch_id', $branchId)
            ->doesntHave('refund');
        $investigations = PatientInvestigation::where('branch_id', $branchId)
            ->whereDoesntHave('refunds', fn (Builder $refunds) => $refunds->doesntHave('items'))
            ->whereHas('investigations', fn (Builder $items) => $items
                ->whereDoesntHave('refunds', fn (Builder $refunds) => $refunds
                    ->whereColumn('refunds.investigation_id', 'patient_investigations.id')));
        $operations = PatientOperation::where('branch_id', $branchId)
            ->doesntHave('refund');
        $queries = [
            $this->invoices->withLedger($consultations),
            $this->invoices->withLedger($investigations),
            $this->invoices->withLedger($operations),
        ];
        foreach ($queries as $query) {
            $query->where('payable_amount', '>', 0);
        }

        return $this->invoices->options($queries, $filters, 'investigation',
            fn (array $balance): bool => $balance['remaining_amount'] > 0,
        );
    }

    public function invoiceData(Account $actor, Refund $refund): array
    {
        $this->authorize($actor, $refund, 'view');
        $source = $this->invoices->source($refund);
        $state = $this->invoices->balance($source);
        $credit = Money::minor($refund->amount) + Money::minor($refund->due_adjustment);
        $createdAt = $refund->created_at?->copy()->timezone(config('app.timezone'));
        $data = $this->invoiceValues($actor, $source, $state);

        return $data + [
            'pageTitle' => 'Refund voucher',
            'documentTitle' => 'Refund voucher',
            'invoiceNumber' => $refund->invoice_no ?: '—',
            'originalInvoiceNumber' => filled($source->invoice_no) ? $source->invoice_no : '#'.$source->getKey(),
            'entryDate' => $refund->created_at?->format('d M Y') ?? '—',
            'entryDateLabel' => 'Date / Time',
            'entryTime' => $createdAt?->format('h:i A') ?? '—',
            'lines' => $this->refundLines($refund, $source),
            'refundAmount' => $refund->amount,
            'refundDueAdjustment' => $refund->due_adjustment,
            'refundCreditAmount' => Money::decimal($credit),
            'remarks' => $refund->remarks,
            'refundCause' => $refund->cause()->value('name'),
            'amountWords' => Money::words($credit),
            'status' => 'REFUNDED',
            'backUrl' => route('hospital.refunds.index'),
        ];
    }

    public function delete(Account $actor, Model $record): void
    {
        $this->authorize($actor, $record, 'delete');

        $actor->withAccessLock(function (Account $freshActor) use ($record): void {
            $refund = $record->newQuery()
                ->whereKey($record->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $this->authorize($freshActor, $refund, 'delete');
            $this->invoices->source($refund, lock: true);
            $refund->delete();
        });
    }

    // Item tracking belongs to refunds; common ledger arithmetic receives the same rows.
    private function refundState(BaseModel $source, ?Refund $excluded = null): array
    {
        $tracksItems = $source instanceof PatientInvestigation;
        $query = $source->{$source::REFUND_RELATION}()
            ->select(['id', 'amount', 'due_adjustment']);
        if ($excluded?->exists) {
            $query->whereKeyNot($excluded->getKey());
        }
        if ($tracksItems) {
            $query->withCount('items');
        }
        $refunds = $query->get();
        $state = $this->invoices->balance($source, $refunds);

        return $state + [
            'legacy' => $tracksItems && $refunds->contains('items_count', 0),
            'used_item_ids' => $tracksItems && $refunds->isNotEmpty()
                ? $source->refundedItems()
                    ->wherePivotIn('refund_id', $refunds->modelKeys())
                    ->pluck('refund_items.item_id')
                    ->all()
                : [],
        ];
    }

    private function refundItemValues(PatientInvestigation $source): array
    {
        $gross = Money::minor($source->amount);
        $net = Money::minor($source->payable_amount);
        $items = [];
        $remainders = [];
        $allocated = 0;
        $savedGross = 0;
        foreach ($source->investigations()->orderBy('investigations.id')->get(['investigations.id', 'investigations.name', 'investigations.room_no']) as $item) {
            $rate = Money::minor($item->pivot->rate);
            // Exact integer shares avoid overflow and keep total discount allocation stable.
            [$share, $remainder] = $gross > 0
                ? BigInteger::of($rate)->multipliedBy($net)->quotientAndRemainder($gross)
                : [BigInteger::zero(), BigInteger::zero()];
            $minor = $share->toInt();
            $id = $item->getKey();
            $items[$id] = [
                'id' => $id,
                'name' => $item->name,
                'room' => $item->room_no,
                'eye_type' => $item->pivot->eye_type,
                'eye_label' => eyeTypes()[$item->pivot->eye_type] ?? ucfirst($item->pivot->eye_type),
                'rate' => Money::decimal($rate),
                'amount' => Money::decimal($minor),
                'position' => (int) $item->pivot->position,
            ];
            $remainders[$id] = $remainder->toInt();
            $allocated += $minor;
            $savedGross += $rate;
        }
        if ($savedGross !== $gross || $net > $gross) {
            throw ValidationException::withMessages(['invoice' => 'The saved item prices do not match this invoice. Review the invoice before refunding.']);
        }
        $ids = array_keys($items);
        usort($ids, fn (int $left, int $right): int => ($remainders[$right] <=> $remainders[$left])
            ?: ($items[$left]['position'] <=> $items[$right]['position']) ?: ($left <=> $right));
        for ($position = 0; $position < $net - $allocated; $position++) {
            $id = $ids[$position];
            $items[$id]['amount'] = Money::decimal(Money::minor($items[$id]['amount']) + 1);
        }

        return $items;
    }

    private function refundLines(Refund $refund, BaseModel $source): array
    {
        if ($source instanceof PatientInvestigation) {
            $items = $refund->items()->orderByPivot('position')->get(['investigations.id', 'investigations.name', 'investigations.room_no']);
            if ($items->isNotEmpty()) {
                return $items->map(fn (Investigation $item): array => [
                    'name' => $item->name,
                    'room' => $item->room_no,
                    'eye' => eyeTypes()[$item->pivot->eye_type] ?? ucfirst($item->pivot->eye_type),
                    'amount' => $item->pivot->amount,
                ])->all();
            }

            // Earlier whole-invoice refunds have no item snapshots.
            return [[
                'name' => 'Investigation invoice refund',
                'room' => null,
                'eye' => null,
                'amount' => Money::decimal(Money::minor($refund->amount) + Money::minor($refund->due_adjustment)),
            ]];
        }
        $amount = Money::decimal(Money::minor($refund->amount) + Money::minor($refund->due_adjustment));
        if ($source instanceof PatientOperation) {
            return [[
                'name' => $source->operation()->value('name') ?? 'Operation',
                'room' => null,
                'eye' => eyeTypes()[$source->eye_type] ?? ucfirst($source->eye_type),
                'amount' => $amount,
            ]];
        }

        return [[
            'name' => 'Consultation — '.(patientTypes()[$source->patient_type] ?? $source->patient_type),
            'room' => null,
            'eye' => null,
            'amount' => $amount,
        ]];
    }
}
