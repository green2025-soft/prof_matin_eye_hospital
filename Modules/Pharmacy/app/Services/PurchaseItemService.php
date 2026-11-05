<?php

namespace Modules\Pharmacy\Services;

use Brick\Math\BigInteger;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Pharmacy\Models\Product;
use Modules\Pharmacy\Models\ProductUnit;
use Modules\Pharmacy\Models\ProductVariant;
use Modules\Pharmacy\Models\StockEntry;
use Modules\Pharmacy\Models\StockItem;

class PurchaseItemService
{
    public static function itemFields(array $settings, string $productType): array
    {
        $prefix = $productType.'_purchase_';

        return [
            'discount' => (bool) ($settings[$prefix.'discount_enabled'] ?? true),
            'vat' => (bool) ($settings[$prefix.'vat_enabled'] ?? true),
            'sale_price' => (bool) ($settings[$prefix.'sale_price_enabled'] ?? true),
            'expiry' => $productType === 'medicine' && (bool) ($settings[$prefix.'expiry_enabled'] ?? true),
        ];
    }

    public function __construct(
        private ItemService $items,
        private PurchaseUnitService $units,
        private PharmacyAccess $pharmacy,
    ) {}

    public function prepare(Account $actor, StockEntry $entry, array $inputs, Collection $previousLines, array $settings): array
    {
        $catalog = $this->items->loadCatalog($inputs);
        $units = $this->units->prepare($inputs, $catalog['products'], $catalog['units']);
        $fields = self::itemFields($settings, $entry->product_type);
        $lines = [];
        $salePrices = [];

        foreach ($inputs as $index => $input) {
            $variant = $catalog['variants']->get((int) ($input['variant_id'] ?? 0));
            $product = $this->purchaseProduct($variant, $catalog['products'], $entry->product_type, $index);
            $previous = $previousLines->get((int) ($input['original_line_id'] ?? 0));
            $unit = $this->units->resolve($product, $input, $previous, $units, $index);
            $input = $this->preserveHiddenFields($input, $previous, $fields, $unit->factor, $index);
            $lines[$index] = $this->snapshot($variant, $unit, $input, $previous, $index);
            $salePrices = $this->rememberSalePrice($salePrices, $product->id, $input, $index);
        }

        $this->updateSalePrices($actor, $catalog['products'], $salePrices);

        return $lines;
    }

    public function applyVat(array $lines, array $weights): array
    {
        foreach ($lines as $index => $line) {
            $lines[$index]['vat'] = $this->vatAmount($line, $weights[$index]);
        }

        return $lines;
    }

    private function purchaseProduct(?ProductVariant $variant, Collection $products, string $productType, int|string $index): Product
    {
        $product = $variant === null ? null : $products->get($variant->product_id);
        if ($product === null || ! $product->active || ! $variant->active || $product->type !== $productType) {
            throw ValidationException::withMessages(["lines.$index.variant_id" => 'Choose an active product for this invoice type.']);
        }

        return $product;
    }

    private function snapshot(ProductVariant $variant, ProductUnit $unit, array $input, ?StockItem $previous, int|string $index): array
    {
        $quantity = (int) ($input['quantity'] ?? 0);
        if ($quantity < 1 || $quantity > 1_000_000_000 || $unit->factor < 1) {
            throw ValidationException::withMessages(["lines.$index.quantity" => 'Enter a valid positive whole quantity.']);
        }
        if (blank($input['unit_price'] ?? null)) {
            throw ValidationException::withMessages(["lines.$index.unit_price" => 'Enter the rate for the selected unit, including 0.00 for a free item.']);
        }
        $price = Money::minor($input['unit_price']);
        $subtotal = $this->multiply($price, $quantity);
        $discount = $this->discount($input, $subtotal, $quantity, $index);
        $vat = $this->vatInput($input, $index);
        $salePrice = filled($input['sale_price_per_piece'] ?? null) ? Money::minor($input['sale_price_per_piece']) : null;

        return [
            'variant_id' => $variant->id, 'product_unit_id' => $unit->id,
            'quantity' => $quantity, 'factor' => $unit->factor, 'base_quantity' => $this->multiply($quantity, $unit->factor),
            'unit_price' => $price, 'subtotal' => $subtotal,
            ...$discount, ...$vat,
            'sale_price_per_piece' => $salePrice ?? $previous?->sale_price_per_piece,
            'invoice_discount' => 0, 'total' => $subtotal - $discount['discount'],
            'cost' => $subtotal - $discount['discount'],
            'batch_no' => $input['batch_no'] ?? null, 'expiry_date' => $input['expiry_date'] ?? null,
        ];
    }

    private function discount(array $input, int $subtotal, int $quantity, int|string $index): array
    {
        $mode = $input['discount_input'] ?? 'amount';
        $value = Money::minor($input['discount'] ?? '0');
        if ($mode === 'percent' && $value > 10000) {
            throw ValidationException::withMessages(["lines.$index.discount" => 'Discount percentage cannot exceed 100.']);
        }
        $amount = match ($mode) {
            'percent' => $this->items->roundedRatio($subtotal, $value, 10000),
            'unit' => $this->multiply($value, $quantity),
            default => $value,
        };
        if ($amount > $subtotal) {
            throw ValidationException::withMessages(["lines.$index.discount" => 'Discount cannot exceed the line amount.']);
        }

        return [
            'discount' => $amount, 'discount_calculation' => $mode,
            'discount_percent' => $mode === 'percent' ? Money::decimal($value) : null,
            'discount_unit_amount' => $mode === 'unit' ? $value : null,
        ];
    }

