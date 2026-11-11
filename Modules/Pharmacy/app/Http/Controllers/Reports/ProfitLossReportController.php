<?php

namespace Modules\Pharmacy\Http\Controllers\Reports;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Modules\Core\Http\Controllers\BaseController;
use Modules\Pharmacy\Http\Requests\DateRangeReportRequest;
use Modules\Pharmacy\Models\Payment;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\ProductVariant;

class ProfitLossReportController extends BaseController
{
    public function index(DateRangeReportRequest $request): View
    {
        $filters = $request->filters();
        $query = $this->recordsQuery($filters);
        $summary = $this->summary($query);
        $showInvoiceAdjustments = empty($filters['variant_id']);
        $this->settlementDiscounts($summary, $filters, $showInvoiceAdjustments);
        $records = $this->paginateQuery(
            $query->orderBy('product_name')
                ->orderBy('id'),
            $filters
        );
        foreach ($records as $record) {
            $this->calculateProfit($record);
        }
        $selectedProduct = $this->selectedOption(
            ProductVariant::with('product:id,name,type')
                ->whereHas('product', fn (Builder $products) => $products->where('type', $filters['product_type'])),
            $filters['variant_id'] ?? null,
            ['id', 'product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl']
        );

        $data = [
            'pageTitle' => 'Product Profit / Loss',
            'filters' => $filters,
            'summary' => $summary,
            'records' => $records,
            'showInvoiceAdjustments' => $showInvoiceAdjustments,
            'selectedProduct' => $selectedProduct,
        ];

        return view('pharmacy::reports.profit-loss', $data);
    }

    private function recordsQuery(array $filters): Builder
    {
        $invoices = fn (Builder $invoices) => $invoices->where('product_type', $filters['product_type'])
            ->whereNotNull('posted_at')
            ->whereNull('voided_at')
            ->whereNull('deleted_at')
            ->whereBetween('entry_date', [$filters['from_date'], $filters['to_date']]);
        $saleItems = fn (Builder $items) => $items->whereNull('superseded_at')
            ->whereHas('sale', $invoices);
        $returnItems = fn (Builder $items) => $items->whereNull('superseded_at')
            ->whereHas('saleReturn', $invoices);
        $stockEffects = fn (Builder $movements) => $movements->whereIn('type', ['damage', 'adjustment', 'stock_variance'])
            ->whereBetween('movement_date', [$filters['from_date'], $filters['to_date']]);

        $variants = ProductVariant::select('phr_product_variants.*')
            ->whereHas('product', fn (Builder $products) => $products->where('type', $filters['product_type']))
            ->where(
                fn (Builder $activity) => $activity->whereHas('saleItems', $saleItems)
                    ->orWhereHas('saleReturnItems', $returnItems)
                    ->orWhereHas('movements', $stockEffects)
            )
            ->when($filters['variant_id'] ?? null, fn (Builder $variants, $id) => $variants->whereKey($id))
            ->selectSub(
                Product::whereColumn('id', 'phr_product_variants.product_id')
                    ->select('name'),
                'product_name'
            )
            ->withSum([
                'saleItems as sale_quantity' => $saleItems,
            ], 'base_quantity')
            ->withSum([
                'saleItems as sale_total' => $saleItems,
            ], 'total')
            ->withSum([
                'saleItems as sale_vat' => $saleItems,
            ], 'vat')
            ->withSum([
                'saleItems as sale_cost' => $saleItems,
            ], 'cost')
            ->withSum([
                'saleReturnItems as return_quantity' => $returnItems,
            ], 'base_quantity')
            ->withSum([
                'saleReturnItems as return_total' => $returnItems,
            ], 'total')
            ->withSum([
                'saleReturnItems as return_vat' => $returnItems,
            ], 'vat')
            ->withSum([
                'saleReturnItems as return_cost' => $returnItems,
            ], 'cost')
            ->withSum([
                'movements as damage' => fn (Builder $movements) => $stockEffects($movements)
                    ->where('type', 'damage'),
            ], 'cost')
            ->withSum([
                'movements as adjustment' => fn (Builder $movements) => $stockEffects($movements)
                    ->where('type', 'adjustment'),
            ], 'cost')
            ->withSum([
                'movements as stock_variance' => fn (Builder $movements) => $stockEffects($movements)
                    ->where('type', 'stock_variance'),
            ], 'cost');

        return ProductVariant::fromSub($variants->toBase(), 'phr_product_variants')
            ->with('product:id,name');
    }

    private function summary(Builder $records): object
    {
        $summary = (object) [
            'product_count' => (clone $records)->count(),
            'sale_quantity' => (clone $records)->sum('sale_quantity'),
            'sale_total' => (clone $records)->sum('sale_total'),
            'sale_vat' => (clone $records)->sum('sale_vat'),
            'sale_cost' => (clone $records)->sum('sale_cost'),
            'return_quantity' => (clone $records)->sum('return_quantity'),
            'return_total' => (clone $records)->sum('return_total'),
            'return_vat' => (clone $records)->sum('return_vat'),
            'return_cost' => (clone $records)->sum('return_cost'),
            'damage' => (clone $records)->sum('damage'),
            'adjustment' => (clone $records)->sum('adjustment'),
            'stock_variance' => (clone $records)->sum('stock_variance'),
        ];
        $this->calculateProfit($summary);

        return $summary;
    }

    private function calculateProfit(object $record): void
    {
        $record->sale_quantity = (int) $record->sale_quantity;
        $record->return_quantity = (int) $record->return_quantity;
        $record->sale_amount = (string) BigInteger::of($record->sale_total ?? 0)
            ->minus($record->sale_vat ?? 0);
        $record->return_amount = (string) BigInteger::of($record->return_total ?? 0)
            ->minus($record->return_vat ?? 0);
        $record->net_revenue = (string) BigInteger::of($record->sale_amount)
            ->minus($record->return_amount);
        $record->cost = (string) BigInteger::of($record->sale_cost ?? 0)
            ->minus($record->return_cost ?? 0);
        $record->damage = (string) BigInteger::of($record->damage ?? 0)
            ->negated();
        $record->other = (string) BigInteger::of($record->adjustment ?? 0)
            ->plus($record->stock_variance ?? 0)
            ->minus($record->damage);
        $record->gross_profit = (string) BigInteger::of($record->net_revenue)
            ->minus($record->cost);
        $record->profit = (string) BigInteger::of($record->gross_profit)
            ->plus($record->other);
    }

    private function settlementDiscounts(object $summary, array $filters, bool $includeSettlements): void
    {
        $summary->customer_discount = '0';
        $summary->supplier_discount = '0';
        if ($includeSettlements) {
            $payments = Payment::where('product_type', $filters['product_type'])
                ->whereNull('voided_at')
                ->whereBetween('payment_date', [$filters['from_date'], $filters['to_date']]);
            $summary->customer_discount = (clone $payments)->where('direction', 'customer')
                ->sum('discount');
            $summary->supplier_discount = (clone $payments)->where('direction', 'supplier')
                ->sum('discount');
        }
        $summary->net_profit = (string) BigInteger::of($summary->profit)
            ->minus($summary->customer_discount)
            ->plus($summary->supplier_discount);
    }
}
