<?php

namespace Modules\Hospital\Contracts;

use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Models\Account;

interface PharmacyCustomers
{
    public function paginate(Account $actor, array $filters): LengthAwarePaginator;

    public function search(Account $actor, string $search, bool $customersOnly, int $page = 1, ?Builder $eligibleIds = null): array;

    public function findMany(Account $actor, array $ids): array;

    public function create(Account $actor, array $values, int $branchId): array;
}
