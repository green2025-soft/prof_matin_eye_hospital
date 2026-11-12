<?php

namespace Modules\Core\Policies;

use Modules\Core\Models\Account;
use Modules\Core\Models\Admin;
use Modules\Core\Models\SmsAccount;
use Modules\Core\Models\SmsMessage;
use Modules\Core\Models\SmsRecharge;
use Modules\Core\Models\Tenant;

class SmsPolicy
{
    public function viewAccounts(Account $actor): bool
    {
        return $this->platform($actor, 'sms-accounts.view');
    }

    public function manageAccounts(Account $actor, ?SmsAccount $account = null): bool
    {
        return $this->platform($actor, $account?->exists ? 'sms-accounts.update' : 'sms-accounts.create');
    }

    public function viewHistory(Account $actor): bool
    {
        return $this->platform($actor, 'sms-history.view');
    }

    public function resolveMessage(Account $actor, SmsMessage $message): bool
    {
        return $this->platform($actor, 'sms-history.update');
    }

    public function viewRecharges(Account $actor): bool
    {
        return $this->platform($actor, 'sms-recharges.view');
    }

    public function reviewRecharge(Account $actor, SmsRecharge $recharge): bool
    {
        return $this->platform($actor, 'sms-recharges.update');
    }

    public function viewWallet(Account $actor): bool
    {
        return $this->tenant($actor, 'sms.view');
    }

    public function requestRecharge(Account $actor): bool
    {
        return $this->tenant($actor, 'sms-recharges.create');
    }

    public function viewProof(Account $actor, SmsRecharge $recharge): bool
    {
        if ($actor instanceof Admin) {
            return $this->viewRecharges($actor) || $this->reviewRecharge($actor, $recharge);
        }

        return Tenant::current()?->getKey() === $recharge->tenant_id
            && ($this->viewWallet($actor)
                || ($this->requestRecharge($actor) && $recharge->requested_by === (string) $actor->getAuthIdentifier()));
    }

    private function platform(Account $actor, string $permission): bool
    {
        return $actor instanceof Admin && $actor->matchesApplication()
            && (request()->attributes->get('navigation_permissions', [])[$permission] ?? false);
    }

    private function tenant(Account $actor, string $permission): bool
    {
        return ! $actor instanceof Admin && $actor->matchesApplication()
            && (request()->attributes->get('navigation_permissions', [])[$permission] ?? false);
    }
}
