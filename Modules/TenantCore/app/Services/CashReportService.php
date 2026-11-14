<?php

namespace Modules\TenantCore\Services;

use Brick\Math\BigInteger;
use Modules\Core\Helpers\Money;

class CashReportService
{
    private const AMOUNTS = ['received', 'paid', 'receivable', 'payable'];

    // Owners emit dated financial facts; aggregation is identical for every source.
    public function report(string $from, string $to, array $modules = [], bool $includeEntries = false, bool $includeOpening = true): array
    {
        $opening = $this->emptyAmounts();
        $dates = [];
        $headTotals = [];
        $headCatalog = [];
        $entries = [];
        $record = function (array $event) use ($from, $modules, $includeEntries, &$opening, &$dates, &$headTotals, &$entries): void {
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
            $headTotals[$key] = $this->add($headTotals[$key] ?? $this->emptyAmounts(), $event);

            if ($includeEntries) {
                $entries[] = $event;
            }
        };

        foreach (app()->tagged('tenantcore.cash-report-sources') as $source) {
            $sourceModules = $source->modules();
            $selectedModules = $modules === [] ? $sourceModules : array_intersect($sourceModules, $modules);

            if ($selectedModules === []) {
                continue;
            }

            $headCatalog += array_fill_keys($selectedModules, $source->heads());
            $source->collect($to, $record, $includeOpening ? null : $from);
        }

        $openingCash = $opening['received']->minus($opening['paid']);
        $cash = $openingCash;
        $receivable = $opening['receivable'];
        $payable = $opening['payable'];
        $openingSnapshot = [
            'closing' => $cash,
            'receivable' => $receivable,
            'payable' => $payable,
            'balance' => $cash->plus($receivable)->minus($payable),
        ];
        $period = $this->emptyAmounts();
        $days = [];

        // Calculate financial snapshots only for recorded dates; Blade fills calendar gaps.
        ksort($dates);
        foreach ($dates as $date => $amounts) {
            $before = $cash;
            $cash = $cash->plus($amounts['received'])->minus($amounts['paid']);
            $receivable = $receivable->plus($amounts['receivable']);
            $payable = $payable->plus($amounts['payable']);
            $period = $this->add($period, $amounts);
            $days[$date] = [
                'opening' => $before,
                'received' => $amounts['received'],
                'paid' => $amounts['paid'],
                'net' => $amounts['received']->minus($amounts['paid']),
                'closing' => $cash,
                'receivable' => $receivable,
                'payable' => $payable,
                'balance' => $cash->plus($receivable)->minus($payable),
            ];
        }

        return [
            'fromDate' => $from,
            'toDate' => $to,
            'opening' => Money::decimal((string) $openingCash),
            'received' => Money::decimal((string) $period['received']),
            'paid' => Money::decimal((string) $period['paid']),
            'net' => Money::decimal((string) $period['received']->minus($period['paid'])),
            'closing' => Money::decimal((string) $cash),
            'receivable' => Money::decimal((string) $receivable),
            'payable' => Money::decimal((string) $payable),
            'balance' => Money::decimal((string) $cash->plus($receivable)->minus($payable)),
            'headCatalog' => $headCatalog,
            'headTotals' => $headTotals,
            'openingSnapshot' => $openingSnapshot,
            'days' => $days,
            'entries' => $entries,
        ];
    }

    private function emptyAmounts(): array
    {
        return array_fill_keys(self::AMOUNTS, BigInteger::zero());
    }

    private function add(array $totals, array $amounts): array
    {
        return [
            'received' => $totals['received']->plus($amounts['received']),
            'paid' => $totals['paid']->plus($amounts['paid']),
            'receivable' => $totals['receivable']->plus($amounts['receivable']),
            'payable' => $totals['payable']->plus($amounts['payable']),
        ];
    }
}
