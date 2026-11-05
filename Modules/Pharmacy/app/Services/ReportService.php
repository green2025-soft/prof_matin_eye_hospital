<?php

namespace Modules\Pharmacy\Services;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ReportService
{
    public function filterStockProducts(Builder $query, string $productType, ?int $variantId, ?string $search): void
    {
        $query->whereHas('variant.product', fn (Builder $products) => $products->where('type', $productType))
            ->when($variantId, fn (Builder $stock, int $id) => $stock->where('variant_id', $id))
            ->when(filled($search), function (Builder $stock) use ($search): void {
                $term = '%'.addcslashes($search, '\\%_').'%';
                $stock->whereHas('variant', fn (Builder $variants) => $variants->whereLike('sku', $term)
                    ->orWhereHas('product', fn (Builder $products) => $products->whereLike('name', $term)));
            });
    }

    public function dueSummary(Builder $invoices, string $partyColumn): object
    {
        return (object) [
            'party_count' => (clone $invoices)->distinct()->count($partyColumn),
            'invoice_count' => (clone $invoices)->count(),
            'due' => (clone $invoices)->sum('outstanding'),
        ];
    }

    public function dueBalances(Builder $invoices, string $partyColumn, LengthAwarePaginator $parties): void
    {
        $totals = [];
        $invoices = (clone $invoices)->whereIn($partyColumn, $parties->pluck('party_id'))
            ->select(['id', $partyColumn, 'total', 'returned', 'paid', 'refunded', 'settlement_discount', 'outstanding']);

        // Stream only invoices for this page; integer strings retain exact totals above PHP's integer limit.
        foreach ($invoices->lazyById(200) as $invoice) {
            $partyId = $invoice->getAttribute($partyColumn);
            $totals[$partyId] ??= (object) [
                'party_id' => $partyId, 'invoice_count' => 0, 'invoice_total' => '0',
                'returned' => '0', 'paid' => '0', 'refunded' => '0', 'settlement_discount' => '0', 'due' => '0',
            ];
            $balance = $totals[$partyId];
            $balance->invoice_count++;
            $balance->invoice_total = (string) BigInteger::of($balance->invoice_total)->plus($invoice->total);
            $balance->returned = (string) BigInteger::of($balance->returned)->plus($invoice->returned);
            $balance->paid = (string) BigInteger::of($balance->paid)->plus($invoice->paid);
            $balance->refunded = (string) BigInteger::of($balance->refunded)->plus($invoice->refunded);
            $balance->settlement_discount = (string) BigInteger::of($balance->settlement_discount)->plus($invoice->settlement_discount);
            $balance->due = (string) BigInteger::of($balance->due)->plus($invoice->outstanding);
        }

        $parties->setCollection(collect($totals)->sortKeys()->values());
    }

    public function financialActivities(Builder $invoices, array $dates, string $activity, int $cashSign): Collection
    {
        $totals = [];
        $invoices = (clone $invoices)->whereIn('entry_date', $dates)->select(['id', 'entry_date', 'total', 'initial_paid']);

        foreach ($invoices->lazyById(200) as $invoice) {
            $date = $invoice->entry_date->toDateString();
            $totals[$date] ??= (object) [
                'activity_date' => $date, 'activity' => $activity, 'entries' => 0, 'value' => '0', 'cash' => '0',
            ];
            $row = $totals[$date];
            $row->entries++;
            $row->value = (string) BigInteger::of($row->value)->plus($invoice->total);
            $cash = BigInteger::of($invoice->initial_paid)->multipliedBy($cashSign);
            $row->cash = (string) BigInteger::of($row->cash)->plus($cash);
        }

        return collect($totals);
    }

    public function stockActivities(Builder $entries, array $dates, string $activity): Collection
    {
        $totals = [];
        $entries = (clone $entries)->whereIn('entry_date', $dates)->select(['id', 'entry_date', 'cost']);

        foreach ($entries->lazyById(200) as $entry) {
            $date = $entry->entry_date->toDateString();
            $totals[$date] ??= (object) [
                'activity_date' => $date, 'activity' => $activity, 'entries' => 0, 'value' => '0', 'cash' => '0',
            ];
            $row = $totals[$date];
            $row->entries++;
            $row->value = (string) BigInteger::of($row->value)->plus($entry->cost);
        }

        return collect($totals);
    }

    public function paymentActivities(Builder $payments, array $dates, string $activity, int $cashSign): Collection
    {
        $totals = [];
        $payments = (clone $payments)->whereIn('payment_date', $dates)->select(['id', 'payment_date', 'amount', 'discount']);

        foreach ($payments->lazyById(200) as $payment) {
            $date = $payment->payment_date->toDateString();
            $totals[$date] ??= (object) [
                'activity_date' => $date, 'activity' => $activity, 'entries' => 0, 'value' => '0', 'cash' => '0',
            ];
            $row = $totals[$date];
            $row->entries++;
            $row->value = (string) BigInteger::of($row->value)->plus($payment->amount)->plus($payment->discount);
            $cash = BigInteger::of($payment->amount)->multipliedBy($cashSign);
            $row->cash = (string) BigInteger::of($row->cash)->plus($cash);
        }

        return collect($totals);
    }
}
