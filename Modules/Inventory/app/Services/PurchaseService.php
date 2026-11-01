<?php

namespace Modules\Inventory\Services;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Services\AccessManager;
use Modules\Hospital\Contracts\InventoryCustomers;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\Purchase;
use Modules\Inventory\Models\Supplier;

class PurchaseService extends TradeEntryService
{
    protected string $model = Purchase::class;

    public function __construct(
        AccessManager $access,
        InventoryAccess $inventory,
        InventoryCustomers $customers,
        StockService $stock,
        ItemService $items,
        private PurchaseItemService $purchaseItems,
    ) {
        parent::__construct($access, $inventory, $customers, $stock, $items);
    }

    public function save(Account $actor, ?Document $record, array $values): Document
    {
        try {
            return parent::save($actor, $record, $values);
        } catch (UniqueConstraintViolationException $exception) {
            if (str_contains($exception->errorInfo[2] ?? '', 'inventory_supplier_invoice_unique')) {
                throw ValidationException::withMessages(['supplier_invoice_no' => 'This supplier invoice has already been entered for this branch.']);
            }

            throw $exception;
        }
    }

    protected function prepareEntry(Account $actor, Document $document, array $values, array $settings, Collection $previousLines): array
    {
        $this->assignSupplier($document, $values);
        $document->vat_mode = $values['vat_mode'] ?? 'exclusive';
        $document->vat_percent = '0.00';
        $document->vat_calculation = 'line';
        $lines = $this->purchaseItems->prepare($actor, $document, $values['lines'] ?? [], $previousLines, $settings);

        return $this->calculateTotals($document, $lines, $values);
    }

    private function assignSupplier(Document $document, array $values): void
    {
        $supplier = Supplier::query()->whereKey($values['supplier_id'] ?? 0)
            ->where('type', $document->product_type)->where('active', true)->first();
        if ($supplier === null) {
            throw ValidationException::withMessages(['supplier_id' => 'Choose a supplier for this product type.']);
        }
        $document->supplier_id = $supplier->id;
        $document->supplier_invoice_no = $values['supplier_invoice_no'] ?? null;
        $document->active_supplier_invoice_no = $document->supplier_invoice_no;
    }

    protected function applyVat(Document $document, array $lines, array $weights): array
    {
        return $this->purchaseItems->applyVat($document, $lines, $weights);
    }

    protected function postStock(Document $document): int
    {
        return $this->stock->receive($document);
    }
}
