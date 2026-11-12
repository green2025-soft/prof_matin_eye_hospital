<?php

namespace Modules\Pharmacy\Services;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Pharmacy\Models\PharmacySequence;
use Modules\Pharmacy\Models\StockEntry;
use Modules\TenantCore\Models\Branch;

abstract class StockEntryService
{
    protected string $model;

    public function __construct(
        protected PharmacyAccess $pharmacy,
        protected PharmacyCustomers $customers,
        protected StockService $stock,
        protected ItemService $items,
    ) {}

    public function save(Account $actor, ?StockEntry $record, array $values): StockEntry
    {
        try {
            return $actor->withAccessLock(function (Account $freshActor) use ($record, $values): StockEntry {
                $entry = $this->lockedEntry($record);
                $created = ! $entry->exists;
                $action = $created ? 'create' : 'update';
                $this->pharmacy->authorize($freshActor, $entry->crudPermission().'.'.$action);
                $previousLines = $this->prepareForWrite($freshActor, $entry, $values);
                $settings = Setting::values('pharmacy', $entry->getConnectionName());

                $this->fillHeader($entry, $values);
                $lines = $this->prepareEntry($freshActor, $entry, $values, $settings, $previousLines);
                if ($created) {
                    $entry->number = $this->nextNumber($entry, $settings);
                }

                $entry->save();
                $entry->lines()->forceCreateMany($lines);
                $entry->cost = $this->postStock($entry);
                $entry->posted_at = now(config('app.timezone'));
                $entry->save();

                // Resource creation hooks share this transaction after stock and totals are final.
                if ($created) {
                    $this->afterCreated($freshActor, $entry, $settings);
                }

                return $entry;
            });
        } catch (UniqueConstraintViolationException $exception) {
            $model = $this->model;
            if (str_contains($exception->errorInfo[2] ?? '', (new $model)->getTable().'_number_unique')) {
                throw ValidationException::withMessages(['number' => 'This invoice number already exists. Change its pattern in Pharmacy Settings.']);
            }

            throw $exception;
        }
    }

    public function reverse(Account $actor, StockEntry $record): void
    {
        $actor->withAccessLock(function (Account $freshActor) use ($record): void {
            $entry = $this->lockedEntry($record);
            $this->pharmacy->authorize($freshActor, $entry->crudPermission().'.void');
            if ($entry->branch_id !== (int) request()->attributes->get('branch_id') || $entry->voided_at !== null) {
                throw ValidationException::withMessages(['record' => 'Only the source branch can void an active entry.']);
            }

            $this->reverseStock($entry);
            $this->voidHeader($entry);
            $entry->save();
        });
    }

    public function delete(Account $actor, StockEntry $record): void
    {
        $actor->withAccessLock(function (Account $freshActor) use ($record): void {
            $entry = $this->lockedEntry($record);
            $this->pharmacy->authorize($freshActor, $entry->crudPermission().'.delete');
            Gate::forUser($freshActor)->authorize('delete', $entry);
            if ($entry->voided_at === null) {
                $this->reverseStock($entry);
                $this->voidHeader($entry);
            }
            $entry->save();
            $entry->delete();
        });
    }

    public function invoiceData(Account $actor, StockEntry $record): array
    {
        $model = $this->model;
        $entry = $model::findOrFail($record->id);
        Gate::forUser($actor)->authorize('view', $entry);
        $entry->load([
            'lines.variant.product:id,name,type',
            'lines.productUnit.unit:id,name,symbol',
        ]);
        $company = Setting::values('company', $entry->getConnectionName());
        $branding = Setting::values('branding', $entry->getConnectionName());
        $branch = Branch::find($entry->branch_id);
        $phone = implode(' / ', array_filter([$branch?->mobile_no, $branch?->telephone_no], fn ($value): bool => filled($value)));
        $logoPath = $branch?->logo_path ?: $branding['logo_path'];
        $companyName = $company['name'] ?: $branding['app_name'];

        return [
            'entry' => $entry,
            'company' => [
                'name' => $companyName,
                'branch_name' => $branch !== null && ! $branch->is_default && $branch->name !== $companyName ? $branch->name : '',
                'address' => $branch?->address ?: $company['address'],
                'phone' => $phone ?: $company['phone'],
                'email' => $branch?->email ?: $company['email'],
                'tax_id' => $company['tax_id'],
            ],
            'logoUrl' => filled($logoPath) ? Storage::disk('public')->url($logoPath) : null,
            'printedBy' => $actor->name,
            'printedAt' => now(config('app.timezone'))->format('d M Y h:i A'),
            'canVoid' => Gate::forUser($actor)->allows('void', $entry),
        ];
    }

