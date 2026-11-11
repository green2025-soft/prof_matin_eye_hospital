<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\ProductUnit;
use Modules\Pharmacy\Models\StockItem;
use Modules\Pharmacy\Models\Unit;

class PurchaseUnitService
{
    public function prepare(array $inputs, Collection $products, Collection $savedUnits, Collection $baseUnits): array
    {
        $piece = Unit::lockForUpdate()
            ->firstOrCreate(['symbol' => 'Pcs'], ['name' => 'Piece']);
        $unitIds = array_filter([...array_column($inputs, 'unit_id'), ...$savedUnits->pluck('unit_id')->all(), $piece->id]);
        $choices = Unit::whereIn('id', $unitIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
        $pieceProductIds = $baseUnits->where('unit_id', $piece->id)
            ->keys()
            ->all();

        // Catalog loading already locked these products and their existing base units.
        foreach ($products->except($pieceProductIds)->sortBy('id') as $product) {
            ProductUnit::lockForUpdate()
                ->firstOrCreate([
                    'product_id' => $product->id,
                    'unit_id' => $piece->id,
                    'factor' => 1,
                ]);
        }

        return [
            'piece' => $piece,
            'choices' => $choices,
            'saved' => $savedUnits,
        ];
    }

    public function resolve(Product $product, array $input, ?StockItem $previous, array $units, int|string $index): ProductUnit
    {
        $unit = filled($input['product_unit_id'] ?? null)
            ? $this->resolveSaved($product, $input, $units, $index)
            : $this->resolvePack($product, $input, $units, $index);

        if ($product->type !== 'optic') {
            return $unit;
        }
        if ((int) $unit->unit_id === (int) $units['piece']->id && $unit->factor === 1) {
            return $unit;
        }
        if ($previous !== null && $unit->id === $previous->product_unit_id && $unit->factor === $previous->factor) {
            return $unit;
        }

        throw ValidationException::withMessages(["lines.$index.unit_id" => 'Optic purchases use Pcs. An older item must retain its saved unit.']);
    }

    private function resolveSaved(Product $product, array $input, array $units, int|string $index): ProductUnit
    {
        if (filled($input['unit_id'] ?? null) || filled($input['unit_factor'] ?? null)) {
            throw ValidationException::withMessages(["lines.$index.product_unit_id" => 'Choose a saved conversion or enter a new pack, using one option at a time.']);
        }
        $unit = $units['saved']->get((int) $input['product_unit_id']);
        if ($unit === null || (int) $unit->product_id !== (int) $product->id) {
            throw ValidationException::withMessages(["lines.$index.product_unit_id" => 'Choose a saved unit belonging to this product.']);
        }
        $choice = $units['choices']->get((int) $unit->unit_id);
        if ($choice === null) {
            throw ValidationException::withMessages(["lines.$index.product_unit_id" => 'The selected unit is no longer available.']);
        }
        $this->validateFactor($product, $choice, $unit->factor, $units['piece'], "lines.$index.product_unit_id");

        return $unit;
    }

    private function resolvePack(Product $product, array $input, array $units, int|string $index): ProductUnit
    {
        $choice = $units['choices']->get((int) ($input['unit_id'] ?? 0));
        $factor = (int) ($input['unit_factor'] ?? 0);
        if ($choice === null || $factor < 1 || $factor > 1_000_000) {
            throw ValidationException::withMessages(["lines.$index.unit_factor" => 'Choose a unit and enter between 1 and 1,000,000 pieces per unit.']);
        }
        $this->validateFactor($product, $choice, $factor, $units['piece'], "lines.$index.unit_factor");

        return ProductUnit::lockForUpdate()
            ->firstOrCreate([
                'product_id' => $product->id,
                'unit_id' => $choice->id,
                'factor' => $factor,
            ]);
    }

    private function validateFactor(Product $product, Unit $unit, int $factor, Unit $piece, string $field): void
    {
        if ((int) $unit->id === (int) $piece->id && $factor !== 1) {
            throw ValidationException::withMessages([$field => 'Pcs always contains one piece. Select a pack unit for a larger conversion.']);
        }
        if ($product->type !== 'optic') {
            return;
        }
        $symbol = mb_strtolower(trim($unit->symbol));
        if (! in_array($symbol, ['pcs', 'pair', 'box'], true)) {
            throw ValidationException::withMessages([$field => 'Optic purchases use Piece, Pair, or Box.']);
        }
        if ($symbol === 'pair' && $factor !== 2) {
            throw ValidationException::withMessages([$field => 'A Pair contains exactly two pieces.']);
        }
        if ($symbol === 'box' && ($factor < 1 || $factor > 1_000_000)) {
            throw ValidationException::withMessages([$field => 'Enter between 1 and 1,000,000 pieces per Box.']);
        }
    }
}
