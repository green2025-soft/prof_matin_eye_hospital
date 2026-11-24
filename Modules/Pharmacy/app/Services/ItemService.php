<?php

namespace Modules\Pharmacy\Services;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\ProductUnit;
use Modules\Pharmacy\Models\ProductVariant;
use Modules\Pharmacy\Models\StockEntry;

class ItemService
{
    private const MAX_AMOUNT = 9_000_000_000_000_000;

    public function loadCatalog(array $inputs): array
    {
        if ($inputs === [] || count($inputs) > 100) {
            throw ValidationException::withMessages(['lines' => 'Add between 1 and 100 product lines.']);
        }
        foreach ($inputs as $index => $input) {
            if (! is_array($input)) {
                throw ValidationException::withMessages(["lines.$index" => 'Enter a valid product line.']);
            }
        }

        $variants = ProductVariant::whereIn('id', array_column($inputs, 'variant_id'))
            ->orderBy('id')->lockForUpdate()->get()->keyBy('id');
        $products = Product::whereIn('id', $variants->pluck('product_id'))
            ->orderBy('id')->lockForUpdate()->get()->keyBy('id');
        $units = ProductUnit::whereIn('id', array_filter(array_column($inputs, 'product_unit_id')))
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
        $baseUnits = ProductUnit::whereIn('product_id', $products->modelKeys())
            ->where('factor', 1)
            ->orderBy('product_id')
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->unique('product_id')
            ->keyBy('product_id');

        return compact('variants', 'products', 'units', 'baseUnits');
    }

    public function selectedVariants(array $inputs): Collection
    {
        $ids = collect($inputs)
            ->pluck('variant_id')
            ->filter(fn ($id): bool => is_scalar($id) && ctype_digit((string) $id))
            ->unique()
            ->all();
        if ($ids === []) {
            return collect();
        }

        return ProductVariant::with([
            'product:id,name,type,purchase_price,sale_price',
            'latestPurchaseItem:id,variant_id,unit_price,factor',
            'product.units' => fn ($query) => $query
                ->orderBy('factor')
                ->orderBy('id'),
            'product.units.unit:id,name,symbol',
        ])
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');
    }

    public function optionRecords(Builder $products, string $search, int $page): Paginator
    {
        $query = ProductVariant::where('active', true)
            ->whereIn('product_id', $products->select('id'))
            ->with([
                'product:id,name,purchase_price,sale_price',
                'product.units' => fn ($units) => $units
                    ->select(['id', 'product_id', 'unit_id', 'factor'])
                    ->orderBy('factor')
                    ->orderBy('id'),
                'product.units.unit:id,name,symbol',
            ]);

        return $this->searchOptions($query, $search)
            ->orderBy('sku')
            ->orderBy('id')
            ->simplePaginate(20, ['id', 'product_id', 'sku', 'name', 'color', 'size', 'sph', 'cyl'], 'page', $page);
    }

    public function optionResults(Paginator $records, Collection $balances): array
    {
        $results = [];

        foreach ($records as $variant) {
            $product = $variant->product;
            $description = $variant->description();
            $units = [];

            foreach ($product->units as $productUnit) {
                $unit = $productUnit->unit;
                $units[] = [
                    'id' => $productUnit->id,
                    'unit_id' => $productUnit->unit_id,
                    'name' => $unit->symbol ?: $unit->name,
                    'symbol' => $unit->symbol,
                    'factor' => $productUnit->factor,
                ];
            }

            $results[] = [
                'id' => $variant->id,
                'product_id' => $variant->product_id,
                'name' => $product->name.' / '.$description,
                'description' => $description,
                'product_name' => $product->name,
                'sku' => $variant->sku,
                'available_quantity' => (string) ($balances->get($variant->id) ?? 0),
                'purchase_price' => $product->purchase_price,
                'sale_price' => $product->sale_price,
                'units' => $units,
            ];
        }

        return $results;
    }

