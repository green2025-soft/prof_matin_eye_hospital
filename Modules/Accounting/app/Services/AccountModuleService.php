<?php

namespace Modules\Accounting\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Accounting\Models\AccountHead;
use Modules\Accounting\Models\AccountModule;
use Modules\Accounting\Models\VoucherType;
use Modules\Core\Models\Account;

class AccountModuleService
{
    public function save(Account $actor, ?Model $record, array $values): AccountModule
    {
        return $actor->withAccessLock(function (Account $freshActor) use ($record, $values): AccountModule {
            $mapping = AccountModule::whereKey($record->getKey())->lockForUpdate()->firstOrFail();
            Gate::forUser($freshActor)->authorize('update', $mapping);
            $voucherType = VoucherType::whereKey($values['voucher_type_id'])->where('active', true)->firstOrFail();
            $components = $mapping->components()->lockForUpdate()->get();
            $submitted = collect($values['components'])->keyBy('id');

            // Every fixed component must remain present; clients may only change its ledger.
            if ($submitted->keys()->diff($components->modelKeys())->isNotEmpty() || $submitted->count() !== $components->count()) {
                throw ValidationException::withMessages(['components' => 'Reload the mapping and keep all of its components.']);
            }

            $ledgerIds = $submitted->pluck('account_head_id')->unique();
            $ledgers = AccountHead::postable()->whereKey($ledgerIds)->lockForUpdate()->get(['id']);
            if ($ledgers->count() !== $ledgerIds->count()) {
                throw ValidationException::withMessages(['components' => 'Select active ledger accounts.']);
            }

            $mapping->forceFill([
                'name' => $values['name'],
                'active' => $values['active'],
                'voucher_type_id' => $voucherType->id,
            ])->save();
            foreach ($components as $component) {
                $component->update(['account_head_id' => $submitted->get($component->id)['account_head_id']]);
            }

            return $mapping;
        });
    }
}
