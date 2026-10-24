<?php

namespace Modules\Core\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface TenantAccounts
{
    public function ownerForLogin(): Authenticatable;

    public function ownerExists(string $email): bool;

    public function adoptOwner(string $email): void;

    public function createOwner(string $name, string $email, string $password): void;
}
