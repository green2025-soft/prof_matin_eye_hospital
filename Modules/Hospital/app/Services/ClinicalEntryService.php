<?php

namespace Modules\Hospital\Services;

use Brick\Math\BigInteger;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Illuminate\Validation\ValidationException;
use LogicException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;
use Modules\Hospital\Models\Appointment;
use Modules\Hospital\Models\BaseModel;
use Modules\Hospital\Models\Consultation;
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Models\DueCollection;
use Modules\Hospital\Models\Investigation;
use Modules\Hospital\Models\Operation;
use Modules\Hospital\Models\Patient;
use Modules\Hospital\Models\PatientInvestigation;
use Modules\Hospital\Models\PatientOperation;
use Modules\Hospital\Models\Reference;
use Modules\Hospital\Models\Refund;
use Modules\Hospital\Models\RefundCause;
use Modules\TenantCore\Models\Branch;
use Modules\TenantCore\Models\PaymentType;

class ClinicalEntryService
{
    public function __construct(private AccessManager $access) {}

    public function allowsCommissions(Account $actor, string $action, int $branchId): bool
    {
        if (! in_array($action, ['view', 'update'], true) || ! $this->access->actorMatchesContext($actor)
            || $branchId < 1 || $branchId !== (int) request()->attributes->get('branch_id')) {
            return false;
        }

        $permission = 'commissions.'.$action;
        if (! $this->access->isOwner($actor) && (! $actor->can('hospital.view') || ! $actor->can($permission))) {
            return false;
        }
        $tenant = Tenant::current();
        $subscription = $tenant === null ? null : Subscription::forTenant($tenant);

        return $subscription?->isUsable() && $subscription->plan->allowsRoutePermission('hospital.view')
            && $subscription->plan->allowsRoutePermission($permission);
    }

    public function commissions(Account $actor, array $filters, int $branchId): LengthAwarePaginator
    {
        if (! $this->allowsCommissions($actor, 'view', $branchId)) {
            throw new AuthorizationException('Your account or plan does not include commissions.');
        }

        $query = $this->invoiceBalancesQuery($branchId, commissionFields: true)
            ->join('references as referrer', function (JoinClause $join) use ($branchId): void {
                $join->on('referrer.id', '=', 'invoices.reference_id')->where('referrer.branch_id', $branchId);
            })
            ->whereNotNull('invoices.reference_commission_percent')
            ->whereNotNull('invoices.reference_discount_mode')->whereNotNull('invoices.reference_due_mode')
            ->select('invoices.*', 'referrer.reference_id as reference_code', 'referrer.name as reference_name');
        if (filled($filters['date_from'] ?? null)) {
            $query->where('invoices.entry_date', '>=', $filters['date_from']);
        }
        if (filled($filters['date_to'] ?? null)) {
            $query->where('invoices.entry_date', '<=', $filters['date_to']);
        }
        if (filled($filters['filter_reference_id'] ?? null)) {
            $query->where('invoices.reference_id', $filters['filter_reference_id']);
        }

        $size = $filters['per_page'] ?? config('pagination.default');
        $all = $size === 'all';
        $size = $all ? 'all' : (int) $size;
        if (! in_array($size, config('pagination.options'), true)) {
            $size = config('pagination.default');
        }
        $page = $all ? 1 : max(1, (int) ($filters['page'] ?? 1));
        $perPage = $all ? fn (int $total): int => max(1, $total) : $size;
        $records = $query->orderByDesc('invoices.entry_date')->orderBy('invoices.service_rank')->orderByDesc('invoices.source_id')
            ->paginate($perPage, ['*'], 'page', $page)
            ->appends(Arr::only($filters, ['date_from', 'date_to', 'filter_reference_id']) + ['per_page' => $size]);
        $records->setCollection($records->getCollection()->map($this->commissionRow(...)));

        return $records;
    }