    protected function lockedEntry(?StockEntry $record): StockEntry
    {
        $model = $this->model;
        if ($record === null) {
            return new $model;
        }
        if (! $record instanceof $model) {
            throw ValidationException::withMessages(['record' => 'Choose an entry belonging to this resource.']);
        }

        return $model::whereKey($record->id)
            ->lockForUpdate()
            ->firstOrFail();
    }

    protected function prepareForWrite(Account $actor, StockEntry $entry, array $values): Collection
    {
        if ($entry->exists) {
            throw ValidationException::withMessages(['record' => 'Posted entries cannot be edited.']);
        }

        return collect();
    }

    abstract protected function prepareEntry(Account $actor, StockEntry $entry, array $values, array $settings, Collection $previousLines): array;

    abstract protected function postStock(StockEntry $entry): int;

    protected function afterCreated(Account $actor, StockEntry $entry, array $settings): void {}

    protected function reverseStock(StockEntry $entry): void
    {
        $this->stock->reverse($entry);
    }

    protected function voidHeader(StockEntry $entry): void
    {
        $entry->voided_at = now(config('app.timezone'));
    }

    private function fillHeader(StockEntry $entry, array $values): void
    {
        $entry->forceFill([
            'branch_id' => (int) request()->attributes->get('branch_id'),
            'product_type' => $values['product_type'],
            'entry_date' => $values['entry_date'],
            'notes' => $values['notes'] ?? null,
            'posted_at' => null,
            'stock_reversed_at' => null,
            'voided_at' => null,
            'cost' => 0,
        ]);
        if (! in_array($entry->product_type, ['medicine', 'optic'], true)) {
            throw ValidationException::withMessages(['product_type' => 'Choose Medicine or Optic.']);
        }
        if ($entry->entry_date->gt(now(config('app.timezone'))->startOfDay())) {
            throw ValidationException::withMessages(['entry_date' => 'The date cannot be in the future.']);
        }
    }

    protected function validatePaymentType(StockEntry $entry, int $cashMinor): void
    {
        if ($cashMinor > 0 && $entry->payment_type_id === null) {
            throw ValidationException::withMessages(['payment_type_id' => 'Choose a payment type for the cash payment or refund.']);
        }
        if ($entry->payment_type_id !== null && ! $entry->paymentType()->exists()) {
            throw ValidationException::withMessages(['payment_type_id' => 'Choose a valid payment type.']);
        }
    }

    private function nextNumber(StockEntry $entry, array $settings): string
    {
        $scope = $entry::TYPE.':'.$entry->branch_id;
        PharmacySequence::insertOrIgnore([
            'scope' => $scope,
            'last_number' => 0,
        ]);
        $sequence = PharmacySequence::whereKey($scope)
            ->lockForUpdate()
            ->firstOrFail();
        $number = (int) $sequence->last_number + 1;
        if ($number > 999999999999) {
            throw ValidationException::withMessages(['record' => 'The transaction numbering sequence is exhausted.']);
        }
        $sequence->last_number = $number;
        $sequence->save();

        $defaultPattern = strtoupper(str_replace('_', '-', $entry::TYPE)).'-B{BRANCH}-{SEQ:6}';
        $formatted = strtr($settings[$entry::TYPE.'_invoice_pattern'] ?? $defaultPattern, [
            '{YYYY}' => $entry->entry_date->format('Y'),
            '{MM}' => $entry->entry_date->format('m'),
            '{DD}' => $entry->entry_date->format('d'),
            '{BRANCH}' => (string) $entry->branch_id,
            '{PRODUCT}' => $entry->product_type === 'medicine' ? 'MED' : 'OPT',
        ]);
        $formatted = preg_replace_callback('/\{SEQ(?::([1-9]|1[0-2]))?\}/',
            fn (array $match): string => str_pad((string) $number, (int) ($match[1] ?? 6), '0', STR_PAD_LEFT), $formatted);
        if (mb_strlen($formatted) > 80) {
            throw ValidationException::withMessages(['number' => 'The invoice number is too long. Shorten its pattern in Pharmacy Settings.']);
        }

        return $formatted;
    }
}
