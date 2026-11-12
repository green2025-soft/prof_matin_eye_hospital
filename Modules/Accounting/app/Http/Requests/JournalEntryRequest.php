<?php

namespace Modules\Accounting\Http\Requests;

use Illuminate\Validation\Validator;
use Modules\Core\Helpers\Money;
use Modules\Core\Http\Requests\BaseRequest;

class JournalEntryRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    protected array $rules = [
        'voucher_type_id' => ['required', 'integer', 'min:1'],
        'entry_date' => ['required', 'date_format:Y-m-d'],
        'reference' => ['nullable', 'string', 'max:100'],
        'narration' => ['nullable', 'string', 'max:2000'],
        'lines' => ['required', 'array', 'min:2', 'max:100'],
        'lines.*' => ['required', 'array'],
        'lines.*.account_head_id' => ['required', 'integer', 'min:1'],
        'lines.*.debit' => ['nullable', 'decimal:0,2', 'between:0,9999999999.99'],
        'lines.*.credit' => ['nullable', 'decimal:0,2', 'between:0,9999999999.99'],
        'lines.*.remarks' => ['nullable', 'string', 'max:255'],
    ];

    // Each line uses one positive side; JournalService validates the combined totals.
    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            foreach ($this->input('lines') as $index => $line) {
                $lineDebit = Money::minor((string) ($line['debit'] ?? '0'));
                $lineCredit = Money::minor((string) ($line['credit'] ?? '0'));

                if (($lineDebit > 0) === ($lineCredit > 0)) {
                    $validator->errors()->add('lines.'.$index.'.debit', 'Enter a positive Debit or Credit amount on this line.');
                }
            }
        }];
    }
}
