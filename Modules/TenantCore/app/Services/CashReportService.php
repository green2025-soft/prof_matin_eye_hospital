<?php

namespace Modules\TenantCore\Services;

use Brick\Math\BigInteger;
use Carbon\CarbonImmutable;
use Modules\Core\Helpers\Money;

class CashReportService
{
    private const AMOUNTS = ['received', 'paid', 'receivable', 'payable'];

    // Owners emit dated financial facts; aggregation is identical for every source.
    public function report(string $from, string $to, array $modules = []): array
    {
        $opening = $this->emptyAmounts();
        $dates = [];
        $categories = [];
        $record = function (array $event) use ($from, $modules, &$opening, &$dates, &$categories): void {
            if ($modules !== [] && ! in_array($event['module'], $modules, true)) {
                return;
            }
            if ($event['date'] < $from) {
                $opening = $this->add($opening, $event);

                return;
            }

            $date = $event['date'];
            $dates[$date] = $this->add($dates[$date] ?? $this->emptyAmounts(), $event);
            $key = $event['module'].'|'.$event['label'];
            $categories[$key] = $this->add($categories[$key] ?? $this->emptyAmounts(), $event) + [
                'module' => $event['module'],
                'label' => $event['label'],
            ];
        };

        foreach (app()->tagged('tenantcore.cash-report-sources') as $source) {
            if ($modules !== [] && array_intersect($modules, $source->modules()) === []) {
                continue;
            }
            $source->collect($to, $record);
        }

        $cash = $opening['received']->minus($opening['paid']);
        $openingCash = $cash;
        $receivable = $opening['receivable'];
        $payable = $opening['payable'];
        $period = $this->emptyAmounts();
        $days = [];
        for ($date = CarbonImmutable::parse($from); $date->toDateString() <= $to; $date = $date->addDay()) {
            $amounts = $dates[$date->toDateString()] ?? $this->emptyAmounts();
            $before = $cash;
            $cash = $cash->plus($amounts['received'])->minus($amounts['paid']);
            $receivable = $receivable->plus($amounts['receivable']);
            $payable = $payable->plus($amounts['payable']);
            $period = $this->add($period, $amounts);
            $days[] = [
                'date' => $date->toDateString(),
                'opening' => Money::decimal((string) $before),
                'received' => Money::decimal((string) $amounts['received']),
                'paid' => Money::decimal((string) $amounts['paid']),
                'closing' => Money::decimal((string) $cash),
                'receivable' => Money::decimal((string) $receivable),
                'payable' => Money::decimal((string) $payable),
                'balance' => Money::decimal((string) $cash->plus($receivable)->minus($payable)),
            ];
        }

        $rows = [];
        foreach ($categories as $category) {
            $rows[] = $this->display($category) + [
                'module' => $category['module'],
                'label' => $category['label'],
            ];
        }

        return [
            'fromDate' => $from,
            'toDate' => $to,
            'opening' => Money::decimal((string) $openingCash),
            'received' => Money::decimal((string) $period['received']),
            'paid' => Money::decimal((string) $period['paid']),
            'closing' => Money::decimal((string) $cash),
            'receivable' => Money::decimal((string) $receivable),
            'payable' => Money::decimal((string) $payable),
            'balance' => Money::decimal((string) $cash->plus($receivable)->minus($payable)),
            'rows' => $rows,
            'days' => $days,
        ];
    }

    private function emptyAmounts(): array
    {
        return array_fill_keys(self::AMOUNTS, BigInteger::zero());
    }

    private function add(array $totals, array $amounts): array
    {
        foreach (self::AMOUNTS as $key) {
            $totals[$key] = $totals[$key]->plus($amounts[$key]);
        }

        return $totals;
    }

    private function display(array $amounts): array
    {
        $result = [];
        foreach (self::AMOUNTS as $key) {
            $result[$key] = Money::decimal((string) $amounts[$key]);
        }

        return $result;
    }
}
