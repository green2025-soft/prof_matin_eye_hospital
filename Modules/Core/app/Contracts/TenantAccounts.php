<?php

namespace Modules\Core\Contracts;

interface TenantAccounts
{
    public function ownerExists(string $email): bool;

    public function adoptOwner(string $email): void;

    public function createOwner(string $name, string $email, string $password): void;
}