    private function vatInput(array $input, int|string $index): array
    {
        $mode = $input['vat_mode'] ?? null;
        if (! in_array($mode, ['none', 'inclusive', 'exclusive'], true)) {
            throw ValidationException::withMessages(["lines.$index.vat_mode" => 'Choose the VAT treatment for this item.']);
        }

        return [
            'vat_mode' => $mode, 'vat_percent' => (string) ($input['vat_percent'] ?? '0'),
            'vat_calculation' => $input['vat_input'] ?? 'percent',
            'vat_unit_amount' => ($input['vat_input'] ?? null) === 'unit' ? Money::minor($input['vat_amount'] ?? '0') : null,
            'vat' => Money::minor($input['vat_amount'] ?? '0'),
        ];
    }

    private function preserveHiddenFields(array $input, ?StockItem $previous, array $fields, int $factor, int|string $index): array
    {
        $preserved = [];
        if (! $fields['discount']) {
            $preserved += $this->savedDiscount($previous, $factor);
        }
        if (! $fields['vat']) {
            $preserved += $this->savedVat($previous, $factor);
        }
        if (! $fields['sale_price']) {
            $preserved['sale_price_per_piece'] = null;
        }
        if (! $fields['expiry']) {
            $preserved['expiry_date'] = $previous?->expiry_date?->toDateString();
        }
        $this->validateHiddenFields($input, $preserved, $index);

        return array_replace($input, $preserved);
    }

    private function savedDiscount(?StockItem $previous, int $factor): array
    {
        $mode = $previous?->discount_calculation ?? 'amount';
        $value = match ($mode) {
            'percent' => $previous?->discount_percent ?? '0',
            'unit' => Money::decimal($previous === null ? 0 : $this->items->roundedRatio($previous->discount_unit_amount ?? 0, $factor, $previous->factor)),
            default => Money::decimal($previous?->discount ?? 0),
        };

        return ['discount' => $value, 'discount_input' => $mode];
    }

    private function savedVat(?StockItem $previous, int $factor): array
    {
        $calculation = $previous?->vat_calculation ?? 'amount';
        $amount = $previous?->vat ?? 0;
        if ($calculation === 'unit' && $previous !== null) {
            $amount = $this->items->roundedRatio($previous->vat_unit_amount ?? 0, $factor, $previous->factor);
        }

        return [
            'vat_mode' => $previous?->vat_mode ?? 'none',
            'vat_input' => $calculation,
            'vat_percent' => $previous?->vat_percent ?? '0',
            'vat_amount' => Money::decimal($amount),
        ];
    }

    private function validateHiddenFields(array $input, array $preserved, int|string $index): void
    {
        foreach ($preserved as $field => $value) {
            if (filled($input[$field] ?? null)) {
                throw ValidationException::withMessages(["lines.$index.$field" => 'This item field is hidden in Pharmacy Settings. Reload the form.']);
            }
            if (in_array($field, ['discount', 'vat_amount'], true) && ! preg_match('/^\d{1,10}(?:\.\d{1,2})?$/D', (string) $value)) {
                throw ValidationException::withMessages(["lines.$index.unit_price" => 'The saved discount or VAT exceeds the amount limit for this unit. Choose a smaller unit or enable its field in Pharmacy Settings.']);
            }
        }
    }

    private function rememberSalePrice(array $prices, int $productId, array $input, int|string $index): array
    {
        if (blank($input['sale_price_per_piece'] ?? null)) {
            return $prices;
        }
        $price = Money::minor($input['sale_price_per_piece']);
        if (array_key_exists($productId, $prices) && $prices[$productId] !== $price) {
            throw ValidationException::withMessages(["lines.$index.sale_price_per_piece" => 'Use the same sale price per piece for every row of this product.']);
        }
        $prices[$productId] = $price;

        return $prices;
    }

    private function updateSalePrices(Account $actor, Collection $products, array $salePrices): void
    {
        $changes = [];
        foreach ($salePrices as $productId => $salePrice) {
            $product = $products->get($productId);
            $price = Money::decimal($salePrice);
            if ($product->sale_price === $price) {
                continue;
            }
            $changes[$productId] = $price;
        }
        if ($changes === []) {
            return;
        }
        $this->pharmacy->authorize($actor, 'pharmacy-products.update');
        // Authorize the full batch before changing any catalog price.
        foreach (array_keys($changes) as $productId) {
            Gate::forUser($actor)->authorize('update', $products->get($productId));
        }
        foreach ($changes as $productId => $price) {
            $product = $products->get($productId);
            $product->sale_price = $price;
            $product->save();
        }
    }

    private function multiply(int $value, int $quantity): int
    {
        return $this->items->bounded(BigInteger::of($value)->multipliedBy($quantity));
    }

    private function vatAmount(array $line, int $netAmount): int
    {
        $rate = Money::minor((string) $line['vat_percent']);
        if ($line['vat_mode'] === 'none') {
            if ($rate > 0 || $line['vat'] > 0 || ($line['vat_unit_amount'] ?? 0) > 0) {
                throw ValidationException::withMessages(['lines' => 'Choose VAT added or VAT included to record item VAT.']);
            }

            return 0;
        }
        $tax = match ($line['vat_calculation']) {
            'amount' => $line['vat'],
            'unit' => $this->multiply($line['vat_unit_amount'], $line['quantity']),
            default => $this->items->roundedRatio($netAmount, $rate, $line['vat_mode'] === 'inclusive' ? 10000 + $rate : 10000),
        };
        if ($line['vat_mode'] === 'inclusive' && $tax > $netAmount) {
            throw ValidationException::withMessages(['lines' => 'Included VAT cannot exceed its item amount after discounts.']);
        }

        return $tax;
    }
}
