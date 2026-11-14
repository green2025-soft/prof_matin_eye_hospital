<?php

namespace Modules\TenantCore\Services;

use Modules\TenantCore\Models\Income;
use Modules\TenantCore\Models\IncomeHead;

class IncomeService extends IncomeExpenseService
{
    protected string $model = Income::class;

    protected string $headModel = IncomeHead::class;

    protected string $feature = 'tenantcore.income';
}