    private function searchOptions(Builder $query, string $search): Builder
    {
        if ($search === '') {
            return $query;
        }

        $exactQuery = (clone $query)->where(fn ($matches) => $matches
            ->where('sku', $search)
            ->orWhereHas('product', fn ($products) => $products
                ->where('code', $search)
                ->orWhere('barcode', $search)));

        // A scanned identifier must not include unrelated partial name matches.
        if ($exactQuery->exists()) {
            return $exactQuery;
        }

        $term = '%'.addcslashes($search, '\\%_').'%';
        $numeric = ctype_digit($search);

        return $query->where(function ($matches) use ($search, $term, $numeric): void {
            if ($numeric) {
                $digits = str_pad($search, 6, '0', STR_PAD_LEFT);
                $matches->whereIn('sku', [$search, 'MED-'.$search, 'OPT-'.$search, 'MED-'.$digits, 'OPT-'.$digits]);
            } else {
                $matches->whereLike('sku', $term);
            }

            $matches->orWhereLike('name', $term)
                ->orWhereHas('product', function ($products) use ($search, $term, $numeric): void {
                    $products->whereLike('name', $term);

                    if ($numeric) {
                        $products->orWhereKey($search)
                            ->orWhere('source_key', 'drug_medicine:'.$search);
                    } else {
                        $products->orWhereLike('code', $term);
                    }
                });
        });
    }

    public function prepareQuantities(StockEntry $entry, array $inputs): array
    {
        $catalog = $this->loadCatalog($inputs);
        $lines = [];

        foreach ($inputs as $index => $input) {
            $variant = $catalog['variants']->get((int) ($input['variant_id'] ?? 0));
            $product = $variant === null ? null : $catalog['products']->get($variant->product_id);
            if ($product === null || ! $product->active || ! $variant->active || $product->type !== $entry->product_type) {
                throw ValidationException::withMessages(["lines.$index.variant_id" => 'Choose an active product for this entry type.']);
            }
            $unit = $catalog['units']->get((int) ($input['product_unit_id'] ?? 0));
            if ($unit === null || (int) $unit->product_id !== (int) $product->id) {
                throw ValidationException::withMessages(["lines.$index.product_unit_id" => 'Choose a unit belonging to this product.']);
            }
            if (! $catalog['baseUnits']->has($product->id)) {
                throw ValidationException::withMessages(["lines.$index.product_unit_id" => 'Set up a base unit or record a purchase for this product first.']);
            }

            $quantity = $input['quantity'] ?? 0;
            if (! preg_match('/^\d{1,10}$/D', (string) $quantity)
                || (int) $quantity < 1 || (int) $quantity > 1_000_000_000 || $unit->factor < 1) {
                throw ValidationException::withMessages(["lines.$index.quantity" => 'Enter a valid positive whole quantity.']);
            }
            $quantity = (int) $quantity;
            $baseQuantity = $this->bounded(BigInteger::of($quantity)->multipliedBy($unit->factor));
            $lines[$index] = [
                'variant_id' => $variant->id,
                'product_unit_id' => $unit->id,
                'quantity' => $quantity,
                'factor' => $unit->factor,
                'base_quantity' => $baseQuantity,
            ];
        }

        return $lines;
    }

    public function allocate(int $amount, array $weights): array
    {
        $total = $this->sum($weights);
        $allocated = 0;
        $cumulative = 0;
        $parts = [];
        foreach ($weights as $index => $weight) {
            $cumulative += $weight;
            $target = $total === 0 ? 0 : $this->floorRatio($amount, $cumulative, $total);
            $parts[$index] = $target - $allocated;
            $allocated = $target;
        }

        return $parts;
    }

    public function roundedRatio(int $amount, int $numerator, int $denominator): int
    {
        $rounded = BigInteger::of($amount)
            ->multipliedBy($numerator)
            ->plus(intdiv($denominator, 2))
            ->quotient($denominator);

        return $this->bounded($rounded);
    }

    public function floorRatio(int $amount, int $numerator, int $denominator): int
    {
        $ratio = BigInteger::of($amount)
            ->multipliedBy($numerator)
            ->quotient($denominator);

        return $this->bounded($ratio);
    }

    public function sum(array $amounts): int
    {
        $total = BigInteger::sum(0, ...array_values($amounts));

        return $this->bounded($total);
    }

    public function bounded(BigInteger $amount): int
    {
        if ($amount->isNegative() || $amount->isGreaterThan(self::MAX_AMOUNT)) {
            throw ValidationException::withMessages(['lines' => 'The quantity or total exceeds the supported limit.']);
        }

        return $amount->toInt();
    }
}
