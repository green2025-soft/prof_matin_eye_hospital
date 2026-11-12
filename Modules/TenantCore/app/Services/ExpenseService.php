<?php

namespace Modules\TenantCore\Services;

use Modules\TenantCore\Models\Expense;

class ExpenseService extends IncomeExpenseService
{
    protected string $model = Expense::class;

    protected string $feature = 'tenantcore.expense';
}
