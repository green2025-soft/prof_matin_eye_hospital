<?php

namespace Modules\Accounting\Http\Requests;

use Carbon\CarbonImmutable;
use Modules\Core\Http\Requests\BaseRequest;

class MonthReportRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'month' => $this->input('month') ?? now()->format('Y-m'),
            'depth' => $this->input('depth') ?? 3,
        ]);
    }

    public function rules(): array
    {
        return [
            'month' => ['required', 'date_format:Y-m'],
            'depth' => ['required', 'integer', 'between:2,5'],
        ];
    }

    public function filters(): array
    {
        return $this->validated();
    }

    // Statements use the same month comparisons and Bangladesh financial year.
    public function periods(): array
    {
        $month = CarbonImmutable::createFromFormat('!Y-m', $this->validated('month'));
        $previous = $month->subMonth();
        $year = $month->month >= 7 ? $month->year : $month->year - 1;
        $financialYear = CarbonImmutable::create($year, 7, 1)->startOfDay();

        return [
            'current' => [
                'label' => $month->format('M Y'),
                'from_date' => $month->toDateString(),
                'to_date' => $month->endOfMonth()->toDateString(),
            ],
            'previous' => [
                'label' => $previous->format('M Y'),
                'from_date' => $previous->toDateString(),
                'to_date' => $previous->endOfMonth()->toDateString(),
            ],
            'fiscal' => [
                'label' => 'FY '.$year.'-'.substr((string) ($year + 1), -2),
                'from_date' => $financialYear->toDateString(),
                'to_date' => $month->endOfMonth()->toDateString(),
            ],
            'previous_fiscal' => [
                'label' => 'FY '.($year - 1).'-'.substr((string) $year, -2),
                'from_date' => $financialYear->subYear()->toDateString(),
                'to_date' => $financialYear->subDay()->toDateString(),
            ],
        ];
    }
}
