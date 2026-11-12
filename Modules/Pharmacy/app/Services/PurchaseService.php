<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Pharmacy\Models\Purchase;
use Modules\Pharmacy\Models\StockEntry;
use Modules\Pharmacy\Models\Supplier;

class PurchaseService extends TradeEntryService
{
    protected string $model = Purchase::class;

    public function invoiceData(Account $actor, StockEntry $record): array
    {
        $data = parent::invoiceData($actor, $record);
        $data['entry']->load(['supplier:id,name,contact_no,address', 'paymentType:id,name']);
        $data['paidAmountWords'] = Money::words($data['entry']->paid);

        return $data;
    }

    protected function voidHeader(StockEntry $entry): void
    {
        $entry->active_supplier_invoice_no = null;
        parent::voidHeader($entry);
    }

    public function __construct(
        PharmacyAccess $pharmacy,
        PharmacyCustomers $customers,
        StockService $stock,
        ItemService $items,
        private PurchaseItemService $purchaseItems,
    ) {
        parent::__construct($pharmacy, $customers, $stock, $items);
    }

    public function save(Account $actor, ?StockEntry $record, array $values): StockEntry
    {
        try {
            return parent::save($actor, $record, $values);
        } catch (UniqueConstraintViolationException $exception) {
            if (str_contains($exception->errorInfo[2] ?? '', 'phr_supplier_invoice_unique')) {
                throw ValidationException::withMessages(['supplier_invoice_no' => 'This supplier invoice has already been entered for this branch.']);
            }

            throw $exception;
        }
    }

    protected function prepareEntry(Account $actor, StockEntry $entry, array $values, array $settings, Collection $previousLines): array
    {
        $this->assignSupplier($entry, $values);
        $lines = $this->purchaseItems->prepare($actor, $entry, $values['lines'] ?? [], $previousLines, $settings);
        $lines = $this->calculateTotals($entry, $lines, $values);
        $this->validatePaymentType($entry, $entry->paid);

        return $lines;
    }

    private function assignSupplier(StockEntry $entry, array $values): void
    {
        $supplier = Supplier::whereKey($values['supplier_id'] ?? 0)
            ->where('type', $entry->product_type)
            ->where('active', true)
            ->first();

        if ($supplier === null) {
            throw ValidationException::withMessages(['supplier_id' => 'Choose a supplier for this product type.']);
        }

        $entry->supplier_id = $supplier->id;
        $entry->supplier_invoice_no = $values['supplier_invoice_no'] ?? null;
        $entry->active_supplier_invoice_no = $entry->supplier_invoice_no;
    }

    protected function applyVat(StockEntry $entry, array $lines, array $weights): array
    {
        return $this->purchaseItems->applyVat($lines, $weights);
    }

    protected function postStock(StockEntry $entry): int
    {
        return $this->stock->receive($entry);
    }
}
