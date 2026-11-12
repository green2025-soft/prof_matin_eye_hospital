<?php

namespace Modules\Accounting\Services;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Accounting\Contracts\JournalPosting;
use Modules\Accounting\Models\AccountHead;
use Modules\Accounting\Models\AccountingSequence;
use Modules\Accounting\Models\AccountModule;
use Modules\Accounting\Models\JournalEntry;
use Modules\Accounting\Models\PaymentAccount;
use Modules\Accounting\Models\VoucherType;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;

class JournalService implements JournalPosting
{
    public function save(Account $actor, ?Model $record, array $values): JournalEntry
    {
        return $actor->withAccessLock(function (Account $freshActor) use ($record, $values): JournalEntry {
            $entry = $record?->exists
                ? JournalEntry::whereKey($record->getKey())
                    ->where('branch_id', request()->attributes->get('branch_id'))
                    ->lockForUpdate()
                    ->firstOrFail()
                : new JournalEntry;
            Gate::forUser($freshActor)->authorize($entry->exists ? 'update' : 'create', $entry->exists ? $entry : JournalEntry::class);

            $voucherType = VoucherType::whereKey($values['voucher_type_id'])
                ->where('active', true)
                ->where('is_manual', true)
                ->firstOrFail();
            $entry->fill(Arr::only($values, ['voucher_type_id', 'entry_date', 'reference', 'narration']));

            if (! $entry->exists) {
                $entry->forceFill([
                    'branch_id' => request()->attributes->get('branch_id'),
                    'created_by' => $freshActor->getAuthIdentifier(),
                    'created_by_name' => $freshActor->name,
                ]);
            }

            $lines = [];

            foreach ($values['lines'] as $line) {
                $lines[] = [
                    'account_head_id' => $line['account_head_id'],
                    'debit' => Money::minor((string) ($line['debit'] ?? '0')),
                    'credit' => Money::minor((string) ($line['credit'] ?? '0')),
                    'remarks' => $line['remarks'] ?? null,
                    'ordering' => count($lines),
                ];
            }

            return $this->persist($entry, $voucherType, $lines);
        });
    }

    // Source owners supply their own financial facts; this operation only maps and stores them.
    public function sync(
        Model $source,
        string $feature,
        string $entryDate,
        int $branchId,
        array $amounts,
        bool $created,
        ?int $paymentTypeId = null,
        ?Account $actor = null,
        ?string $reference = null,
    ): void {
        $connection = $source->getConnectionName();
        $entry = JournalEntry::on($connection)
            ->whereMorphedTo('source', $source)
            ->where('feature', $feature)
            ->lockForUpdate()
            ->first();

        // Editing a legacy source must not create its first accounting entry.
        if (! $created && $entry === null) {
            return;
        }

        $mapping = AccountModule::on($connection)
            ->with(['components', 'voucherType'])
            ->where('key', $feature)
            ->first();

        if ($mapping === null) {
            throw ValidationException::withMessages(['record' => 'Accounting defaults are not installed. Complete Accounting setup before posting this transaction.']);
        }

        if (! $mapping->active) {
            $this->reverse($source, $feature);

            return;
        }

        if (! $mapping->voucherType->active) {
            throw ValidationException::withMessages(['record' => 'Choose an active voucher type in Accounting account mappings.']);
        }

        $unknown = array_diff(array_keys($amounts), $mapping->components->pluck('component')->all());

        if ($unknown !== []) {
            throw ValidationException::withMessages(['record' => 'Accounting components have changed. Update the default account mappings before posting.']);
        }

        $paymentAccount = null;

        if ($paymentTypeId !== null && ($amounts['settlement'] ?? 0) > 0) {
            $paymentAccount = PaymentAccount::on($connection)
                ->where('payment_type_id', $paymentTypeId)
                ->value('account_head_id');

            if ($paymentAccount === null) {
                throw ValidationException::withMessages(['payment_type_id' => 'Choose this payment type’s Cash/Bank ledger in Accounting → Payment Accounts.']);
            }
        }

        $lines = [];

        foreach ($mapping->components as $component) {
            $amount = $amounts[$component->component] ?? 0;

            if ($amount === 0) {
                continue;
            }

            $lines[] = [
                'account_head_id' => $component->use_payment_type && $paymentAccount !== null
                    ? $paymentAccount : $component->account_head_id,
                'debit' => $component->side === 'debit' ? $amount : 0,
                'credit' => $component->side === 'credit' ? $amount : 0,
                'remarks' => $component->name,
                'ordering' => count($lines),
            ];
        }

        $entry ??= new JournalEntry;
        $entry->setConnection($connection);
        $entry->source()->associate($source);
        $entry->forceFill([
            'feature' => $feature,
            'branch_id' => $branchId,
            'voucher_type_id' => $mapping->voucher_type_id,
            'entry_date' => $entryDate,
            'reference' => $reference,
            'narration' => $mapping->name,
            'created_by' => $actor?->getAuthIdentifier(),
            'created_by_name' => $actor?->name ?? 'Automatic posting',
            'voided_at' => null,
        ]);
        $this->persist($entry, $mapping->voucherType, $lines);
    }

