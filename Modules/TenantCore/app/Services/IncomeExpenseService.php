<?php

namespace Modules\TenantCore\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Modules\Accounting\Contracts\JournalPosting;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;

abstract class IncomeExpenseService
{
    protected string $model;

    protected string $headModel;

    protected string $feature;

    // Save the voucher, its head rows and optional journal in the same tenant transaction.
    public function save(Account $actor, ?Model $record, array $values): Model
    {
        return $actor->withAccessLock(function (Account $freshActor) use ($record, $values): Model {
            $entry = $record?->exists
                ? $record->newQuery()
                    ->whereKey($record->getKey())
                    ->lockForUpdate()
                    ->firstOrFail()
                : new $this->model;
            Gate::forUser($freshActor)->authorize($entry->exists ? 'update' : 'create', $entry);

            $items = $values['items'];
            unset($values['items']);

            $amount = 0;
            foreach ($items as $item) {
                $amount += Money::minor($item['amount']);
            }

            $entry->fill($values);
            $entry->amount = Money::decimal($amount);
            $entry->save();

            if (! $entry->wasRecentlyCreated) {
                $entry->items()->delete();
            }
            $entry->items()->createMany($items);

            $journals = $this->journals();
            $subscription = request()->attributes->get('application_subscription');
            $accountingAvailable = $subscription?->isUsable()
                && $subscription->plan->allowsRoutePermission('accounting.view');

            // Existing links stay current after plan changes; older unlinked entries never backfill.
            if ($journals !== null && (! $entry->wasRecentlyCreated || $accountingAvailable)) {
                $headModel = $this->headModel;
                $headNames = $headModel::whereKey(array_column($items, 'head_id'))
                    ->sharedLock()
                    ->pluck('name')
                    ->implode(', ');

                $journals->sync(
                    source: $entry,
                    feature: $this->feature,
                    entryDate: $entry->entry_date->toDateString(),
                    branchId: $entry->branch_id,
                    amounts: [
                        'settlement' => $amount,
                        'value' => $amount,
                    ],
                    created: $entry->wasRecentlyCreated,
                    paymentTypeId: $entry->payment_type_id,
                    actor: $freshActor,
                    reference: Str::substr($headNames, 0, 100),
                );
            }

            return $entry;
        });
    }

    public function delete(Account $actor, Model $record): void
    {
        $actor->withAccessLock(function (Account $freshActor) use ($record): void {
            $entry = $record->newQuery()
                ->whereKey($record->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            Gate::forUser($freshActor)->authorize('delete', $entry);

            $this->journals()?->reverse($entry, $this->feature);
            $entry->delete();
        });
    }

    // TenantCore remains usable when Accounting is not enabled in the application.
    private function journals(): ?JournalPosting
    {
        return app()->bound(JournalPosting::class) ? app(JournalPosting::class) : null;
    }
}
