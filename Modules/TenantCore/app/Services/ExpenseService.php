<?php

namespace Modules\TenantCore\Services;

use Modules\TenantCore\Models\Expense;
use Modules\TenantCore\Models\ExpenseHead;

class ExpenseService extends IncomeExpenseService
{
    protected string $model = Expense::class;

    protected string $headModel = ExpenseHead::class;

    protected string $feature = 'tenantcore.expense';
}
