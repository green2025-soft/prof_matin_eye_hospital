<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Pharmacy\Models\ProductUnit;
use Modules\Pharmacy\Models\Sale;
use Modules\Pharmacy\Models\SaleReturn;
use Modules\Pharmacy\Models\StockEntry;

class SaleReturnService extends ReturnEntryService
{
    protected string $model = SaleReturn::class;

    public function invoiceData(Account $actor, StockEntry $record): array
    {
        $data = parent::invoiceData($actor, $record);
        $data['entry']->load(['sale:id,number', 'paymentType:id,name']);
        $customerId = $data['entry']->customer_id;
        $data['customer'] = $this->customers->findMany($actor, [$customerId])[$customerId] ?? null;

        return $data;
    }

    protected function prepareForWrite(Account $actor, StockEntry $entry, array $values): Collection
    {
        if ($entry->exists) {
            $this->authorizeReturnUpdate($actor, $entry, $values);
            if ($entry->sale_id !== (int) $values['sale_id']) {
                throw ValidationException::withMessages(['sale_id' => 'A return must retain its original invoice.']);
            }
            $this->reverseStock($entry);
            $entry->lines()->update(['superseded_at' => now(config('app.timezone'))]);
            $entry->unsetRelation('lines');
        }
        $entry->forceFill(['payment_type_id' => $values['payment_type_id'] ?? null, 'total' => 0, 'initial_paid' => 0]);

        return collect();
    }

    protected function prepareEntry(Account $actor, StockEntry $entry, array $values, array $settings, Collection $previousLines): array
    {
        $sale = Sale::query()->whereKey($values['sale_id'] ?? 0)->lockForUpdate()->first();
        if ($sale === null || $sale->branch_id !== $entry->branch_id
            || $sale->product_type !== $entry->product_type || $sale->voided_at !== null || $sale->posted_at === null) {
            throw ValidationException::withMessages(['sale_id' => 'Choose an active original invoice from this branch.']);
        }
        $entry->sale_id = $sale->id;
        $entry->customer_id = $sale->customer_id;
        $inputs = $this->returnInputs($values['lines'] ?? []);
        if (count(array_unique(array_column($inputs, 'sale_item_id'))) !== count($inputs)) {
            throw ValidationException::withMessages(['lines' => 'Add each original invoice line once, up to 100 lines.']);
        }
        $originals = $sale->lines()->with('variant:id,product_id')->whereIn('id', array_column($inputs, 'sale_item_id'))
            ->orderBy('id')->lockForUpdate()->get()->keyBy('id');
        $units = ProductUnit::query()->whereIn('id', array_column($inputs, 'product_unit_id'))
            ->orderBy('id')->lockForUpdate()->get()->keyBy('id');
        $returnedAmounts = $this->returnedAmounts($sale);
        $lines = [];
        foreach ($inputs as $index => $input) {
            $original = $originals->get((int) ($input['sale_item_id'] ?? 0));
            if ($original === null) {
                throw ValidationException::withMessages(["lines.$index.sale_item_id" => 'Choose a line from the original invoice.']);
            }
            $unitId = (int) ($input['product_unit_id'] ?? $original->product_unit_id);
            $line = $this->returnLine($original, $units->get($unitId), $input, $returnedAmounts->get($original->id), $index);
            $line['sale_item_id'] = $original->id;
            $lines[] = $line;
        }
        $this->applyReturnTotals($entry, $sale, $lines);
        $this->validatePaymentType($entry, $entry->initial_paid);

        return $lines;
    }

    protected function reverseStock(StockEntry $entry): void
    {
        $sale = $entry->sale()->lockForUpdate()->firstOrFail();
        $this->reverseReturnBalance($entry, $sale);
        $originals = $sale->lines()->orderBy('id')->lockForUpdate()->get()->keyBy('id');
        $returned = $entry->lines()->orderBy('id')->lockForUpdate()->get();
        $this->stock->reverse($entry);
        foreach ($returned as $line) {
            $this->reverseReturnQuantity($originals->get($line->sale_item_id), $line);
        }
    }

    public function returnedAmounts(Sale $sale, ?SaleReturn $excluding = null): Collection
    {
        $activeReturns = function ($query) use ($sale, $excluding): void {
            $query->whereNull('superseded_at')->whereHas('saleReturn', function ($query) use ($sale, $excluding): void {
                $query->where('sale_id', $sale->id)->where('branch_id', $sale->branch_id)
                    ->where('product_type', $sale->product_type)->whereNull('voided_at')->whereNull('deleted_at');
                if ($excluding?->exists) {
                    $query->whereKeyNot($excluding->id);
                }
            });
        };

        return $sale->lines()->select('id')
            ->withSum(['returns as discount' => $activeReturns], 'discount')
            ->withSum(['returns as invoice_discount' => $activeReturns], 'invoice_discount')
            ->withSum(['returns as vat' => $activeReturns], 'vat')
            ->withSum(['returns as total' => $activeReturns], 'total')
            ->get()->keyBy('id');
    }

    protected function postStock(StockEntry $entry): int
    {
        return $this->stock->returnSale($entry);
    }
}
