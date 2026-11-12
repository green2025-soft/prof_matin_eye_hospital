<?php

namespace Modules\Hospital\Services;

use Illuminate\Support\Arr;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Hospital\Models\Operation;
use Modules\Hospital\Models\PatientOperation;

class PatientOperationService extends ClinicalEntryService
{
    public function save(Account $actor, ?PatientOperation $record, array $values, int $branchId): PatientOperation
    {
        return $this->write($actor, $record ?? new PatientOperation, $branchId, function (PatientOperation $entry, Account $freshActor) use ($values, $branchId): PatientOperation {
            $this->assertInvoiceEditable($entry);
            $settings = Setting::values('hospital', $entry->getConnectionName());
            $operation = Operation::whereKey($values['operation_id'])
                ->sharedLock()
                ->firstOrFail(['id', 'single_rate', 'both_rate']);
            $sameRate = $entry->exists && $entry->operation_id === $operation->getKey() && $entry->eye_type === $values['eye_type'];
            $rate = $values['eye_type'] === 'both' ? $operation->both_rate : $operation->single_rate;
            $amount = Money::minor($sameRate ? $entry->amount : $rate);
            $this->assertInvoiceAmount($amount);

            $entry->fill(Arr::only($values, $entry->getFillable()));
            $entry->fill($this->totals($amount, Money::minor($values['discount_amount']), Money::minor($values['paid_amount'])));
            $this->assignReference(
                $entry,
                isset($values['reference_id']) ? (int) $values['reference_id'] : null,
                $branchId,
                $settings['operation_reference_enabled'],
                'operation_percent',
                $settings['reference_operation_discount_mode'],
                $settings['reference_operation_due_mode'],
            );
            $this->assignInvoiceNumber($entry, 'operation_invoice', $values['entry_date'], $settings);
            $entry->save();

            $this->journals->sync(
                source: $entry,
                feature: 'hospital.operation',
                entryDate: $entry->entry_date->toDateString(),
                branchId: $entry->branch_id,
                amounts: [
                    'settlement' => Money::minor($entry->paid_amount),
                    'receivable' => Money::minor($entry->due_amount),
                    'discount' => Money::minor($entry->discount_amount),
                    'revenue' => $amount,
                ],
                created: $entry->wasRecentlyCreated,
                paymentTypeId: $entry->payment_type_id,
                actor: $freshActor,
                reference: $entry->invoice_no,
            );

            $this->queueInvoiceSms(
                $entry,
                'hospital.operation', 'patient-operation:'.$entry->getKey().':created',
                $settings['sms_operation_enabled'], $settings['sms_operation_template'],
                $entry->invoice_no,
            );

            return $entry;
        });
    }

    public function invoiceData(Account $actor, PatientOperation $entry): array
    {
        $this->authorize($actor, $entry, 'view');
        $state = $this->invoices->balance($entry);
        $data = $this->invoiceValues($actor, $entry, $state);

        return $data + [
            'pageTitle' => 'Operation invoice',
            'documentTitle' => 'Operation invoice',
            'invoiceNumber' => filled($entry->invoice_no) ? $entry->invoice_no : '#'.$entry->getKey(),
            'originalInvoiceNumber' => null,
            'entryDate' => $entry->entry_date?->format('d M Y') ?? '—',
            'entryDateLabel' => 'Date / Time',
            'entryTime' => $entry->created_at?->copy()->timezone(config('app.timezone'))->format('h:i A') ?? '—',
            'operationDate' => $entry->operation_date?->format('d M Y'),
            'operationPatientTypeLabel' => patientTypes()[$entry->patient_type] ?? $entry->patient_type,
            'consultantName' => $entry->consultant_id !== null
                ? $entry->consultant()->where('branch_id', $entry->branch_id)->value('name') : null,
            'lines' => [[
                'name' => $entry->operation()->value('name') ?? 'Operation',
                'room' => null,
                'eye' => eyeTypes()[$entry->eye_type] ?? ucfirst($entry->eye_type),
                'amount' => $entry->amount,
            ]],
            'remarks' => $entry->remarks,
            'refundCause' => null,
            'amountWords' => Money::words($state['remaining_amount']),
            'status' => $this->invoiceStatus($state),
            'backUrl' => route('hospital.patient-operations.index'),
        ];
    }
}
