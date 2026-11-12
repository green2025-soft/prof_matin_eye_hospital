<?php

namespace Modules\Hospital\Services;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Services\SmsService;
use Modules\Hospital\Models\BaseModel;
use Modules\Hospital\Models\ClinicalSequence;
use Modules\Hospital\Models\Consultation;
use Modules\Hospital\Models\PatientInvestigation;
use Modules\Hospital\Models\PatientOperation;
use Modules\Hospital\Models\Reference;
use Modules\TenantCore\Models\Branch;

abstract class ClinicalEntryService
{
    public function __construct(
        protected SmsService $sms,
        protected ClinicalInvoiceService $invoices,
    ) {}

    protected function write(Account $actor, BaseModel $record, int $branchId, Closure $save): BaseModel
    {
        $action = $record->exists ? 'update' : 'create';
        $this->authorize($actor, $record, $action);

        try {
            return $actor->withAccessLock(function (Account $freshActor) use ($record, $branchId, $action, $save): BaseModel {
                $entry = $record->exists ? $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail()
                    : $record->newInstance();
                if (! $entry->exists) {
                    $entry->branch_id = $branchId;
                }
                $this->authorize($freshActor, $entry, $action);

                return $save($entry);
            });
        } catch (QueryException $exception) {
            $code = (int) ($exception->errorInfo[1] ?? 0);
            $constraint = $exception->errorInfo[2] ?? '';
            if ($code === 1062 && (str_contains($constraint, '_invoice_no_unique') || str_contains($constraint, '_serial_scope_no_unique')
                || str_contains($constraint, '_queue_serial_unique'))) {
                throw ValidationException::withMessages(['record' => 'The generated number already exists. Choose a distinct pattern in Hospital settings.']);
            }
            if ($code === 1452) {
                throw ValidationException::withMessages(['record' => 'A selected record has changed. Reload the form.']);
            }

            throw $exception;
        }
    }

    public function delete(Account $actor, Model $record): void
    {
        $this->authorize($actor, $record, 'delete');
        $actor->withAccessLock(function (Account $freshActor) use ($record): void {
            $entry = $record->newQuery()->whereKey($record->getKey())
                ->lockForUpdate()->firstOrFail();
            $this->authorize($freshActor, $entry, 'delete');
            $entry->delete();
        });
    }

    public function listInvoiceBalances(Account $actor, Consultation|PatientInvestigation|PatientOperation $source, array $invoiceIds, int $branchId): array
    {
        $this->authorize($actor, $source, 'view');

        return $this->invoices->listBalances($source, $invoiceIds, $branchId);
    }

    // Every invoice uses these source facts; the owner prepares its own document fields.
    protected function invoiceValues(Account $actor, Consultation|PatientInvestigation|PatientOperation $source, array $state): array
    {
        $patient = $source->patient()->first(['id', 'patient_id', 'name', 'age', 'gender', 'mobile_no']);
        $doctor = $source->doctor()->where('branch_id', $source->branch_id)->first(['id', 'name']);
        $branch = Branch::find($source->branch_id, ['id', 'name', 'address', 'mobile_no', 'telephone_no', 'email', 'logo_path']);
        $company = Setting::values('company', $source->getConnectionName());
        $branding = Setting::values('branding', $source->getConnectionName());
        $branchPhone = implode(' / ', array_filter([$branch?->mobile_no, $branch?->telephone_no], fn ($value): bool => filled($value)));
        $logoPath = filled($branch?->logo_path) ? $branch->logo_path : $branding['logo_path'];

        return [
            'sourceService' => $source::SERVICE,
            'sourceServiceLabel' => ucfirst($source::SERVICE),
            'printedAt' => now(config('app.timezone'))->format('d M Y h:i A'),
            'printedBy' => $actor->name,
            'company' => [
                'name' => filled($branch?->name) ? $branch->name : ($company['name'] ?: $branding['app_name']),
                'address' => filled($branch?->address) ? $branch->address : $company['address'],
                'phone' => $branchPhone !== '' ? $branchPhone : $company['phone'],
                'email' => filled($branch?->email) ? $branch->email : $company['email'],
                'tax_id' => $company['tax_id'],
            ],
            'logoUrl' => filled($logoPath) ? Storage::disk('public')->url($logoPath) : null,
            'patient' => $patient,
            'patientGenderLabel' => genders()[$patient?->gender] ?? ($patient?->gender ?? '—'),
            'doctor' => $doctor,
            'amount' => $source->amount,
            'discount' => $source->discount_amount,
            'payable' => $source->payable_amount,
            'paid' => $source->paid_amount,
            'due' => $source->due_amount,
            'collectedAmount' => Money::decimal($state['collected']),
            'creditAmount' => Money::decimal($state['credit']),
            'cashRefunded' => Money::decimal($state['cash_refunded']),
            'dueAdjusted' => Money::decimal($state['due_adjusted']),
            'remainingPayable' => Money::decimal($state['remaining_amount']),
            'remainingPaid' => Money::decimal($state['remaining_paid']),
            'remainingDue' => Money::decimal($state['remaining_due']),
        ];
    }

