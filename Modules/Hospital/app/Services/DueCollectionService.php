<?php

namespace Modules\Hospital\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Hospital\Models\DueCollection;

class DueCollectionService extends ClinicalEntryService
{
    public function save(Account $actor, ?DueCollection $record, array $values, int $branchId): DueCollection
    {
        return $this->write($actor, $record ?? new DueCollection, $branchId, function (DueCollection $collection) use ($values, $branchId): DueCollection {
            $source = $collection->exists
                ? $this->invoices->source($collection, lock: true)
                : $this->invoices->find($collection, $values['invoice'], $branchId, lock: true);
            $state = $this->invoices->balance($source);
            $previous = $collection->exists ? Money::minor($collection->amount) : 0;
            $amount = Money::minor($values['amount']);
            if ($amount > $state['remaining_due'] + $previous) {
                throw ValidationException::withMessages(['amount' => 'The collection cannot exceed the outstanding due.']);
            }
            if ($state['remaining_paid'] + $amount - $previous < 0) {
                throw ValidationException::withMessages(['amount' => 'This payment has funded a refund. Its amount cannot be reduced this far.']);
            }
            if (! $collection->exists) {
                $settings = Setting::values('hospital', $collection->getConnectionName());
                $column = $source->{$source::REFUND_RELATION}()->getForeignKeyName();
                $collection->setAttribute($column, $source->getKey());
                $this->assignInvoiceNumber($collection, 'due_collection_invoice', $values['collection_date'], $settings, $source->doctor_id);
            }
            $collection->fill($values);
            $collection->amount = Money::decimal($amount);
            $collection->save();

            if (! $collection->wasRecentlyCreated || ! $settings['sms_due_collection_enabled']) {
                return $collection;
            }

            $patient = $source->patient()->firstOrFail();
            $this->sms->queue(
                $collection,
                'hospital.due_collection',
                'due-collection:'.$collection->getKey().':created',
                $patient->mobile_no,
                true,
                $settings['sms_due_collection_template'],
                [
                    ...$patient->smsValues(),
                    'name' => $patient->name,
                    'invoice' => $collection->invoice_no,
                    'date' => $collection->collection_date->format('d-m-Y'),
                    'total' => Money::decimal($state['remaining_amount']),
                    'paid' => Money::decimal($state['remaining_paid'] + $amount),
                    'due' => Money::decimal($state['remaining_due'] - $amount),
                    'amount' => $collection->amount,
                ],
            );

            return $collection;
        });
    }

    public function preview(Account $actor, string $invoice, int $branchId, ?DueCollection $current = null): array
    {
        $collection = $current ?? new DueCollection;
        $this->authorize($actor, $collection, $collection->exists ? 'update' : 'create');
        $source = $collection->exists
            ? $this->invoices->source($collection)
            : $this->invoices->find($collection, $invoice, $branchId);
        $state = $this->invoices->balance($source);

        return [
            'invoice' => $this->invoices->option($source, $state),
            'service' => $source::SERVICE,
            'invoice_no' => $source->invoice_no ?: '',
            'remaining_due' => Money::decimal($state['remaining_due']),
            'remaining_paid' => Money::decimal($state['remaining_paid']),
            'remaining_amount' => Money::decimal($state['remaining_amount']),
            'available_due' => Money::decimal($state['remaining_due'] + ($current?->exists ? Money::minor($current->amount) : 0)),
        ];
    }

    public function invoices(Account $actor, int $branchId, array $filters): Paginator
    {
        $this->authorize($actor, new DueCollection, 'create');
        $queries = $this->invoices->sources($branchId);
        foreach ($queries as $query) {
            $query->where('due_amount', '>', 0);
        }

        return $this->invoices->options($queries, $filters, 'all',
            fn (array $balance): bool => $balance['remaining_due'] > 0,
        );
    }

    public function invoiceData(Account $actor, DueCollection $collection): array
    {
        $this->authorize($actor, $collection, 'view');
        $source = $this->invoices->source($collection);
        $state = $this->invoices->balance($source);
        $data = $this->invoiceValues($actor, $source, $state);

        return $data + [
            'pageTitle' => 'Due collection receipt',
            'documentTitle' => 'Due collection receipt',
            'invoiceNumber' => $collection->invoice_no ?: '—',
            'originalInvoiceNumber' => filled($source->invoice_no) ? $source->invoice_no : '#'.$source->getKey(),
            'entryDate' => $collection->collection_date?->format('d M Y') ?? '—',
            'entryDateLabel' => 'Collection date',
            'entryTime' => '',
            'collectionAmount' => $collection->amount,
            'collectionPaymentType' => $collection->paymentType()->value('name'),
            'remarks' => $collection->remarks,
            'refundCause' => null,
            'amountWords' => Money::words(Money::minor($collection->amount)),
            'status' => 'RECEIVED',
            'backUrl' => route('hospital.due-collections.index'),
        ];
    }

    public function delete(Account $actor, Model $record): void
    {
        $this->authorize($actor, $record, 'delete');

        $actor->withAccessLock(function (Account $freshActor) use ($record): void {
            $collection = $record->newQuery()
                ->whereKey($record->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $this->authorize($freshActor, $collection, 'delete');
            $source = $this->invoices->source($collection, lock: true);
            if ($this->invoices->balance($source)['remaining_paid'] < Money::minor($collection->amount)) {
                throw ValidationException::withMessages(['record' => 'This collection has funded a refund and cannot be deleted.']);
            }
            $collection->delete();
        });
    }
}
