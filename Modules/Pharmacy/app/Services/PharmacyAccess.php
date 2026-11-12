<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Modules\Core\Models\Account;

class PharmacyAccess
{
    public function allows(Account $actor, string $permission): bool
    {
        $permissions = request()->attributes->get('navigation_permissions', []);

        return $actor->matchesApplication()
            && (int) request()->attributes->get('branch_id') > 0
            && ($permissions['pharmacy.view'] ?? false)
            && ($permissions[$permission] ?? false);
    }

    public function authorize(Account $actor, string $permission): void
    {
        if (! $this->allows($actor, $permission)) {
            throw new AuthorizationException('Your account or plan does not include this Pharmacy action.');
        }
    }
}
