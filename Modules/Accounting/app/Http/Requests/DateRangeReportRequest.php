<?php

namespace Modules\Accounting\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;

class DateRangeReportRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'from_date' => $this->input('from_date') ?? now()->startOfMonth()->toDateString(),
            'to_date' => $this->input('to_date') ?? now()->toDateString(),
        ]);
    }

    public function rules(): array
    {
        return [
            'from_date' => ['required', 'date_format:Y-m-d'],
            'to_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:from_date'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', Rule::in(config('pagination.options'))],
        ];
    }

    public function filters(): array
    {
        return $this->validated();
    }
}
