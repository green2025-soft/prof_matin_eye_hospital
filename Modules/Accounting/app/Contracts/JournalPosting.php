<?php

namespace Modules\Accounting\Contracts;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\Account;

interface JournalPosting
{
    public function sync(
        Model $source,
        string $feature,
        string $entryDate,
        int $branchId,
        array $amounts,
        bool $created,
        ?int $paymentTypeId = null,
        ?Account $actor = null,
        ?string $reference = null,
    ): void;

    public function reverse(Model $source, ?string $feature = null): void;
}
