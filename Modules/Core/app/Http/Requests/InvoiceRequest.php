<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Invoice;

class InvoiceRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function authorize(): bool
    {
        return parent::authorize() && ($this->input('status') !== Invoice::STATUS_ISSUED
            || Gate::allows('issue', $this->record() ?? new Invoice));
    }

    public function rules(): array
    {
        return [
            'tenant_id' => [$this->onCreate('required'), 'integer', 'min:1', $this->existsIn('tenants')],
            'due_at' => ['nullable', 'date_format:Y-m-d'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.description' => ['required', 'string', 'max:1000'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100000'],
            'items.*.unit_amount' => ['required', 'string', 'regex:/^\d{1,10}(?:\.\d{1,2})?$/D'],
            'status' => ['sometimes', 'required', Rule::in([Invoice::STATUS_DRAFT, Invoice::STATUS_ISSUED])],
        ];
    }

    public function payload(): array
    {
        $values = parent::payload();
        $items = [];
        $total = 0;

        foreach ($values['items'] as $index => $item) {
            $unit = Money::minor($item['unit_amount']);
            $quantity = (int) $item['quantity'];

            if ($unit > intdiv(Invoice::MAX_MINOR, $quantity)) {
                throw ValidationException::withMessages(['items.'.$index.'.unit_amount' => 'This line amount exceeds the supported limit.']);
            }

            $lineTotal = $unit * $quantity;

            if ($total > Invoice::MAX_MINOR - $lineTotal) {
                throw ValidationException::withMessages(['items' => 'The invoice total exceeds the supported limit.']);
            }

            $total += $lineTotal;
            $items[] = [
                'description' => trim($item['description']),
                'quantity' => $quantity,
                'unit_amount_minor' => $unit,
                'line_total_minor' => $lineTotal,
                'position' => count($items),
            ];
        }

        if ($total < 1) {
            throw ValidationException::withMessages(['items' => 'The invoice total must be greater than zero.']);
        }

        $values['items'] = $items;
        $values['total_minor'] = $total;
        $values['status'] ??= Invoice::STATUS_DRAFT;

        return $values;
    }
}