    // Keep the saved account/amount snapshot and remove its effect from active balances.
    public function reverse(Model $source, ?string $feature = null): void
    {
        $entries = JournalEntry::on($source->getConnectionName())
            ->whereMorphedTo('source', $source)
            ->active();

        if ($feature !== null) {
            $entries->where('feature', $feature);
        }

        $entries->update(['voided_at' => now()]);
    }

    public function delete(Account $actor, Model $record): void
    {
        $actor->withAccessLock(function (Account $freshActor) use ($record): void {
            $entry = JournalEntry::whereKey($record->getKey())
                ->where('branch_id', request()->attributes->get('branch_id'))
                ->lockForUpdate()
                ->firstOrFail();
            Gate::forUser($freshActor)->authorize('delete', $entry);
            $entry->delete();
        });
    }

    private function persist(JournalEntry $entry, VoucherType $voucherType, array $lines): JournalEntry
    {
        $debit = BigInteger::sum(0, ...array_column($lines, 'debit'));
        $credit = BigInteger::sum(0, ...array_column($lines, 'credit'));

        if (! $debit->isEqualTo($credit) || $debit->isNegative() || $debit->isGreaterThan(PHP_INT_MAX)) {
            throw ValidationException::withMessages(['lines' => 'Debit and credit must be equal and within the supported amount.']);
        }

        $accountIds = array_unique(array_column($lines, 'account_head_id'));
        $accounts = AccountHead::on($entry->getConnectionName())
            ->postable()
            ->whereIn('id', $accountIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->get(['id']);

        if ($accounts->count() !== count($accountIds)) {
            throw ValidationException::withMessages(['lines' => 'Every account must be an active transaction ledger.']);
        }

        $created = ! $entry->exists;

        if ($created) {
            $entry->number = $this->nextNumber($entry, $voucherType);
        }

        $entry->total = $debit->toInt();
        $entry->save();

        if (! $created) {
            $entry->lines()->delete();
        }

        $entry->lines()->createMany($lines);

        return $entry;
    }

    private function nextNumber(JournalEntry $entry, VoucherType $voucherType): string
    {
        $connection = $entry->getConnectionName();
        $year = $entry->entry_date->format('Y');
        $name = 'journals:'.$year;
        AccountingSequence::on($connection)->firstOrCreate(['name' => $name], ['last_number' => 0]);
        $sequence = AccountingSequence::on($connection)
            ->whereKey($name)
            ->lockForUpdate()
            ->firstOrFail();
        $sequence->increment('last_number');

        return $voucherType->prefix.'-'.$year.'-'.str_pad((string) $sequence->last_number, 6, '0', STR_PAD_LEFT);
    }
}
