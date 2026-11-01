<?php

namespace Modules\Inventory\Services;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Services\AccessManager;
use Modules\Hospital\Contracts\InventoryCustomers;
use Modules\Inventory\Models\Document;
use Modules\Inventory\Models\DocumentLine;
use Modules\TenantCore\Models\Branch;

abstract class StockEntryService
{
    protected string $model;

    public function __construct(
        protected AccessManager $access,
        protected InventoryAccess $inventory,
        protected InventoryCustomers $customers,
        protected StockService $stock,
        protected ItemService $items,
    ) {}

    public function save(Account $actor, ?Document $record, array $values): Document
    {
        try {
            return $this->access->transaction($actor, function (Account $freshActor) use ($record, $values): Document {
                $document = $this->lockedEntry($record);
                $action = $document->exists ? 'update' : 'create';
                $this->inventory->authorize($freshActor, $document->crudPermission().'.'.$action);
                $previousLines = $this->prepareForWrite($freshActor, $document, $values);
                $settings = Setting::values('inventory', $document->getConnectionName());
                $this->fillHeader($document, $values, $settings);
                $lines = $this->prepareEntry($freshActor, $document, $values, $settings, $previousLines);
                $this->validatePaymentType($document);
                if (! $document->exists) {
                    $document->number = $this->nextNumber($document, $settings);
                }

                $document->save();
                foreach ($lines as $line) {
                    $document->lines()->save((new DocumentLine)->forceFill($line));
                }
                $document->cost_minor = $this->postStock($document);
                $document->posted_at = now(config('app.timezone'));
                $document->save();

                return $document;
            });
        } catch (UniqueConstraintViolationException $exception) {
            if (str_contains($exception->errorInfo[2] ?? '', 'inventory_documents_number_unique')) {
                throw ValidationException::withMessages(['number' => 'This invoice number already exists. Change its pattern in Inventory Settings.']);
            }

            throw $exception;
        }
    }

    public function reverse(Account $actor, Document $record): void
    {
        $this->access->transaction($actor, function (Account $freshActor) use ($record): void {
            $document = $this->lockedEntry($record);
            $this->inventory->authorize($freshActor, $document->crudPermission().'.void');
            if ($document->branch_id !== (int) request()->attributes->get('branch_id') || $document->voided_at !== null) {
                throw ValidationException::withMessages(['record' => 'Only the source branch can void an active entry.']);
            }

            $this->assertNoActiveLinks($document, 'voiding');
            $this->reverseStock($document);
            $document->voided_at = now(config('app.timezone'));
            $document->active_supplier_invoice_no = null;
            $document->save();
        });
    }

    public function delete(Account $actor, Document $record): void
    {
        $this->access->transaction($actor, function (Account $freshActor) use ($record): void {
            $document = $this->lockedEntry($record);
            $this->inventory->authorize($freshActor, $document->crudPermission().'.delete');
            Gate::forUser($freshActor)->authorize('delete', $document);
            $this->assertNoActiveLinks($document, 'deleting');
            if ($document->voided_at === null) {
                $this->reverseStock($document);
                $document->voided_at = now(config('app.timezone'));
            }
            $document->active_supplier_invoice_no = null;
            $document->save();
            $document->delete();
        });
    }

    public function invoiceData(Account $actor, Document $record): array
    {
        $model = $this->model;
        $document = $model::query()->findOrFail($record->id);
        Gate::forUser($actor)->authorize('view', $document);
        $document->load([
            'lines.variant.product:id,name,type', 'lines.productUnit.unit:id,name,symbol',
            'supplier:id,name,contact_no,address', 'destinationBranch:id,name',
            'paymentType:id,name', 'sourceDocument:id,number,type',
        ]);
        $company = Setting::values('company', $document->getConnectionName());
        $branding = Setting::values('branding', $document->getConnectionName());
        $branch = Branch::query()->find($document->branch_id);
        $phone = implode(' / ', array_filter([$branch?->mobile_no, $branch?->telephone_no], fn ($value): bool => filled($value)));
        $logoPath = $branch?->logo_path ?: $branding['logo_path'];
        $companyName = $company['name'] ?: $branding['app_name'];
        $customer = $document->customer_id
            ? ($this->customers->findMany($actor, [$document->customer_id])[$document->customer_id] ?? null) : null;

        return [
            'document' => $document, 'customer' => $customer,
            'company' => [
                'name' => $companyName,
                'branch_name' => $branch?->name && $branch->name !== $companyName ? $branch->name : '',
                'address' => $branch?->address ?: $company['address'],
                'phone' => $phone ?: $company['phone'], 'email' => $branch?->email ?: $company['email'],
                'tax_id' => $company['tax_id'],
            ],
            'logoUrl' => filled($logoPath) ? Storage::disk('public')->url($logoPath) : null,
            'paidAmountWords' => Money::words($document->paid_minor),
            'printedBy' => $actor->name, 'printedAt' => now(config('app.timezone'))->format('d M Y h:i A'),
            'canVoid' => Gate::forUser($actor)->allows('void', $document),
        ];
    }

