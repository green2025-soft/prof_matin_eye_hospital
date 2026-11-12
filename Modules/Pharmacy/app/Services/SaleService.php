<?php

namespace Modules\Pharmacy\Services;

use Brick\Math\BigInteger;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Services\SmsService;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\StockEntry;

class SaleService extends TradeEntryService
{
    protected string $model = Sale::class;

    public static function itemFields(array $settings, string $productType): array
    {
        return ['discount' => (bool) ($settings[$productType.'_sale_discount_enabled'] ?? true)];
    }

    public function invoiceData(Account $actor, StockEntry $record): array
    {
        $data = parent::invoiceData($actor, $record);
        $data['entry']->load('paymentType:id,name');
        $customerId = $data['entry']->customer_id;
        $data['customer'] = $this->customers->findMany($actor, [$customerId])[$customerId] ?? null;

        return $data;
    }

    protected function prepareEntry(Account $actor, StockEntry $entry, array $values, array $settings, Collection $previousLines): array
    {
        $customerId = (int) ($values['customer_id'] ?? 0);
        $customer = $this->customers->findMany($actor, [$customerId])[$customerId] ?? null;
        $retainsCustomer = $entry->exists && $customerId === (int) $entry->getRawOriginal('customer_id');
        if ($customer === null || (! $retainsCustomer && $settings['customer_scope'] === 'customer' && $customer['type'] !== 'customer')) {
            throw ValidationException::withMessages(['customer_id' => 'Choose an available customer.']);
        }
        $entry->customer_id = $customerId;

        // Existing invoices keep their saved VAT and stock-method settings.
        if (! $entry->exists) {
            $entry->vat_mode = $settings['vat_enabled'] ? $settings['vat_mode'] : 'none';
            $entry->vat_percent = $settings['vat_enabled'] ? $settings['vat_percent'] : '0.00';
            $entry->stock_method = $settings['stock_method'];
        }

        $inputs = $values['lines'] ?? [];
        $lines = $this->items->prepareQuantities($entry, $inputs);
        $fields = self::itemFields($settings, $entry->product_type);
        foreach ($lines as $index => $line) {
            $input = $inputs[$index];
            $previous = $previousLines->get((int) ($input['original_line_id'] ?? 0));
            if (! $fields['discount'] && filled($input['discount'] ?? null)) {
                throw ValidationException::withMessages(["lines.$index.discount" => 'This item field is hidden in Pharmacy Settings. Reload the form.']);
            }
            $price = Money::minor($input['unit_price'] ?? '0');
            $line['unit_price'] = $price;
            $line['subtotal'] = $this->items->bounded(BigInteger::of($price)->multipliedBy($line['quantity']));
            $discount = $fields['discount'] ? Money::minor($input['discount'] ?? '0') : ($previous?->discount ?? 0);
            if ($discount > $line['subtotal']) {
                throw ValidationException::withMessages(["lines.$index.discount" => 'Discount cannot exceed the line amount.']);
            }
            $line['discount'] = $discount;
            $line['total'] = $line['subtotal'] - $discount;
            $lines[$index] = $line;
        }

        $lines = $this->calculateTotals($entry, $lines, $values);
        $this->validatePaymentType($entry, $entry->paid);

        return $lines;
    }

    protected function applyVat(StockEntry $entry, array $lines, array $weights): array
    {
        $net = $this->items->sum($weights);
        $percent = Money::minor((string) $entry->vat_percent);
        $tax = match ($entry->vat_mode) {
            'exclusive' => $this->items->roundedRatio($net, $percent, 10000),
            'inclusive' => $this->items->roundedRatio($net, $percent, 10000 + $percent),
            default => 0,
        };
        $taxes = $this->items->allocate($tax, $weights);
        foreach ($lines as $index => $line) {
            $line['vat_mode'] = $entry->vat_mode;
            $line['vat'] = $taxes[$index];
            $lines[$index] = $line;
        }

        return $lines;
    }

    protected function postStock(StockEntry $entry): int
    {
        $minimumExpiryDate = $entry->product_type === 'medicine'
            ? max(now(config('app.timezone'))->toDateString(), $entry->entry_date->toDateString()) : null;

        $settings = Setting::values('pharmacy', $entry->getConnectionName());

        return $this->stock->issueSale($entry, $minimumExpiryDate, (bool) $settings[$entry->product_type.'_sale_without_stock_enabled']);
    }

    protected function afterCreated(Account $actor, StockEntry $entry, array $settings): void
    {
        $prefix = 'sms_'.$entry->product_type.'_sale';
        $enabled = (bool) ($settings[$prefix.'_enabled'] ?? false);
        if (! $enabled) {
            return;
        }

        $customer = $this->customers->findMany($actor, [$entry->customer_id])[$entry->customer_id] ?? null;
        if (blank($customer['mobile_no'] ?? null)) {
            return;
        }

        app(SmsService::class)->queue(
            $entry,
            'pharmacy.'.$entry->product_type.'.sale',
            'pharmacy.sale.created:'.$entry->getKey(),
            $customer['mobile_no'],
            $enabled,
            $settings[$prefix.'_template'] ?? '',
            [
                ...($customer['sms_values'] ?? []),
                'name' => $customer['name'],
                'invoice' => $entry->number,
                'date' => $entry->entry_date->format('d-m-Y'),
                'total' => Money::decimal($entry->total),
                'paid' => Money::decimal($entry->paid),
                'due' => Money::decimal($entry->outstanding),
                'amount' => Money::decimal($entry->total),
            ],
        );
    }
}
