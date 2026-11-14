<?php

namespace Modules\Pharmacy\Services;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Pharmacy\Models\Payment;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\PurchaseReturn;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\SaleReturn;
use Modules\TenantCore\Contracts\CashReportSource;

class PharmacyCashReportSource implements CashReportSource
{
    public function modules(): array
    {
        return ['medicine', 'optic'];
    }

    public function heads(): array
    {
        return [
            'Sales' => 'received',
            'Customer collections' => 'received',
            'Purchase returns' => 'received',
            'Purchases' => 'paid',
            'Supplier payments' => 'paid',
            'Sale returns' => 'paid',
        ];
    }

    public function collect(string $through, Closure $record, ?string $from = null): void
    {
        $this->sales($through, $record, $from);
        $this->purchases($through, $record, $from);
        $this->customerPayments($through, $record, $from);
        $this->supplierPayments($through, $record, $from);
        $this->saleReturns($through, $record, $from);
        $this->purchaseReturns($through, $record, $from);
    }

    private function sales(string $through, Closure $record, ?string $from): void
    {
        Sale::query()
            ->select(['id', 'number', 'customer_id', 'product_type', 'entry_date', 'total', 'initial_paid'])
            ->whereNull('voided_at')
            ->whereNotNull('posted_at')
            ->where('entry_date', '<=', $through)
            ->when($from, fn (Builder $query) => $query->where('entry_date', '>=', $from))
            ->chunkById(200, function (Collection $sales) use ($record): void {
                foreach ($sales as $sale) {
                    $record([
                        'date' => $sale->entry_date->toDateString(),
                        'module' => $sale->product_type,
                        'label' => 'Sales',
                        'reference' => $sale->number,
                        'description' => 'Customer #'.($sale->customer_id ?? '—'),
                        'received' => $sale->initial_paid,
                        'paid' => 0,
                        'receivable' => $sale->total - $sale->initial_paid,
                        'payable' => 0,
                    ]);
                }
            });
    }

    private function purchases(string $through, Closure $record, ?string $from): void
    {
        Purchase::query()
            ->with('supplier:id,name')
            ->select(['id', 'number', 'supplier_id', 'product_type', 'entry_date', 'total', 'initial_paid'])
            ->whereNull('voided_at')
            ->whereNotNull('posted_at')
            ->where('entry_date', '<=', $through)
            ->when($from, fn (Builder $query) => $query->where('entry_date', '>=', $from))
            ->chunkById(200, function (Collection $purchases) use ($record): void {
                foreach ($purchases as $purchase) {
                    $record([
                        'date' => $purchase->entry_date->toDateString(),
                        'module' => $purchase->product_type,
                        'label' => 'Purchases',
                        'reference' => $purchase->number,
                        'description' => $purchase->supplier?->name ?? '—',
                        'received' => 0,
                        'paid' => $purchase->initial_paid,
                        'receivable' => 0,
                        'payable' => $purchase->total - $purchase->initial_paid,
                    ]);
                }
            });
    }

    private function customerPayments(string $through, Closure $record, ?string $from): void
    {
        Payment::query()
            ->select(['id', 'number', 'customer_id', 'product_type', 'payment_date', 'amount', 'discount'])
            ->where('direction', 'customer')
            ->whereNull('voided_at')
            ->where('payment_date', '<=', $through)
            ->when($from, fn (Builder $query) => $query->where('payment_date', '>=', $from))
            ->chunkById(200, function (Collection $payments) use ($record): void {
                foreach ($payments as $payment) {
                    $record([
                        'date' => $payment->payment_date->toDateString(),
                        'module' => $payment->product_type,
                        'label' => 'Customer collections',
                        'reference' => $payment->number,
                        'description' => 'Customer #'.($payment->customer_id ?? '—'),
                        'received' => $payment->amount,
                        'paid' => 0,
                        'receivable' => -($payment->amount + $payment->discount),
                        'payable' => 0,
                    ]);
                }
            });
    }

    private function supplierPayments(string $through, Closure $record, ?string $from): void
    {
        Payment::query()
            ->select(['id', 'number', 'supplier_id', 'product_type', 'payment_date', 'amount', 'discount'])
            ->where('direction', 'supplier')
            ->whereNull('voided_at')
            ->where('payment_date', '<=', $through)
            ->when($from, fn (Builder $query) => $query->where('payment_date', '>=', $from))
            ->chunkById(200, function (Collection $payments) use ($record): void {
                foreach ($payments as $payment) {
                    $record([
                        'date' => $payment->payment_date->toDateString(),
                        'module' => $payment->product_type,
                        'label' => 'Supplier payments',
                        'reference' => $payment->number,
                        'description' => 'Supplier #'.($payment->supplier_id ?? '—'),
                        'received' => 0,
                        'paid' => $payment->amount,
                        'receivable' => 0,
                        'payable' => -($payment->amount + $payment->discount),
                    ]);
                }
            });
    }

    private function saleReturns(string $through, Closure $record, ?string $from): void
    {
        SaleReturn::query()
            ->select(['id', 'number', 'customer_id', 'product_type', 'entry_date', 'total', 'initial_paid'])
            ->whereNull('voided_at')
            ->whereNotNull('posted_at')
            ->where('entry_date', '<=', $through)
            ->when($from, fn (Builder $query) => $query->where('entry_date', '>=', $from))
            ->chunkById(200, function (Collection $returns) use ($record): void {
                foreach ($returns as $return) {
                    $record([
                        'date' => $return->entry_date->toDateString(),
                        'module' => $return->product_type,
                        'label' => 'Sale returns',
                        'reference' => $return->number,
                        'description' => 'Customer #'.($return->customer_id ?? '—'),
                        'received' => 0,
                        'paid' => $return->initial_paid,
                        'receivable' => -($return->total - $return->initial_paid),
                        'payable' => 0,
                    ]);
                }
            });
    }

    private function purchaseReturns(string $through, Closure $record, ?string $from): void
    {
        PurchaseReturn::query()
            ->with('supplier:id,name')
            ->select(['id', 'number', 'supplier_id', 'product_type', 'entry_date', 'total', 'initial_paid'])
            ->whereNull('voided_at')
            ->whereNotNull('posted_at')
            ->where('entry_date', '<=', $through)
            ->when($from, fn (Builder $query) => $query->where('entry_date', '>=', $from))
            ->chunkById(200, function (Collection $returns) use ($record): void {
                foreach ($returns as $return) {
                    $record([
                        'date' => $return->entry_date->toDateString(),
                        'module' => $return->product_type,
                        'label' => 'Purchase returns',
                        'reference' => $return->number,
                        'description' => $return->supplier?->name ?? '—',
                        'received' => $return->initial_paid,
                        'paid' => 0,
                        'receivable' => 0,
                        'payable' => -($return->total - $return->initial_paid),
                    ]);
                }
            });
    }
}
