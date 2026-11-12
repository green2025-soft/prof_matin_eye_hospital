<?php

namespace Modules\TenantCore\Contracts;

use Closure;

interface CashReportSource
{
    public function modules(): array;

    public function collect(string $through, Closure $record): void;
}
