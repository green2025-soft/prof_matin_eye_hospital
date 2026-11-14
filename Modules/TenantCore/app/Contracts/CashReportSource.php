<?php

namespace Modules\TenantCore\Contracts;

use Closure;

interface CashReportSource
{
    public function modules(): array;

    public function heads(): array;

    // Emit minor-unit amounts with the dated head, reference and party/head description.
    public function collect(string $through, Closure $record, ?string $from = null): void;
}
