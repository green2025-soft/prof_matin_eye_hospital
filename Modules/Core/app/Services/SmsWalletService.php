<?php

namespace Modules\Core\Services;

use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\SmsAccount;
use Modules\Core\Models\SmsRecharge;
use Modules\Core\Models\Tenant;

class SmsWalletService
{
    public function save(Account $actor, ?SmsAccount $record, array $values): SmsAccount
    {
        return $this->centralTransaction($actor, function (Account $freshActor) use ($record, $values): SmsAccount {
            $tenantId = $record?->tenant_id ?? $values['tenant_id'];
            $tenant = Tenant::whereKey($tenantId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($tenant->status === Tenant::STATUS_DELETING) {
                throw ValidationException::withMessages(['tenant_id' => 'This tenant is being deleted.']);
            }

            $account = SmsAccount::where('tenant_id', $tenant->getKey())
                ->lockForUpdate()
                ->first();

            if ($record === null && $account !== null) {
                throw ValidationException::withMessages(['tenant_id' => 'This tenant already has an SMS account.']);
            }

            $account ??= (new SmsAccount)->forceFill(['tenant_id' => $tenant->getKey()]);
            Gate::forUser($freshActor)->authorize('manageAccounts', $account);
            $account->forceFill([
                'enabled' => (bool) $values['enabled'],
                'rate' => Money::minor($values['rate']),
                'monthly_limit' => $values['monthly_limit'] ?? null,
                'allowed_features' => array_values($values['allowed_features'] ?? []),
                'gateway' => $values['gateway_enabled'] ? $this->gateway($account, $values['gateway']) : null,
            ])->save();

            return $account;
        });
    }

    public function requestRecharge(Account $actor, array $values): SmsRecharge
    {
        $actor->assertApplicationContext();
        Gate::forUser($actor)->authorize('requestRecharge', SmsRecharge::class);
        $tenantId = Tenant::current()->getKey();
        $proof = $values['proof'] ?? null;
        $connection = (new SmsRecharge)->getConnection();

        return $connection->transaction(function () use ($actor, $values, $tenantId, $proof, $connection): SmsRecharge {
            $freshActor = $actor->newQuery()
                ->whereKey($actor->getKey())
                ->firstOrFail();
            $freshActor->assertApplicationContext();
            Gate::forUser($freshActor)->authorize('requestRecharge', SmsRecharge::class);
            $account = SmsAccount::where('tenant_id', $tenantId)
                ->lockForUpdate()
                ->first();

            if ($account === null || ! $account->enabled || empty($account->allowed_features)
                || $account->tenant->status === Tenant::STATUS_DELETING) {
                throw ValidationException::withMessages(['record' => 'SMS is not enabled for this workspace.']);
            }

            $duplicatePayment = SmsRecharge::where('tenant_id', $tenantId)
                ->where('payment_method', $values['payment_method'])
                ->where('payment_reference', $values['payment_reference'])
                ->exists();

            if ($duplicatePayment) {
                throw ValidationException::withMessages(['payment_reference' => 'This payment reference already has a recharge request.']);
            }

            $path = $proof instanceof UploadedFile ? $proof->store('sms-recharges', 'local') : null;
            if ($path === false) {
                throw ValidationException::withMessages(['proof' => 'The payment proof could not be stored.']);
            }
            if ($path !== null) {
                $connection->afterRollBack(fn () => Storage::disk('local')->delete($path));
            }

            $recharge = (new SmsRecharge)->forceFill([
                'tenant_id' => $tenantId,
                'amount' => Money::minor($values['amount']),
                'payment_method' => $values['payment_method'],
                'payment_reference' => $values['payment_reference'],
                'proof_path' => $path,
                'requested_by' => (string) $freshActor->getAuthIdentifier(),
                'status' => 'pending',
            ]);
            $recharge->save();

            return $recharge;
        });
    }

    public function approve(Account $actor, SmsRecharge $recharge, ?string $note = null): SmsRecharge
    {
        return $this->centralTransaction($actor, function (Account $freshActor) use ($recharge, $note): SmsRecharge {
            $account = SmsAccount::where('tenant_id', $recharge->tenant_id)
                ->lockForUpdate()
                ->firstOrFail();
            $current = SmsRecharge::whereKey($recharge->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            Gate::forUser($freshActor)->authorize('reviewRecharge', $current);

            if ($current->status === 'approved') {
                return $current;
            }
            if ($current->status !== 'pending') {
                throw ValidationException::withMessages(['record' => 'Only a pending recharge can be approved.']);
            }
            if ($current->amount > PHP_INT_MAX - $account->balance) {
                throw ValidationException::withMessages(['record' => 'The wallet balance is too large.']);
            }

            $account->forceFill(['balance' => $account->balance + $current->amount])->save();
            $current->forceFill([
                'status' => 'approved',
                'reviewed_by' => $freshActor->getKey(),
                'review_note' => $note,
                'reviewed_at' => now(),
            ])->save();

            $tenant = $current->tenant;
            $current->getConnection()->afterCommit(fn () => app(SmsService::class)->afterResponse($tenant));

            return $current;
        });
    }

    public function reject(Account $actor, SmsRecharge $recharge, ?string $note = null): SmsRecharge
    {
        return $this->centralTransaction($actor, function (Account $freshActor) use ($recharge, $note): SmsRecharge {
            SmsAccount::where('tenant_id', $recharge->tenant_id)
                ->lockForUpdate()
                ->firstOrFail();
            $current = SmsRecharge::whereKey($recharge->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            Gate::forUser($freshActor)->authorize('reviewRecharge', $current);

            if ($current->status === 'rejected') {
                return $current;
            }
            if ($current->status !== 'pending') {
                throw ValidationException::withMessages(['record' => 'Only a pending recharge can be rejected.']);
            }

            $current->forceFill([
                'status' => 'rejected',
                'reviewed_by' => $freshActor->getKey(),
                'review_note' => $note,
                'reviewed_at' => now(),
            ])->save();

            return $current;
        });
    }

    private function gateway(SmsAccount $account, array $values): array
    {
        $gateway = [
            'api_url' => $values['api_url'],
            'method' => $values['method'],
            'body_format' => $values['body_format'],
            'sender_id' => $values['sender_id'] ?? '',
            'success_field' => $values['success_field'] ?? '',
            'success_value' => $values['success_value'] ?? '',
            'failure_values' => $values['failure_values'] ?? '',
        ];
        $saved = $account->gateway ?? [];

        foreach (array_keys(config('settings.credentials.sms')) as $field) {
            $gateway[$field] = filled($values[$field] ?? null) ? $values[$field] : ($saved[$field] ?? '');
        }
        $gateway['parameters'] = $gateway['parameters'] ?: config('settings.credentials.sms.parameters.default');
        $gateway['enabled'] = true;

        return $gateway;
    }

    private function centralTransaction(Account $actor, Closure $operation): mixed
    {
        return $actor->withAccessLock(function (Account $freshActor) use ($operation): mixed {
            $central = (new SmsAccount)->getConnection();

            return $central->transaction(fn (): mixed => $operation($freshActor));
        });
    }
}
