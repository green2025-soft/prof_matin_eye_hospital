<?php

namespace Modules\Inventory\Services;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\Sale;

class SaleService extends TradeEntryService
{
    protected string $model = Sale::class;

    protected function prepareEntry(Account $actor, Document $document, array $values, array $settings, Collection $previousLines): array
    {
        $customerId = (int) ($values['customer_id'] ?? 0);
        $customer = $this->customers->findMany($actor, [$customerId])[$customerId] ?? null;
        $retainsCustomer = $document->exists && $customerId === (int) $document->getRawOriginal('customer_id');
        if ($customer === null || (! $retainsCustomer && $settings['customer_scope'] === 'customer' && $customer['type'] !== 'customer')) {
            throw ValidationException::withMessages(['customer_id' => 'Choose an available customer.']);
        }
        $document->customer_id = $customerId;
        $document->vat_mode = $document->exists ? $document->getRawOriginal('vat_mode') : ($settings['vat_enabled'] ? $settings['vat_mode'] : 'none');
        $document->vat_percent = $document->exists ? $document->getRawOriginal('vat_percent') : ($settings['vat_enabled'] ? $settings['vat_percent'] : '0.00');
        $document->vat_calculation = 'percent';
        if ($document->exists) {
            $document->stock_method = $document->getRawOriginal('stock_method');
        }

        $inputs = $values['lines'] ?? [];
        $lines = $this->items->prepareLines($document, $inputs);
        $fields = Document::visibleItemFields($settings, Sale::TYPE, $document->product_type);
        foreach ($lines as $index => &$line) {
            $input = $inputs[$index];
            $previous = $previousLines->get((int) ($input['original_line_id'] ?? 0));
            if (blank($input['unit_price'] ?? null)) {
                throw ValidationException::withMessages(["lines.$index.unit_price" => 'Enter the rate for the selected unit, including 0.00 for a free item.']);
            }
            if (! $fields['discount'] && filled($input['discount'] ?? null)) {
                throw ValidationException::withMessages(["lines.$index.discount" => 'This item field is hidden in Inventory Settings. Reload the form.']);
            }
            $discount = $fields['discount'] ? Money::minor($input['discount'] ?? '0') : ($previous?->discount_minor ?? 0);
            if ($discount > $line['subtotal_minor']) {
                throw ValidationException::withMessages(["lines.$index.discount" => 'Discount cannot exceed the line amount.']);
            }
            $line['discount_minor'] = $discount;
            $line['total_minor'] = $line['subtotal_minor'] - $discount;
            $line['batch_no'] = null;
            $line['expiry_date'] = null;
            $line['direction'] = null;
            $line['stock_lot_id'] = null;
        }
        unset($line);

        return $this->calculateTotals($document, $lines, $values);
    }

    protected function applyVat(Document $document, array $lines, array $weights): array
    {
        $net = $this->items->sum($weights);
        $percent = Money::minor((string) $document->vat_percent);
        $tax = match ($document->vat_mode) {
            'exclusive' => $this->items->roundedRatio($net, $percent, 10000),
            'inclusive' => $this->items->roundedRatio($net, $percent, 10000 + $percent),
            default => 0,
        };
        $taxes = $this->items->allocate($tax, $weights);
        foreach ($lines as $index => &$line) {
            $line['vat_mode'] = $document->vat_mode;
            $line['vat_percent'] = $document->vat_percent;
            $line['vat_calculation'] = 'percent';
            $line['vat_minor'] = $taxes[$index];
        }
        unset($line);

        return $lines;
    }

    protected function postStock(Document $document): int
    {
        $minimumExpiryDate = $document->product_type === 'medicine'
            ? max(now(config('app.timezone'))->toDateString(), $document->document_date->toDateString()) : null;

        $settings = Setting::values('inventory', $document->getConnectionName());

        return $this->stock->issue($document, $minimumExpiryDate, false, (bool) $settings[$document->product_type.'_sale_without_stock_enabled']);
    }
}