    public function setCommissionStatus(Account $actor, string $service, int $invoiceId, string $status, int $branchId): BaseModel
    {
        if (! $this->allowsCommissions($actor, 'update', $branchId)) {
            throw new AuthorizationException('Your account or plan does not include commission updates.');
        }
        if (! in_array($status, ['paid', 'unpaid'], true)) {
            throw ValidationException::withMessages(['target_status' => 'Select Paid or Unpaid.']);
        }

        return $this->access->transaction($actor, function (Account $freshActor) use ($service, $invoiceId, $status, $branchId): BaseModel {
            if (! $this->allowsCommissions($freshActor, 'update', $branchId)) {
                throw new AuthorizationException('Your account or plan does not include commission updates.');
            }
            $entry = $this->findInvoice($service.':'.$invoiceId, $branchId, lock: true);
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
                $balance = $this->invoiceSourceQuery($branchId, $entry->getTable(), $service, $this->sourceColumn($entry), 1, [$invoiceId])->first();
                $amounts = $this->referenceCommissionAmounts($entry, (array) $balance);
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

    private function commissionRow(object $invoice): array
    {
        $model = match ($invoice->service) {
            'consultation' => new Consultation,
            'investigation' => new PatientInvestigation,
            'operation' => new PatientOperation,
        };
        $source = $model->newFromBuilder(Arr::only((array) $invoice, [
            'amount', 'reference_id', 'reference_commission_percent', 'reference_discount_mode', 'reference_due_mode',
            'reference_commission_paid_amount', 'reference_commission_paid_at',
        ]), $model->getConnectionName());
        $amounts = $this->referenceCommissionAmounts($source, (array) $invoice);
        $paidAmount = $source->reference_commission_paid_amount ?? '0.00';
        $isPaid = $source->reference_commission_paid_at !== null;

        return [
            'service' => $invoice->service, 'source_id' => (int) $invoice->source_id,
            'service_label' => clinicalServiceOptions()[$invoice->service],
            'invoice_no' => $invoice->invoice_no, 'entry_date' => $invoice->entry_date,
            'reference_id' => (int) $invoice->reference_id, 'reference_code' => $invoice->reference_code, 'reference_name' => $invoice->reference_name,
            'percent' => $amounts['percent'], 'commission' => $amounts['commission'], 'eligible' => $amounts['eligible'], 'pending' => $amounts['pending'],
            'paid_amount' => $paidAmount, 'paid_at' => $source->reference_commission_paid_at?->format('d M Y'),
            'status' => $isPaid ? 'paid' : 'unpaid',
            'can_pay' => ! $isPaid && $amounts['commission'] !== '0.00' && $amounts['pending'] === '0.00',
            'can_unpay' => $isPaid,
            'overpaid' => Money::decimal(max(0, Money::minor($paidAmount) - Money::minor($amounts['eligible']))),
        ];
    }

    public function allowsInvestigationDelivery(Account $actor, string $action, int $branchId, ?PatientInvestigation $record = null): bool
    {
        if (! in_array($action, ['view', 'update'], true) || ! $this->access->actorMatchesContext($actor)
            || $branchId < 1 || $branchId !== (int) request()->attributes->get('branch_id')) {
            return false;
        }
        if ($record !== null && (! $record->exists || $record->branch_id !== $branchId || ! $record->matchesBranchContext()
            || $record->getConnectionName() !== $actor->getConnectionName()
            || $record->getConnection()->getDatabaseName() !== $actor->getConnection()->getDatabaseName())) {
            return false;
        }

        $permission = 'delivery-investigations.'.$action;
        if (! $this->access->isOwner($actor) && (! $actor->can('hospital.view') || ! $actor->can($permission))) {
            return false;
        }

        $tenant = Tenant::current();
        $subscription = $tenant === null ? null : Subscription::forTenant($tenant);

        return $subscription?->isUsable() && $subscription->plan->allowsRoutePermission('hospital.view')
            && $subscription->plan->allowsRoutePermission($permission);
    }

    public function setInvestigationDeliveryStatus(Account $actor, PatientInvestigation $record, string $status, int $branchId): PatientInvestigation
    {
        if (! $this->allowsInvestigationDelivery($actor, 'update', $branchId, $record)) {
            throw new AuthorizationException('Your account or plan does not include investigation delivery updates.');
        }

        return $this->access->transaction($actor, function (Account $freshActor) use ($record, $status, $branchId): PatientInvestigation {
            $entry = $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail();
            if (! $this->allowsInvestigationDelivery($freshActor, 'update', $branchId, $entry)) {
                throw new AuthorizationException('Your account or plan does not include investigation delivery updates.');
            }

            $entry->delivery_status = $status;
            if ($entry->isDirty('delivery_status')) {
                $entry->save();
            }

            return $entry;
        });
    }

    public function saveAppointment(Account $actor, ?Appointment $record, array $values, int $branchId): Appointment
    {
        return $this->write($actor, $record ?? new Appointment, $values, $branchId, function (Appointment $entry) use ($values): Appointment {
            $entry->fill($values);
            $this->assignNumbers($entry, 'appointment', $values['appointment_date']);
            $entry->save();

            return $entry;
        });
    }

    public function saveConsultation(Account $actor, ?Consultation $record, array $values, int $branchId): Consultation
    {
        return $this->write($actor, $record ?? new Consultation, $values, $branchId, function (Consultation $entry, Doctor $doctor) use ($values, $branchId): Consultation {
            $appointmentId = $values['appointment_id'] ?? null;
            if ($appointmentId !== null) {
                $appointment = Appointment::query()->whereKey($appointmentId)->lockForUpdate()->first();
                if ($appointment === null || $appointment->branch_id !== $branchId
                    || $appointment->patient_id !== (int) $values['patient_id'] || $appointment->doctor_id !== $doctor->getKey()) {
                    throw ValidationException::withMessages(['appointment_id' => 'The appointment must match the selected patient, doctor, and branch.']);
                }
            }

            $sameFee = $entry->exists && $entry->doctor_id === $doctor->getKey() && $entry->patient_type === $values['patient_type'];
            $fee = $values['patient_type'] === 'follow_up' ? $doctor->follow_up_fee : $doctor->consultation_fee;
            $amount = Money::minor($sameFee ? $entry->amount : $fee);

            $entry->fill(Arr::only($values, $entry->getFillable()));
            $entry->appointment_id = $appointmentId;
            $entry->fill($this->totals($values, $amount, percentDiscount: true));
            $this->assignReference($entry, $values, $branchId, 'consultation');
            $this->assignNumbers($entry, 'consultation', $values['entry_date']);
            $entry->save();

            return $entry;
        });
    }

    public function saveInvestigation(Account $actor, ?PatientInvestigation $record, array $values, int $branchId): PatientInvestigation
    {
        return $this->write($actor, $record ?? new PatientInvestigation, $values, $branchId, function (PatientInvestigation $entry) use ($values, $branchId): PatientInvestigation {
            $ids = $values['investigation_ids'];
            $eyes = $values['investigation_eyes'];
            if (count($ids) !== count($eyes) || array_diff(array_map('strval', array_keys($eyes)), array_map('strval', $ids)) !== []) {
                throw ValidationException::withMessages(['investigation_ids' => 'Each selected test must have its own eye type.']);
            }

            $tests = Investigation::query()->whereKey($ids)->orderBy('id')->sharedLock()->get()->keyBy('id');
            if ($tests->count() !== count($ids)) {
                throw ValidationException::withMessages(['investigation_ids' => 'Select valid investigations from the catalog.']);
            }

            $previousItems = $entry->exists ? $entry->investigations()->get()->keyBy('id') : collect();
            $items = [];
            $amount = 0;
            foreach (array_values($ids) as $position => $id) {
                $eye = $eyes[$id];
                if (! array_key_exists($eye, eyeTypes())) {
                    throw ValidationException::withMessages(['investigation_ids' => 'Select a valid eye type for every test.']);
                }
                $test = $tests->get($id);
                $previousItem = $previousItems->get($id);
                $sameEye = $previousItem !== null && $previousItem->pivot->eye_type === $eye;
                $rate = $sameEye ? $previousItem->pivot->rate : ($eye === 'both' ? $test->both_rate : $test->single_rate);
                $amount += Money::minor($rate);
                $items[$id] = ['rate' => $rate, 'eye_type' => $eye, 'position' => $position];
            }

            $entry->fill(Arr::only($values, $entry->getFillable()));
            $entry->fill($this->totals($values, $amount, percentDiscount: true, extraDiscount: true));
            $this->assignReference($entry, $values, $branchId, 'investigation');
            $this->assignNumbers($entry, 'investigation', $values['entry_date']);
            $entry->save();
            $entry->investigations()->sync($items);

            return $entry;
        });
    }

    public function saveOperation(Account $actor, ?PatientOperation $record, array $values, int $branchId): PatientOperation
    {
        return $this->write($actor, $record ?? new PatientOperation, $values, $branchId, function (PatientOperation $entry) use ($values, $branchId): PatientOperation {
            $operation = Operation::query()->whereKey($values['operation_id'])->sharedLock()->first();
            if ($operation === null) {
                throw ValidationException::withMessages(['operation_id' => 'Select an operation from the catalog.']);
            }
            if (filled($values['consultant_id'] ?? null)
                && Doctor::query()->where('branch_id', $branchId)->whereKey($values['consultant_id'])->sharedLock()->first(['id']) === null) {
                throw ValidationException::withMessages(['consultant_id' => 'Select a consultant from this branch.']);
            }

            $sameRate = $entry->exists && $entry->operation_id === $operation->getKey() && $entry->eye_type === $values['eye_type'];
            $rate = $values['eye_type'] === 'both' ? $operation->both_rate : $operation->single_rate;
            $amount = Money::minor($sameRate ? $entry->amount : $rate);

            $entry->fill(Arr::only($values, $entry->getFillable()));
            $entry->fill($this->totals($values, $amount));
            $this->assignReference($entry, $values, $branchId, 'operation');
            $this->assignNumbers($entry, 'operation', $values['entry_date']);
            $entry->save();

            return $entry;
        });
    }

    public function saveDueCollection(Account $actor, ?DueCollection $record, array $values, int $branchId): DueCollection
    {
        $record ??= new DueCollection;
        if ($branchId < 1 || ($record->exists && $record->branch_id !== $branchId)) {
            throw new AuthorizationException('Select the branch that owns this collection.');
        }
        $action = $record->exists ? 'update' : 'create';
        $this->authorize($actor, $record, $action);

        try {
            return $this->access->transaction($actor, function (Account $freshActor) use ($record, $values, $branchId, $action): DueCollection {
                $collection = $record->exists ? $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail() : new DueCollection;
                if (! $collection->exists) {
                    $collection->branch_id = $branchId;
                }
                $this->authorize($freshActor, $collection, $action);
                $source = $collection->exists
                    ? $this->sourceInvoice($collection, lock: true)
                    : $this->findInvoice($values['invoice'], $branchId, lock: true);
                $state = $this->invoiceBalance($source);
                $previous = $collection->exists ? Money::minor($collection->amount) : 0;
                $amount = Money::minor($values['amount']);
                if ($amount <= 0 || $amount > $state['remaining_due'] + $previous) {
                    throw ValidationException::withMessages(['amount' => 'The collection must be positive and cannot exceed the outstanding due.']);
                }
                if ($state['remaining_paid'] + $amount - $previous < 0) {
                    throw ValidationException::withMessages(['amount' => 'This payment has funded a refund. Its amount cannot be reduced this far.']);
                }
                if (PaymentType::query()->whereKey($values['payment_type_id'])->sharedLock()->first(['id']) === null) {
                    throw ValidationException::withMessages(['payment_type_id' => 'Select an available payment type.']);
                }
                if (! $collection->exists) {
                    $column = $this->sourceColumn($source);
                    $collection->setAttribute($column, $source->getKey());
                    $this->assignNumbers($collection, 'due_collection', $values['collection_date'], $source->doctor_id);
                }
                $collection->fill($values);
                $collection->amount = Money::decimal($amount);
                $collection->save();

                return $collection;
            });
        } catch (UniqueConstraintViolationException $exception) {
            if (str_contains($exception->errorInfo[2] ?? '', 'due_collections_invoice_no_unique')) {
                throw ValidationException::withMessages(['record' => 'The generated Due Collection invoice number already exists. Choose a distinct pattern in Hospital settings.']);
            }
            throw $exception;
        } catch (QueryException $exception) {
            if ((int) ($exception->errorInfo[1] ?? 0) === 1452) {
                throw ValidationException::withMessages(['record' => 'The selected invoice or payment type has changed. Reload the form.']);
            }
            throw $exception;
        }
    }

    public function dueCollectionPreview(Account $actor, string $invoice, int $branchId, ?DueCollection $current = null): array
    {
        $this->authorize($actor, $current ?? new DueCollection, $current?->exists ? 'update' : 'create');
        $source = $current?->exists ? $this->sourceInvoice($current) : $this->findInvoice($invoice, $branchId);
        $state = $this->invoiceBalance($source);

        return [
            'invoice' => $this->sourceInvoiceOption($source, $state),
            'service' => $this->sourceService($source),
            'invoice_no' => $source->invoice_no ?: '',
            'remaining_due' => Money::decimal($state['remaining_due']),
            'remaining_paid' => Money::decimal($state['remaining_paid']),
            'remaining_amount' => Money::decimal($state['remaining_amount']),
            'available_due' => Money::decimal($state['remaining_due'] + ($current?->exists ? Money::minor($current->amount) : 0)),
        ];
    }

    public function saveRefund(Account $actor, ?Refund $record, array $values, int $branchId): Refund
    {
        $record ??= new Refund;
        if ($branchId < 1 || ($record->exists && $record->branch_id !== $branchId)) {
            throw new AuthorizationException('Select the branch that owns this refund.');
        }
        $action = $record->exists ? 'update' : 'create';
        $this->authorize($actor, $record, $action);

        try {
            return $this->access->transaction($actor, function (Account $freshActor) use ($record, $values, $branchId, $action): Refund {
                $refund = $record->exists ? $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail() : new Refund;
                if (! $refund->exists) {
                    $refund->branch_id = $branchId;
                }
                $this->authorize($freshActor, $refund, $action);

                $items = [];
                $source = $refund->exists
                    ? $this->sourceInvoice($refund, lock: true)
                    : $this->findInvoice($values['invoice'], $branchId, lock: true);

                if (! $refund->exists) {
                    $state = $this->invoiceBalance($source, includeRefundItems: true);
                    if ($state['legacy'] || (! $source instanceof PatientInvestigation && $this->sourceRefunds($source)->exists())) {
                        throw ValidationException::withMessages(['invoice' => 'This invoice has already been refunded.']);
                    }

                    $credit = $state['remaining_amount'];
                    if ($source instanceof PatientInvestigation) {
                        $selectedIds = array_map('intval', $values['item_ids']);
                        $available = array_diff_key($this->refundItemValues($source), array_flip($state['used_item_ids']));
                        if (count($selectedIds) !== count(array_unique($selectedIds)) || array_diff($selectedIds, array_keys($available)) !== []) {
                            throw ValidationException::withMessages(['item_ids' => 'Select available items from this invoice. An item cannot be refunded twice.']);
                        }
                        $credit = 0;
                        foreach ($selectedIds as $id) {
                            $item = $available[$id];
                            $credit += Money::minor($item['amount']);
                            $items[$id] = ['invoice_id' => $source->getKey(), 'rate' => $item['rate'], 'amount' => $item['amount'], 'eye_type' => $item['eye_type'], 'position' => $item['position']];
                        }
                    }

                    if ($credit <= 0 || $credit > $state['remaining_amount']) {
                        throw ValidationException::withMessages(['invoice' => 'The selected invoice items have no refundable balance.']);
                    }
                    $dueAdjustment = min($credit, $state['remaining_due']);
                    $cash = $credit - $dueAdjustment;
                    if ($cash > $state['remaining_paid']) {
                        throw ValidationException::withMessages(['invoice' => 'The refund cannot exceed the remaining payment. Reload the invoice.']);
                    }
                    $column = $this->sourceColumn($source);
                    $refund->setAttribute($column, $source->getKey());
                    $refund->amount = Money::decimal($cash);
                    $refund->due_adjustment = Money::decimal($dueAdjustment);
                    $this->assignNumbers($refund, 'refund', now()->toDateString(), $source->doctor_id);
                }

                if (RefundCause::query()->whereKey($values['cause_id'])->sharedLock()->first(['id']) === null) {
                    throw ValidationException::withMessages(['cause_id' => 'Select an available refund cause.']);
                }
                $refund->fill($values);
                $refund->save();
                if ($items !== []) {
                    $refund->items()->attach($items);
                }

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
            if (str_contains($constraint, 'refunds_invoice_no_unique')) {
                throw ValidationException::withMessages(['record' => 'The generated Refund invoice number already exists. Choose a distinct pattern in Hospital settings.']);
            }
            throw $exception;
        } catch (QueryException $exception) {
            if ((int) ($exception->errorInfo[1] ?? 0) === 1452) {
                throw ValidationException::withMessages(['record' => 'The selected invoice or refund cause has changed. Reload the form.']);
            }
            throw $exception;
        }
    }

    public function refundPreview(Account $actor, string $invoice, int $branchId, ?Refund $current = null): array
    {
        $this->authorize($actor, $current ?? new Refund, $current?->exists ? 'update' : 'create');
        $source = $current?->exists ? $this->sourceInvoice($current) : $this->findInvoice($invoice, $branchId);
        $state = $this->invoiceBalance($source, $current, includeRefundItems: true);
        $items = [];
        if ($current?->exists) {
            $items = $current->items()->get(['investigations.id', 'investigations.name', 'investigations.room_no'])->map(fn (Investigation $item): array => [
                'id' => $item->getKey(), 'name' => $item->name, 'room' => $item->room_no,
                'eye_type' => $item->pivot->eye_type, 'eye_label' => eyeTypes()[$item->pivot->eye_type] ?? ucfirst($item->pivot->eye_type),
                'rate' => $item->pivot->rate, 'amount' => $item->pivot->amount, 'position' => $item->pivot->position,
            ])->all();
        } elseif ($source instanceof PatientInvestigation && ! $state['legacy']) {
            $items = array_values(array_diff_key($this->refundItemValues($source), array_flip($state['used_item_ids'])));
        }

        return [
            'invoice' => $this->sourceInvoiceOption($source, $state),
            'service' => $this->sourceService($source),
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

    public function delete(Account $actor, Model $record): void
    {
        if (! $record instanceof Consultation && ! $record instanceof PatientInvestigation && ! $record instanceof PatientOperation && ! $record instanceof Refund && ! $record instanceof DueCollection) {
            throw new AuthorizationException;
        }
        $this->authorize($actor, $record, 'delete');
        $this->access->transaction($actor, function (Account $freshActor) use ($record): void {
            $entry = $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail();
            $this->authorize($freshActor, $entry, 'delete');
            if ($entry instanceof Refund) {
                $this->sourceInvoice($entry, lock: true);
            }
            if ($entry instanceof DueCollection) {
                $source = $this->sourceInvoice($entry, lock: true);
                if ($this->invoiceBalance($source)['remaining_paid'] < Money::minor($entry->amount)) {
                    throw ValidationException::withMessages(['record' => 'This collection has funded a refund and cannot be deleted.']);
                }
            }
            $entry->delete();
        });
    }

    public function invoiceData(Account $actor, BaseModel $record): array
    {
        if (! $record instanceof Consultation && ! $record instanceof PatientInvestigation && ! $record instanceof PatientOperation && ! $record instanceof Refund && ! $record instanceof DueCollection) {
            throw new AuthorizationException;
        }
        $this->authorize($actor, $record, 'view');
        $document = $record->newQuery()->whereKey($record->getKey())->firstOrFail();
        $this->authorize($actor, $document, 'view');
        $isRefund = $document instanceof Refund;
        $isCollection = $document instanceof DueCollection;
        $source = match (true) {
            $isRefund => $this->sourceInvoice($document),
            $isCollection => $this->sourceInvoice($document),
            default => $document,
        };
        $patient = $source->patient()->first(['id', 'patient_id', 'name', 'age', 'gender', 'mobile_no']);
        $doctor = $source->doctor()->where('branch_id', $source->branch_id)->first(['id', 'name']);
        $branch = Branch::query()->find($source->branch_id, ['id', 'name', 'address', 'mobile_no', 'telephone_no', 'email', 'logo_path']);
        $company = Setting::values('company', $source->getConnectionName());
        $branding = Setting::values('branding', $source->getConnectionName());
        $branchPhone = implode(' / ', array_filter([$branch?->mobile_no, $branch?->telephone_no], fn ($value): bool => filled($value)));
        $logoPath = filled($branch?->logo_path) ? $branch->logo_path : $branding['logo_path'];
        $timezone = config('app.timezone');
        $date = match (true) {
            $isCollection => $document->collection_date,
            $isRefund => $document->created_at,
            default => $source->entry_date,
        };
        $createdAt = $document->created_at?->copy()->timezone($timezone);
        $invoiceNumber = filled($source->invoice_no) ? $source->invoice_no : '#'.$source->getKey();
        $title = match (true) {
            $isRefund => 'Refund voucher',
            $isCollection => 'Due collection receipt',
            $source instanceof Consultation => 'Consultation invoice',
            $source instanceof PatientInvestigation => 'Investigation invoice',
            $source instanceof PatientOperation => 'Operation invoice',
        };
        $routePrefix = match (true) {
            $isRefund => 'hospital.refunds',
            $isCollection => 'hospital.due-collections',
            $source instanceof Consultation => 'hospital.consultations',
            $source instanceof PatientInvestigation => 'hospital.patient-investigations',
            $source instanceof PatientOperation => 'hospital.patient-operations',
        };
        $state = $this->invoiceBalance($source);
        $refundCredit = $isRefund ? Money::minor($document->amount) + Money::minor($document->due_adjustment) : null;

        return [
            'pageTitle' => $title,
            'documentTitle' => $title,
            'invoiceNumber' => $isRefund || $isCollection ? ($document->invoice_no ?: '—') : $invoiceNumber,
            'originalInvoiceNumber' => $isRefund || $isCollection ? $invoiceNumber : null,
            'sourceService' => $this->sourceService($source),
            'sourceServiceLabel' => ucfirst($this->sourceService($source)),
            'entryDate' => $date?->format('d M Y') ?? '—',
            'entryDateLabel' => $isCollection ? 'Collection date' : 'Date / Time',
            'entryTime' => $isCollection ? '' : ($createdAt?->format('h:i A') ?? '—'),
            'printedAt' => now($timezone)->format('d M Y h:i A'),
            'printedBy' => $actor->name,
            'company' => [
                'name' => filled($branch?->name) ? $branch->name : ($company['name'] ?: $branding['app_name']),
                'address' => filled($branch?->address) ? $branch->address : $company['address'],
                'phone' => $branchPhone !== '' ? $branchPhone : $company['phone'],
                'email' => filled($branch?->email) ? $branch->email : $company['email'],
                'tax_id' => $company['tax_id'],
            ],
            'logoUrl' => filled($logoPath) ? Storage::disk('public')->url($logoPath) : null,
            'patient' => $patient,
            'patientGenderLabel' => genders()[$patient?->gender] ?? ($patient?->gender ?? '—'),
            'doctor' => $doctor,
            'deliveryDate' => $source instanceof PatientInvestigation ? $source->delivery_date?->format('d M Y') : null,
            'operationDate' => $source instanceof PatientOperation ? $source->operation_date?->format('d M Y') : null,
            'operationPatientTypeLabel' => $source instanceof PatientOperation ? (patientTypes()[$source->patient_type] ?? $source->patient_type) : null,
            'consultantName' => $document instanceof PatientOperation && $source->consultant_id !== null
                ? $source->consultant()->where('branch_id', $source->branch_id)->value('name') : null,
            'lines' => match (true) {
                $isRefund => $this->refundLines($document, $source),
                $isCollection => [],
                default => $this->invoiceLines($source),
            },
            'amount' => $source->amount,
            'discount' => $source->discount_amount,
            'extraDiscount' => $source instanceof PatientInvestigation ? $source->extra_discount_amount : '0.00',
            'payable' => $source->payable_amount,
            'paid' => $source->paid_amount,
            'due' => $source->due_amount,
            'refundAmount' => $isRefund ? $document->amount : null,
            'refundDueAdjustment' => $isRefund ? $document->due_adjustment : null,
            'refundCreditAmount' => $isRefund ? Money::decimal($refundCredit) : null,
            'collectionAmount' => $isCollection ? $document->amount : null,
            'collectionPaymentType' => $isCollection ? $document->paymentType()->value('name') : null,
            'collectedAmount' => Money::decimal($state['collected']),
            'creditAmount' => Money::decimal($state['credit']),
            'cashRefunded' => Money::decimal($state['cash_refunded']),
            'dueAdjusted' => Money::decimal($state['due_adjusted']),
            'remainingPayable' => Money::decimal($state['remaining_amount']),
            'remainingPaid' => Money::decimal($state['remaining_paid']),
            'remainingDue' => Money::decimal($state['remaining_due']),
            'remarks' => $isRefund || $isCollection ? $document->remarks : $source->getAttribute('remarks'),
            'refundCause' => $isRefund ? $document->cause()->value('name') : null,
            'amountWords' => $this->amountWords(match (true) {
                $isRefund => Money::decimal($refundCredit),
                $isCollection => $document->amount,
                default => Money::decimal($state['remaining_amount']),
            }),
            'status' => $isCollection ? 'RECEIVED' : ($isRefund || ($state['credit'] > 0 && $state['remaining_amount'] === 0)
                ? 'REFUNDED' : ($state['credit'] > 0 ? 'PARTIALLY REFUNDED' : ($state['remaining_due'] > 0 ? 'DUE' : 'PAID'))),
            'backUrl' => route($routePrefix.'.index'),
        ];
    }

    private function write(Account $actor, BaseModel $record, array $values, int $branchId, Closure $save): BaseModel
    {
        if ($branchId < 1 || ($record->exists && $record->branch_id !== $branchId)) {
            throw new AuthorizationException('Select the branch that owns this entry.');
        }
        $action = $record->exists ? 'update' : 'create';
        $this->authorize($actor, $record, $action);

        try {
            return $this->access->transaction($actor, function (Account $freshActor) use ($record, $values, $branchId, $action, $save): BaseModel {
                $entry = $record->exists ? $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail() : $record->newInstance();
                if (! $entry->exists) {
                    $entry->branch_id = $branchId;
                }
                $this->authorize($freshActor, $entry, $action);
                if ($entry->exists && ! $entry instanceof Appointment && $entry->reference_commission_paid_at !== null) {
                    throw ValidationException::withMessages(['record' => 'Mark this Reference commission Unpaid before changing the invoice.']);
                }
                if ($entry->exists && ! $entry instanceof Appointment
                    && ($this->sourceRefunds($entry)->exists() || $this->sourceCollections($entry)->exists())) {
                    throw ValidationException::withMessages(['record' => 'This invoice has a refund or due collection and cannot be changed.']);
                }

                $doctor = Doctor::query()->where('branch_id', $branchId)->whereKey($values['doctor_id'])->sharedLock()->first();
                if ($doctor === null) {
                    throw ValidationException::withMessages(['doctor_id' => 'Select a doctor from this branch.']);
                }
                if (Patient::query()->whereKey($values['patient_id'])->sharedLock()->first(['id']) === null) {
                    throw ValidationException::withMessages(['patient_id' => 'Select a patient from this branch.']);
                }
                if (! $entry instanceof Appointment && PaymentType::query()->whereKey($values['payment_type_id'])->sharedLock()->first(['id']) === null) {
                    throw ValidationException::withMessages(['payment_type_id' => 'Select an available payment type.']);
                }

                return $save($entry, $doctor);
            });
        } catch (UniqueConstraintViolationException $exception) {
            $constraint = $exception->errorInfo[2] ?? '';
            if (str_contains($constraint, 'consultations_appointment_id_unique')) {
                throw ValidationException::withMessages(['appointment_id' => 'A consultation already exists for this appointment.']);
            }
            if (str_contains($constraint, '_invoice_no_unique') || str_contains($constraint, '_serial_scope_no_unique') || str_contains($constraint, '_queue_serial_unique')) {
                throw ValidationException::withMessages(['record' => 'The generated number already exists. Choose a distinct pattern in Hospital settings.']);
            }

            throw $exception;
        } catch (QueryException $exception) {
            if ((int) ($exception->errorInfo[1] ?? 0) === 1451) {
                throw ValidationException::withMessages(['record' => 'This appointment is linked to a consultation. Its doctor and patient must remain the same.']);
            }
            if ((int) ($exception->errorInfo[1] ?? 0) === 1452) {
                throw ValidationException::withMessages(['record' => 'A selected patient, doctor, reference, payment type, or appointment has changed. Reload the form.']);
            }
            throw $exception;
        }
    }

    private function assignReference(Consultation|PatientInvestigation|PatientOperation $entry, array $values, int $branchId, string $type): void
    {
        $settings = Setting::values('hospital', $entry->getConnectionName());
        if (! $entry->exists) {
            // Policy snapshots also mark new invoices that have no Reference yet.
            $entry->reference_discount_mode = $settings['reference_'.$type.'_discount_mode'];
            $entry->reference_due_mode = $settings['reference_'.$type.'_due_mode'];
        }

        if (! $settings[$type.'_reference_enabled']) {
            if (filled($values['reference_id'] ?? null)) {
                throw ValidationException::withMessages(['reference_id' => 'Reference selection is disabled in Hospital settings.']);
            }

            // Hiding the selector must preserve an invoice's existing reference.
            return;
        }

        $referenceId = $values['reference_id'] ?? null;
        $reference = null;
        if ($referenceId !== null) {
            $reference = Reference::query()->where('branch_id', $branchId)->whereKey($referenceId)->sharedLock()->first(['id', $type.'_percent']);
            if ($reference === null) {
                throw ValidationException::withMessages(['reference_id' => 'Select a reference from this branch.']);
            }
        }

        $previousReferenceId = $entry->reference_id;
        $entry->reference_id = $reference?->getKey();
        if ($entry->reference_discount_mode === null && $entry->reference_due_mode === null) {
            // An edited pre-feature invoice must never acquire today's rates or rules.
            $entry->reference_commission_percent = null;
        } elseif ($reference === null) {
            $entry->reference_commission_percent = null;
        } elseif (! $entry->exists || $previousReferenceId !== $reference->getKey()) {
            $entry->reference_commission_percent = $reference->getAttribute($type.'_percent');
        }
    }

    private function sourceInvoice(Refund|DueCollection $record, bool $lock = false): BaseModel
    {
        $query = match ($record->serviceType()) {
            'consultation' => Consultation::query()->whereKey($record->consultation_id),
            'investigation' => PatientInvestigation::query()->whereKey($record->investigation_id),
            'operation' => PatientOperation::query()->whereKey($record->operation_id),
        };
        $query->where('branch_id', $record->branch_id);
        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->firstOrFail();
    }

    private function findInvoice(string $invoice, int $branchId, bool $lock = false): BaseModel
    {
        if (! preg_match('/\A(consultation|investigation|operation):([1-9]\d{0,17})\z/', $invoice, $matches)) {
            throw ValidationException::withMessages(['invoice' => 'Select an available clinical invoice.']);
        }
        [, $service, $id] = $matches;
        $query = match ($service) {
            'consultation' => Consultation::query(),
            'investigation' => PatientInvestigation::query(),
            'operation' => PatientOperation::query(),
        };
        $query->where('branch_id', $branchId)->whereKey($id);
        if ($lock) {
            $query->lockForUpdate();
        }
        $source = $query->first();
        if ($source === null) {
            throw ValidationException::withMessages(['invoice' => 'Select an invoice from this branch.']);
        }

        return $source;
    }

    public function referenceCommissionAmounts(Consultation|PatientInvestigation|PatientOperation $source, array $balance): array
    {
        $result = [
            'status' => 'legacy',
            'percent' => $source->reference_commission_percent,
            'discount_mode' => $source->reference_discount_mode,
            'due_mode' => $source->reference_due_mode,
            'commission' => '0.00', 'eligible' => '0.00', 'pending' => '0.00',
        ];
        if ($source->reference_discount_mode === null && $source->reference_due_mode === null) {
            return $result;
        }
        if ($source->reference_id === null) {
            return array_replace($result, ['status' => 'no_reference']);
        }
        if ($source->reference_commission_percent === null
            || ! array_key_exists($source->reference_discount_mode, config('settings.reference_discount_modes'))
            || ! array_key_exists($source->reference_due_mode, config('settings.reference_due_modes'))) {
            throw new LogicException('The invoice has incomplete Reference commission snapshots.');
        }

        $result['status'] = 'calculated';
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

    public function listInvoiceBalances(Account $actor, Consultation|PatientInvestigation|PatientOperation $source, array $invoiceIds, int $branchId): array
    {
        $this->authorize($actor, $source, 'view');
        $this->assertSelectedBranch($branchId);
        if ($invoiceIds === []) {
            return [];
        }

        $query = $this->invoiceSourceQuery(
            $branchId, $source->getTable(), $this->sourceService($source), $this->sourceColumn($source), 1, $invoiceIds,
        );
        $records = $source->getConnection()->query()->fromSub($query, 'invoices')
            ->get(['source_id', 'remaining_amount', 'remaining_paid', 'remaining_due', 'credit', 'refunded_gross']);
        $balances = [];
        foreach ($records as $invoice) {
            $balances[(int) $invoice->source_id] = [
                'remaining_amount' => Money::decimal(Money::minor((string) $invoice->remaining_amount)),
                'remaining_paid' => Money::decimal(Money::minor((string) $invoice->remaining_paid)),
                'remaining_due' => Money::decimal(Money::minor((string) $invoice->remaining_due)),
                'credit' => Money::decimal(Money::minor((string) $invoice->credit)),
                'refunded_gross' => Money::decimal(Money::minor((string) $invoice->refunded_gross)),
            ];
        }

        return $balances;
    }

    public function dueInvoices(Account $actor, int $branchId, array $filters): Paginator
    {
        $this->authorize($actor, new DueCollection, 'create');
        $this->assertSelectedBranch($branchId);

        return $this->invoiceOptions($this->invoiceBalancesQuery($branchId)->where('remaining_due', '>', 0), $filters, 'all');
    }

    public function refundInvoices(Account $actor, int $branchId, array $filters): Paginator
    {
        $this->authorize($actor, new Refund, 'create');
        $this->assertSelectedBranch($branchId);
        $query = $this->invoiceBalancesQuery($branchId)->where('remaining_amount', '>', 0)
            ->where(function (QueryBuilder $sources): void {
                $sources->where(function (QueryBuilder $single): void {
                    $single->whereIn('service', ['consultation', 'operation'])->where('refund_count', 0);
                })->orWhere(function (QueryBuilder $investigations): void {
                    $investigations->where('service', 'investigation')
                        ->whereNotExists(function (QueryBuilder $legacy): void {
                            $legacy->selectRaw('1')->from('refunds as legacy')->whereColumn('legacy.investigation_id', 'invoices.source_id')
                                ->whereNotExists(function (QueryBuilder $items): void {
                                    $items->selectRaw('1')->from('refund_items')->whereColumn('refund_items.refund_id', 'legacy.id');
                                });
                        })
                        ->whereExists(function (QueryBuilder $items): void {
                            $items->selectRaw('1')->from('patient_investigation_items as invoice_item')->whereColumn('invoice_item.patient_investigation_id', 'invoices.source_id')
                                ->whereNotExists(function (QueryBuilder $returned): void {
                                    $returned->selectRaw('1')->from('refund_items')->whereColumn('refund_items.invoice_id', 'invoices.source_id')
                                        ->whereColumn('refund_items.item_id', 'invoice_item.investigation_id');
                                });
                        });
                });
            });

        return $this->invoiceOptions($query, $filters, 'investigation');
    }

    private function assertSelectedBranch(int $branchId): void
    {
        if ($branchId < 1 || $branchId !== (int) request()->attributes->get('branch_id')) {
            throw new AuthorizationException('Select the branch that owns these invoices.');
        }
    }

    private function invoiceBalancesQuery(int $branchId, bool $commissionFields = false): QueryBuilder
    {
        $consultations = $this->invoiceSourceQuery($branchId, 'consultations', 'consultation', 'consultation_id', 1, commissionFields: $commissionFields);
        $investigations = $this->invoiceSourceQuery($branchId, 'patient_investigations', 'investigation', 'investigation_id', 2, commissionFields: $commissionFields);
        $operations = $this->invoiceSourceQuery($branchId, 'patient_operations', 'operation', 'operation_id', 3, commissionFields: $commissionFields);

        return (new Refund)->getConnection()->query()->fromSub($consultations->unionAll($investigations)->unionAll($operations), 'invoices');
    }

    private function invoiceSourceQuery(int $branchId, string $table, string $service, string $column, int $rank, ?array $invoiceIds = null, bool $commissionFields = false): QueryBuilder
    {
        // Identifiers come only from the three clinical model types and their explicit source mappings.
        $connection = (new Refund)->getConnection();
        $refunds = $connection->table('refunds')->where('branch_id', $branchId)->whereNotNull($column)
            ->select($column)->selectRaw('SUM(amount) AS cash, SUM(due_adjustment) AS due_adjustment, COUNT(*) AS refund_count')->groupBy($column);
        $collections = $connection->table('due_collections')->where('branch_id', $branchId)->whereNotNull($column)
            ->select($column)->selectRaw('SUM(amount) AS collected')->groupBy($column);
        if ($invoiceIds !== null) {
            $refunds->whereIn($column, $invoiceIds);
            $collections->whereIn($column, $invoiceIds);
        }

        $query = $connection->table($table.' as source')
            ->leftJoinSub($refunds, 'returned', fn (JoinClause $join) => $join->on('returned.'.$column, '=', 'source.id'))
            ->leftJoinSub($collections, 'payments', fn (JoinClause $join) => $join->on('payments.'.$column, '=', 'source.id'))
            ->where('source.branch_id', $branchId)
            ->selectRaw('? AS service, ? AS service_rank, source.id AS source_id, source.invoice_no, source.paid_amount, source.due_amount, source.payable_amount,
                source.paid_amount + COALESCE(payments.collected, 0) - COALESCE(returned.cash, 0) AS remaining_paid,
                source.due_amount - COALESCE(payments.collected, 0) - COALESCE(returned.due_adjustment, 0) AS remaining_due,
                source.payable_amount - COALESCE(returned.cash, 0) - COALESCE(returned.due_adjustment, 0) AS remaining_amount,
                COALESCE(returned.cash, 0) + COALESCE(returned.due_adjustment, 0) AS credit,
                COALESCE(returned.refund_count, 0) AS refund_count', [$service, $rank]);
        if ($commissionFields) {
            $query->addSelect([
                'source.entry_date', 'source.amount', 'source.reference_id', 'source.reference_commission_percent',
                'source.reference_discount_mode', 'source.reference_due_mode', 'source.reference_commission_paid_amount', 'source.reference_commission_paid_at',
            ]);
        } else {
            $query->join('patients as patient', function (JoinClause $join): void {
                $join->on('patient.id', '=', 'source.patient_id')->on('patient.branch_id', '=', 'source.branch_id');
            })->addSelect('patient.patient_id', 'patient.name as patient_name', 'patient.mobile_no');
        }
        if ($service === 'investigation') {
            $returnedItems = $connection->table('refund_items as refund_item')
                ->join('refunds as refund_invoice', 'refund_invoice.id', '=', 'refund_item.refund_id')
                ->where('refund_invoice.branch_id', $branchId)
                ->select('refund_item.invoice_id')->selectRaw('SUM(refund_item.rate) AS gross')->groupBy('refund_item.invoice_id');
            if ($invoiceIds !== null) {
                $returnedItems->whereIn('refund_item.invoice_id', $invoiceIds);
            }
            $query->leftJoinSub($returnedItems, 'returned_items', fn (JoinClause $join) => $join->on('returned_items.invoice_id', '=', 'source.id'))
                ->selectRaw('COALESCE(returned_items.gross, 0) AS refunded_gross');
        } else {
            $query->selectRaw('CASE WHEN COALESCE(returned.refund_count, 0) > 0 THEN source.amount ELSE 0 END AS refunded_gross');
        }
        if ($invoiceIds !== null) {
            $query->whereIn('source.id', $invoiceIds);
        }

        return $query;
    }

    private function invoiceOptions(QueryBuilder $query, array $filters, string $defaultService): Paginator
    {
        $service = $filters['service'] ?? $defaultService;
        if ($service !== 'all') {
            $query->where('service', $service);
        }
        $search = trim($filters['search'] ?? '');
        if ($search !== '') {
            $pattern = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $search).'%';
            $query->where(function (QueryBuilder $search) use ($pattern): void {
                $search->whereRaw("invoice_no LIKE ? ESCAPE '!'", [$pattern])
                    ->orWhereRaw("patient_id LIKE ? ESCAPE '!'", [$pattern])
                    ->orWhereRaw("patient_name LIKE ? ESCAPE '!'", [$pattern])
                    ->orWhereRaw("mobile_no LIKE ? ESCAPE '!'", [$pattern]);
            });
        }
        $records = $query->orderBy('service_rank')->orderBy('source_id')
            ->simplePaginate(20, ['*'], 'page', max(1, (int) ($filters['page'] ?? 1)));
        $records->setCollection($records->getCollection()->map(fn (object $invoice): array => $this->invoiceOption((array) $invoice)));

        return $records;
    }

    private function invoiceOption(array $invoice): array
    {
        $parts = array_filter([$invoice['invoice_no'], $invoice['patient_name'], $invoice['mobile_no']], fn ($value): bool => filled($value));
        $parts[] = ucfirst($invoice['service']);

        return [
            ...$invoice,
            'id' => $invoice['service'].':'.$invoice['source_id'],
            'text' => implode(' — ', $parts),
            'service_label' => ucfirst($invoice['service']),
        ];
    }

    private function sourceInvoiceOption(BaseModel $source, array $state): array
    {
        $patient = $source->patient()->first(['id', 'name', 'mobile_no']);

        return $this->invoiceOption([
            'service' => $this->sourceService($source), 'source_id' => $source->getKey(), 'invoice_no' => $source->invoice_no,
            'patient_name' => $patient?->name, 'mobile_no' => $patient?->mobile_no,
            'paid_amount' => $source->paid_amount, 'due_amount' => $source->due_amount, 'payable_amount' => $source->payable_amount,
            'remaining_paid' => Money::decimal($state['remaining_paid']), 'remaining_due' => Money::decimal($state['remaining_due']),
            'remaining_amount' => Money::decimal($state['remaining_amount']),
        ]);
    }

    private function sourceRefunds(BaseModel $source): Builder
    {
        return Refund::query()->where($this->sourceColumn($source), $source->getKey());
    }

    private function sourceColumn(BaseModel $source): string
    {
        return match (true) {
            $source instanceof Consultation => 'consultation_id',
            $source instanceof PatientInvestigation => 'investigation_id',
            $source instanceof PatientOperation => 'operation_id',
        };
    }

    private function sourceService(BaseModel $source): string
    {
        return match (true) {
            $source instanceof Consultation => 'consultation',
            $source instanceof PatientInvestigation => 'investigation',
            $source instanceof PatientOperation => 'operation',
        };
    }

    private function sourceCollections(BaseModel $source): Builder
    {
        return DueCollection::query()->where($this->sourceColumn($source), $source->getKey());
    }

    private function invoiceBalance(BaseModel $source, ?Refund $current = null, bool $includeRefundItems = false): array
    {
        $query = $this->sourceRefunds($source)->select(['id', 'amount', 'due_adjustment']);
        if ($current?->exists) {
            $query->whereKeyNot($current->getKey());
        }
        if ($includeRefundItems && $source instanceof PatientInvestigation) {
            $query->withCount('items');
        }
        $refunds = $query->get();
        $cash = 0;
        $due = 0;
        $legacy = false;
        foreach ($refunds as $refund) {
            $cash += Money::minor($refund->amount);
            $due += Money::minor($refund->due_adjustment);
            $legacy = $legacy || ($includeRefundItems && $source instanceof PatientInvestigation && (int) $refund->items_count === 0);
        }
        $collected = Money::minor((string) $this->sourceCollections($source)->sum('amount'));
        $remainingPaid = Money::minor($source->paid_amount) + $collected - $cash;
        $remainingDue = Money::minor($source->due_amount) - $collected - $due;
        $remainingAmount = Money::minor($source->payable_amount) - $cash - $due;
        if (min($remainingPaid, $remainingDue, $remainingAmount) < 0 || $remainingAmount !== $remainingPaid + $remainingDue) {
            throw ValidationException::withMessages(['invoice' => 'The invoice and refund balances are inconsistent. Review the saved payments before refunding.']);
        }

        return [
            'cash_refunded' => $cash,
            'due_adjusted' => $due,
            'credit' => $cash + $due,
            'collected' => $collected,
            'remaining_paid' => $remainingPaid,
            'remaining_due' => $remainingDue,
            'remaining_amount' => $remainingAmount,
            'legacy' => $legacy,
            'used_item_ids' => $includeRefundItems && $source instanceof PatientInvestigation && $refunds->isNotEmpty()
                ? $source->getConnection()->table('refund_items')->whereIn('refund_id', $refunds->modelKeys())->pluck('item_id')->map(fn ($id): int => (int) $id)->all()
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
                'id' => $id, 'name' => $item->name, 'room' => $item->room_no,
                'eye_type' => $item->pivot->eye_type, 'eye_label' => eyeTypes()[$item->pivot->eye_type] ?? ucfirst($item->pivot->eye_type),
                'rate' => Money::decimal($rate), 'amount' => Money::decimal($minor), 'position' => (int) $item->pivot->position,
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
        $lines = $this->invoiceLines($source);
        $lines[0]['amount'] = Money::decimal(Money::minor($refund->amount) + Money::minor($refund->due_adjustment));

        return $lines;
    }

    private function invoiceLines(BaseModel $source): array
    {
        if ($source instanceof PatientInvestigation) {
            return $source->investigations()->get(['investigations.id', 'investigations.name', 'investigations.room_no'])
                ->map(fn (Investigation $investigation): array => [
                    'name' => $investigation->name,
                    'room' => $investigation->room_no,
                    'eye' => eyeTypes()[$investigation->pivot->eye_type] ?? ucfirst($investigation->pivot->eye_type),
                    'amount' => $investigation->pivot->rate,
                ])->all();
        }

        if ($source instanceof PatientOperation) {
            return [[
                'name' => $source->operation()->value('name') ?? 'Operation',
                'room' => null,
                'eye' => eyeTypes()[$source->eye_type] ?? ucfirst($source->eye_type),
                'amount' => $source->amount,
            ]];
        }

        return [[
            'name' => 'Consultation — '.(patientTypes()[$source->patient_type] ?? $source->patient_type),
            'room' => null,
            'eye' => null,
            'amount' => $source->amount,
        ]];
    }

    private function amountWords(string $amount): string
    {
        $minor = Money::minor($amount);
        $words = ucfirst(Number::spell(intdiv($minor, 100), locale: 'en')).' taka';
        if ($minor % 100 > 0) {
            $words .= ' and '.Number::spell($minor % 100, locale: 'en').' paisa';
        }

        return $words.' only.';
    }

    private function assignNumbers(BaseModel $entry, string $resource, string $date, ?int $doctorId = null): void
    {
        $hasSerial = $entry instanceof Appointment || $entry instanceof Consultation;
        $dateField = $entry instanceof Appointment ? 'appointment_date' : 'entry_date';
        // Moving a numbered queue entry to another series needs a new serial; its invoice stays fixed.
        $rescheduled = $entry->exists && $hasSerial && $entry->serial_no !== null
            && $entry->isDirty(['doctor_id', $dateField]);
        if ($entry->exists && ! $rescheduled) {
            return;
        }

        $settings = Setting::values('hospital', $entry->getConnectionName());
        if ($hasSerial && ($resource === 'appointment' || $settings['consultation_serial_enabled'] || $rescheduled)) {
            $key = $resource.'_serial';
            $scope = $this->numberScope($entry, $key, $date, $settings);
            if (! $entry->exists || $entry->getOriginal('serial_scope') !== $scope) {
                $entry->serial_scope = $scope;
                $entry->serial_no = $this->nextNumber($entry, $scope, $date, $settings[$key.'_pattern']);
            }
        }

        if (! $entry->exists && ($resource !== 'consultation' || $settings['consultation_invoice_enabled'])) {
            $key = $resource.'_invoice';
            $scope = $this->numberScope($entry, $key, $date, $settings);
            $entry->invoice_no = $this->nextNumber($entry, $scope, $date, $settings[$key.'_pattern'], $doctorId);
        }
    }

    private function numberScope(BaseModel $entry, string $key, string $date, array $settings): string
    {
        $scope = match ($settings[$key.'_scope']) {
            'branch' => 'branch:'.$entry->branch_id,
            'doctor' => 'doctor:'.$entry->branch_id.':'.$entry->doctor_id,
            default => 'tenant',
        };
        $date = Carbon::parse($date);
        $period = match ($settings[$key.'_reset']) {
            'year' => $date->format('Y'),
            'month' => $date->format('Ym'),
            'day' => $date->format('Ymd'),
            default => 'continuous',
        };

        return $key.':'.$scope.':'.$period;
    }

    private function nextNumber(BaseModel $entry, string $scope, string $date, string $pattern, ?int $doctorId = null): string
    {
        $sequences = $entry->getConnection()->table('clinical_sequences');
        $sequences->insertOrIgnore(['scope' => $scope, 'last_number' => 0]);
        $sequences->where('scope', $scope);
        $sequence = $sequences->lockForUpdate()->first();
        $number = (int) $sequence->last_number + 1;
        if ($number > 999999999999) {
            throw ValidationException::withMessages(['record' => 'This numbering sequence is exhausted. Change its scope or reset period in Hospital settings.']);
        }
        $sequences->update(['last_number' => $number]);

        $date = Carbon::parse($date);
        $formatted = strtr($pattern, [
            '{YYYY}' => $date->format('Y'), '{MM}' => $date->format('m'), '{DD}' => $date->format('d'),
            '{BRANCH}' => (string) $entry->branch_id, '{DOCTOR}' => (string) ($doctorId ?? $entry->getAttribute('doctor_id')),
        ]);
        $formatted = preg_replace_callback('/\{SEQ(?::([1-9]|1[0-2]))?\}/', fn (array $match): string => str_pad((string) $number, (int) ($match[1] ?? 6), '0', STR_PAD_LEFT), $formatted);
        if (mb_strlen($formatted) > 120) {
            throw ValidationException::withMessages(['record' => 'The generated number is too long. Shorten its pattern in Hospital settings.']);
        }

        return $formatted;
    }

    private function authorize(Account $actor, BaseModel $entry, string $action): void
    {
        $this->access->assertActorContext($actor);
        Gate::forUser($actor)->authorize($action, $entry->exists ? $entry : $entry::class);
        $tenant = Tenant::current();
        $subscription = $tenant === null ? null : Subscription::forTenant($tenant);
        if (! $subscription?->isUsable() || ! $subscription->plan->allowsRoutePermission('hospital.view')
            || ! $subscription->plan->allowsRoutePermission($entry->crudPermission().'.'.$action)
            || (! $this->access->isOwner($actor) && ! $actor->can('hospital.view'))) {
            throw new AuthorizationException('Your account or plan does not include this action.');
        }
    }

    private function totals(array $values, int $amount, bool $percentDiscount = false, bool $extraDiscount = false): array
    {
        // Bound integer arithmetic to the same ten whole digits accepted by money inputs.
        if ($amount > 999999999999) {
            throw ValidationException::withMessages(['amount' => 'The combined amount is too large.']);
        }
        $mode = $percentDiscount ? $values['discount_mode'] : 'amount';
        $basisPoints = $percentDiscount ? Money::minor((string) $values['discount_percent']) : 0;
        $discount = $mode === 'percent'
            ? intdiv($amount * $basisPoints + 5000, 10000)
            : Money::minor($values['discount_amount']);
        if ($discount > $amount) {
            throw ValidationException::withMessages(['discount_amount' => 'Discount cannot exceed the amount.']);
        }
        if ($mode === 'amount') {
            $basisPoints = $amount === 0 ? 0 : intdiv($discount * 10000 + intdiv($amount, 2), $amount);
        }

        $remaining = $amount - $discount;
        $extraBasisPoints = $extraDiscount ? Money::minor((string) $values['extra_discount_percent']) : 0;
        $extraAmount = intdiv($remaining * $extraBasisPoints + 5000, 10000);
        $payable = $remaining - $extraAmount;
        $paid = Money::minor($values['paid_amount']);
        if ($paid > $payable) {
            throw ValidationException::withMessages(['paid_amount' => 'Payment cannot exceed the payable amount.']);
        }

        $totals = [
            'amount' => Money::decimal($amount),
            'discount_amount' => Money::decimal($discount),
            'paid_amount' => Money::decimal($paid),
            'payable_amount' => Money::decimal($payable),
            'due_amount' => Money::decimal($payable - $paid),
        ];
        if ($percentDiscount) {
            $totals['discount_mode'] = $mode;
            $totals['discount_percent'] = Money::decimal($basisPoints);
        }
        if ($extraDiscount) {
            $totals['extra_discount_percent'] = Money::decimal($extraBasisPoints);
            $totals['extra_discount_amount'] = Money::decimal($extraAmount);
        }

        return $totals;
    }
}
