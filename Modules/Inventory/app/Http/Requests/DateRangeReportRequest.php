<?php

namespace Modules\Inventory\Http\Requests;

class DateRangeReportRequest extends ReportRequest
{
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            'from_date' => $this->input('from_date') ?? now()->startOfMonth()->toDateString(),
            'to_date' => $this->input('to_date') ?? now()->toDateString(),
        ]);
    }

    public function rules(): array
    {
        return parent::rules() + [
            'from_date' => ['required', 'date_format:Y-m-d'],
            'to_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:from_date'],
        ];
    }
}
