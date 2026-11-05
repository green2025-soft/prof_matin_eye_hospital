<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;

class PharmacyAccess
{
    public function __construct(private AccessManager $access) {}

    public function allows(Account $actor, string $permission): bool
    {
        if (! $this->access->actorMatchesContext($actor) || (int) request()->attributes->get('branch_id') < 1) {
            return false;
        }

        $tenant = Tenant::current();
        $subscription = $tenant === null ? null : Subscription::forTenant($tenant);

        return $subscription?->isUsable()
            && $subscription->plan->allowsRoutePermission('pharmacy.view')
            && $subscription->plan->allowsRoutePermission($permission)
            && ($this->access->isOwner($actor) || ($actor->can('pharmacy.view') && $actor->can($permission)));
    }

    public function authorize(Account $actor, string $permission): void
    {
        if (! $this->allows($actor, $permission)) {
            throw new AuthorizationException('Your account or plan does not include this Pharmacy action.');
        }
    }
}
