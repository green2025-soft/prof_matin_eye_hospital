<?php

namespace Modules\TenantCore\Services;

use Modules\TenantCore\Models\Income;

class IncomeService extends IncomeExpenseService
{
    protected string $model = Income::class;

    protected string $feature = 'tenantcore.income';
}