    protected function invoiceStatus(array $state): string
    {
        return match (true) {
            $state['credit'] > 0 && $state['remaining_amount'] === 0 => 'REFUNDED',
            $state['credit'] > 0 => 'PARTIALLY REFUNDED',
            $state['remaining_due'] > 0 => 'DUE',
            default => 'PAID',
        };
    }

    protected function assertInvoiceEditable(Consultation|PatientInvestigation|PatientOperation $entry): void
    {
        if (! $entry->exists) {
            return;
        }
        if ($entry->reference_commission_paid_at !== null) {
            throw ValidationException::withMessages(['record' => 'Mark this Reference commission Unpaid before changing the invoice.']);
        }
        if ($entry->{$entry::REFUND_RELATION}()->exists() || $entry->dueCollections()->exists()) {
            throw ValidationException::withMessages(['record' => 'This invoice has a refund or due collection and cannot be changed.']);
        }
    }

    protected function queueInvoiceSms(Consultation|PatientInvestigation|PatientOperation $entry, string $feature, string $eventKey, bool $enabled, string $template, ?string $invoiceNumber): void
    {
        if (! $entry->wasRecentlyCreated || ! $enabled) {
            return;
        }

        $patient = $entry->patient()
            ->firstOrFail([
                'id', 'patient_id', 'name', 'mobile_no', 'address',
                'age', 'age_months', 'age_days', 'date_of_birth', 'father_husband_name',
            ]);
        $this->sms->queue($entry, $feature, $eventKey, $patient->mobile_no, true, $template, [
            ...$patient->smsValues(),
            'name' => $patient->name,
            'invoice' => $invoiceNumber,
            'date' => $entry->entry_date->format('d-m-Y'),
            'total' => $entry->payable_amount,
            'paid' => $entry->paid_amount,
            'due' => $entry->due_amount,
            'amount' => $entry->payable_amount,
        ]);
    }

    protected function assignReference(Consultation|PatientInvestigation|PatientOperation $entry, ?int $referenceId, int $branchId, bool $enabled, string $percentageColumn, string $discountMode, string $dueMode): void
    {
        if (! $entry->exists) {
            $entry->reference_discount_mode = $discountMode;
            $entry->reference_due_mode = $dueMode;
        }

        if (! $enabled) {
            return;
        }

        $reference = null;
        if ($referenceId !== null) {
            $reference = Reference::where('branch_id', $branchId)
                ->whereKey($referenceId)->sharedLock()->firstOrFail(['id', $percentageColumn]);
        }

        $previousReferenceId = $entry->reference_id;
        $entry->reference_id = $reference?->getKey();
        if (($entry->reference_discount_mode === null && $entry->reference_due_mode === null) || $reference === null) {
            $entry->reference_commission_percent = null;

            return;
        }
        if (! $entry->exists || $previousReferenceId !== $reference->getKey()) {
            $entry->reference_commission_percent = $reference->getAttribute($percentageColumn);
        }
    }

    protected function assignSerial(BaseModel $entry, string $key, string $dateField, string $date, array $settings): void
    {
        // Existing unnumbered entries stay unnumbered; only a changed series gets a new serial.
        if ($entry->exists && ($entry->serial_no === null || ! $entry->isDirty(['doctor_id', $dateField]))) {
            return;
        }

        $scope = $this->numberScope($entry, $key, $date, $settings);
        if ($entry->exists && $entry->getOriginal('serial_scope') === $scope) {
            return;
        }

        $entry->serial_scope = $scope;
        $entry->serial_no = $this->nextNumber($entry, $scope, $date, $settings[$key.'_pattern']);
    }

