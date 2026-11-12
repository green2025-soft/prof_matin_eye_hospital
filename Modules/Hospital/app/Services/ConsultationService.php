<?php

namespace Modules\Hospital\Services;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Hospital\Models\Consultation;
use Modules\Hospital\Models\Doctor;

class ConsultationService extends ClinicalEntryService
{
    public function save(Account $actor, ?Consultation $record, array $values, int $branchId): Consultation
    {
        try {
            return $this->write($actor, $record ?? new Consultation, $branchId, function (Consultation $entry) use ($values, $branchId): Consultation {
                $this->assertInvoiceEditable($entry);
                $doctor = Doctor::where('branch_id', $branchId)
                    ->whereKey($values['doctor_id'])
                    ->sharedLock()
                    ->firstOrFail(['id', 'consultation_fee', 'follow_up_fee']);
                $settings = Setting::values('hospital', $entry->getConnectionName());
                $appointmentId = $values['appointment_id'] ?? null;

                $sameFee = $entry->exists && $entry->doctor_id === $doctor->getKey() && $entry->patient_type === $values['patient_type'];
                $fee = $values['patient_type'] === 'follow_up' ? $doctor->follow_up_fee : $doctor->consultation_fee;
                $amount = Money::minor($sameFee ? $entry->amount : $fee);
                $this->assertInvoiceAmount($amount);
                $discount = $this->discountAmounts($values, $amount);

                $entry->fill(Arr::only($values, $entry->getFillable()));
                $entry->appointment_id = $appointmentId;
                $entry->fill($discount);
                $entry->fill($this->totals($amount, Money::minor($discount['discount_amount']), Money::minor($values['paid_amount'])));
                $this->assignReference(
                    $entry,
                    isset($values['reference_id']) ? (int) $values['reference_id'] : null,
                    $branchId,
                    $settings['consultation_reference_enabled'],
                    'consultation_percent',
                    $settings['reference_consultation_discount_mode'],
                    $settings['reference_consultation_due_mode'],
                );

                if ($settings['consultation_serial_enabled'] || $entry->serial_no !== null) {
                    $this->assignSerial($entry, 'consultation_serial', 'entry_date', $values['entry_date'], $settings);
                }
                if ($settings['consultation_invoice_enabled']) {
                    $this->assignInvoiceNumber($entry, 'consultation_invoice', $values['entry_date'], $settings);
                }
                $entry->save();

                $this->queueInvoiceSms(
                    $entry,
                    'hospital.consultation', 'consultation:'.$entry->getKey().':created',
                    $settings['sms_consultation_enabled'], $settings['sms_consultation_template'],
                    $entry->invoice_no ?: ($entry->serial_no ?: '#'.$entry->getKey()),
                );

                return $entry;
            });
        } catch (UniqueConstraintViolationException $exception) {
            if (str_contains($exception->errorInfo[2] ?? '', 'consultations_appointment_id_unique')) {
                throw ValidationException::withMessages(['appointment_id' => 'A consultation already exists for this appointment.']);
            }

            throw $exception;
        }
    }

    public function invoiceData(Account $actor, Consultation $entry): array
    {
        $this->authorize($actor, $entry, 'view');
        $state = $this->invoices->balance($entry);
        $data = $this->invoiceValues($actor, $entry, $state);

        return $data + [
            'pageTitle' => 'Consultation invoice',
            'documentTitle' => 'Consultation invoice',
            'invoiceNumber' => filled($entry->invoice_no) ? $entry->invoice_no : '#'.$entry->getKey(),
            'originalInvoiceNumber' => null,
            'entryDate' => $entry->entry_date?->format('d M Y') ?? '—',
            'entryDateLabel' => 'Date / Time',
            'entryTime' => $entry->created_at?->copy()->timezone(config('app.timezone'))->format('h:i A') ?? '—',
            'lines' => [[
                'name' => 'Consultation — '.(patientTypes()[$entry->patient_type] ?? $entry->patient_type),
                'room' => null,
                'eye' => null,
                'amount' => $entry->amount,
            ]],
            'remarks' => $entry->getAttribute('remarks'),
            'refundCause' => null,
            'amountWords' => Money::words($state['remaining_amount']),
            'status' => $this->invoiceStatus($state),
            'backUrl' => route('hospital.consultations.index'),
        ];
    }
}
