<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Modules\Core\Models\Invoice;

class InvoiceActionRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function authorize(): bool
    {
        $invoice = $this->record();
        $ability = $this->routeIs('core.invoices.issue') ? 'issue' : ($this->routeIs('core.invoices.void') ? 'void' : null);

        return $invoice instanceof Invoice && $ability !== null && Gate::allows($ability, $invoice);
    }

    public function rules(): array
    {
        return ['reason' => [$this->routeIs('core.invoices.void') ? 'required' : 'prohibited', 'string', 'max:1000']];
    }
}