    protected function lockedEntry(?Document $record): Document
    {
        $model = $this->model;
        if ($record === null) {
            return new $model;
        }
        if (! $record instanceof $model) {
            throw ValidationException::withMessages(['record' => 'Choose an entry belonging to this resource.']);
        }

        return $model::query()->whereKey($record->id)->lockForUpdate()->firstOrFail();
    }

    protected function prepareForWrite(Account $actor, Document $document, array $values): Collection
    {
        if ($document->exists) {
            throw ValidationException::withMessages(['record' => 'Posted entries cannot be edited.']);
        }

        return collect();
    }

    abstract protected function prepareEntry(Account $actor, Document $document, array $values, array $settings, Collection $previousLines): array;

    abstract protected function postStock(Document $document): int;

    protected function reverseStock(Document $document): void
    {
        $this->stock->reverse($document);
    }

    protected function assertNoActiveLinks(Document $document, string $action): void
    {
        $connection = $document->getConnection();
        $hasPayments = $connection->table('inventory_payment_allocations as allocations')
            ->join('inventory_payments as payments', 'payments.id', '=', 'allocations.payment_id')
            ->where('allocations.document_id', $document->id)->whereNull('payments.voided_at')->exists();
        $hasReturns = $connection->table('inventory_documents')->where('source_document_id', $document->id)
            ->whereNull('voided_at')->whereNull('deleted_at')->exists();
        if ($hasPayments || $hasReturns) {
            throw ValidationException::withMessages(['record' => "Void linked payments and returns before $action this entry."]);
        }
    }

    private function fillHeader(Document $document, array $values, array $settings): void
    {
        $document->forceFill([
            'branch_id' => (int) request()->attributes->get('branch_id'),
            'type' => $document::TYPE,
            'product_type' => $values['product_type'],
            'document_date' => $values['document_date'],
            'stock_method' => $settings['stock_method'],
            'vat_mode' => 'none', 'vat_percent' => '0.00', 'vat_calculation' => 'percent',
            'reason' => $values['reason'] ?? null, 'notes' => $values['notes'] ?? null,
            'payment_type_id' => $values['payment_type_id'] ?? null,
            'supplier_invoice_no' => null, 'active_supplier_invoice_no' => null,
            'customer_id' => null, 'supplier_id' => null, 'source_document_id' => null, 'destination_branch_id' => null,
            'posted_at' => null, 'stock_reversed_at' => null, 'voided_at' => null,
            'subtotal_minor' => 0, 'discount_minor' => 0, 'vat_minor' => 0, 'total_minor' => 0,
            'paid_minor' => 0, 'initial_paid_minor' => 0, 'returned_minor' => 0, 'refunded_minor' => 0,
            'settlement_discount_minor' => 0, 'cost_minor' => 0,
        ]);
        if (! in_array($document->product_type, ['medicine', 'optic'], true)) {
            throw ValidationException::withMessages(['product_type' => 'Choose Medicine or Optic.']);
        }
        if ($document->document_date->gt(now(config('app.timezone'))->startOfDay())) {
            throw ValidationException::withMessages(['document_date' => 'The date cannot be in the future.']);
        }
    }

    private function validatePaymentType(Document $document): void
    {
        if ($document->paid_minor > 0 && $document->payment_type_id === null) {
            throw ValidationException::withMessages(['payment_type_id' => 'Choose a payment type for the cash payment or refund.']);
        }
        if ($document->payment_type_id !== null && ! $document->getConnection()->table('payment_types')->where('id', $document->payment_type_id)->exists()) {
            throw ValidationException::withMessages(['payment_type_id' => 'Choose a valid payment type.']);
        }
    }

    private function nextNumber(Document $document, array $settings): string
    {
        $scope = $document::TYPE.':'.$document->branch_id;
        $sequences = $document->getConnection()->table('inventory_sequences');
        $sequences->insertOrIgnore(['scope' => $scope, 'last_number' => 0]);
        $sequence = (clone $sequences)->where('scope', $scope)->lockForUpdate()->first();
        $number = (int) $sequence->last_number + 1;
        if ($number > 999999999999) {
            throw ValidationException::withMessages(['record' => 'The transaction numbering sequence is exhausted.']);
        }
        (clone $sequences)->where('scope', $scope)->update(['last_number' => $number]);

        $defaultPattern = strtoupper(str_replace('_', '-', $document::TYPE)).'-B{BRANCH}-{SEQ:6}';
        $formatted = strtr($settings[$document::TYPE.'_invoice_pattern'] ?? $defaultPattern, [
            '{YYYY}' => $document->document_date->format('Y'), '{MM}' => $document->document_date->format('m'),
            '{DD}' => $document->document_date->format('d'), '{BRANCH}' => (string) $document->branch_id,
            '{PRODUCT}' => $document->product_type === 'medicine' ? 'MED' : 'OPT',
        ]);
        $formatted = preg_replace_callback('/\{SEQ(?::([1-9]|1[0-2]))?\}/',
            fn (array $match): string => str_pad((string) $number, (int) ($match[1] ?? 6), '0', STR_PAD_LEFT), $formatted);
        if (mb_strlen($formatted) > 80) {
            throw ValidationException::withMessages(['number' => 'The invoice number is too long. Shorten its pattern in Inventory Settings.']);
        }

        return $formatted;
    }
}