    protected function assignInvoiceNumber(BaseModel $entry, string $key, string $date, array $settings, ?int $doctorId = null): void
    {
        if ($entry->exists) {
            return;
        }

        $scope = $this->numberScope($entry, $key, $date, $settings);
        $entry->invoice_no = $this->nextNumber($entry, $scope, $date, $settings[$key.'_pattern'], $doctorId);
    }

    protected function numberScope(BaseModel $entry, string $key, string $date, array $settings): string
    {
        $scope = match ($settings[$key.'_scope']) {
            'branch' => 'branch:'.$entry->branch_id,
            'doctor' => 'doctor:'.$entry->branch_id.':'.$entry->doctor_id,
            default => 'tenant',
        };
        $date = Carbon::parse($date);
        $period = match ($settings[$key.'_reset']) {
            'year' => $date->format('Y'),
            'month' => $date->format('Ym'),
            'day' => $date->format('Ymd'),
            default => 'continuous',
        };

        return $key.':'.$scope.':'.$period;
    }

    protected function nextNumber(BaseModel $entry, string $scope, string $date, string $pattern, ?int $doctorId = null): string
    {
        ClinicalSequence::insertOrIgnore(['scope' => $scope, 'last_number' => 0]);
        $sequence = ClinicalSequence::whereKey($scope)->lockForUpdate()->firstOrFail();
        $number = (int) $sequence->last_number + 1;
        if ($number > 999999999999) {
            throw ValidationException::withMessages(['record' => 'This numbering sequence is exhausted. Change its scope or reset period in Hospital settings.']);
        }
        $sequence->last_number = $number;
        $sequence->saveQuietly();

        $date = Carbon::parse($date);
        $formatted = strtr($pattern, [
            '{YYYY}' => $date->format('Y'),
            '{MM}' => $date->format('m'),
            '{DD}' => $date->format('d'),
            '{BRANCH}' => (string) $entry->branch_id,
            '{DOCTOR}' => (string) ($doctorId ?? $entry->getAttribute('doctor_id')),
        ]);
        $formatted = preg_replace_callback('/\{SEQ(?::([1-9]|1[0-2]))?\}/', fn (array $match): string => str_pad((string) $number, (int) ($match[1] ?? 6), '0', STR_PAD_LEFT), $formatted);
        if (mb_strlen($formatted) > 120) {
            throw ValidationException::withMessages(['record' => 'The generated number is too long. Shorten its pattern in Hospital settings.']);
        }

        return $formatted;
    }

    protected function authorize(Account $actor, BaseModel $entry, string $action): void
    {
        Gate::forUser($actor)->authorize($action, $entry->exists ? $entry : $entry::class);
    }

    protected function assertInvoiceAmount(int $amount): void
    {
        if ($amount > 999999999999) {
            throw ValidationException::withMessages(['amount' => 'The combined amount is too large.']);
        }
    }

    protected function discountAmounts(array $values, int $amount): array
    {
        $mode = $values['discount_mode'];
        $basisPoints = Money::minor((string) $values['discount_percent']);
        $discount = $mode === 'percent'
            ? intdiv($amount * $basisPoints + 5000, 10000)
            : Money::minor($values['discount_amount']);
        if ($mode === 'amount') {
            $basisPoints = $amount === 0 ? 0 : intdiv($discount * 10000 + intdiv($amount, 2), $amount);
        }

        return [
            'discount_mode' => $mode,
            'discount_percent' => Money::decimal($basisPoints),
            'discount_amount' => Money::decimal($discount),
        ];
    }

    protected function totals(int $amount, int $discount, int $paid, int $extraDiscount = 0): array
    {
        if ($discount > $amount) {
            throw ValidationException::withMessages(['discount_amount' => 'Discount cannot exceed the amount.']);
        }

        $payable = $amount - $discount - $extraDiscount;
        if ($paid > $payable) {
            throw ValidationException::withMessages(['paid_amount' => 'Payment cannot exceed the payable amount.']);
        }

        return [
            'amount' => Money::decimal($amount),
            'discount_amount' => Money::decimal($discount),
            'paid_amount' => Money::decimal($paid),
            'payable_amount' => Money::decimal($payable),
            'due_amount' => Money::decimal($payable - $paid),
        ];
    }
}
