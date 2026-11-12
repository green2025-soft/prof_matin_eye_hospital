<?php

namespace Modules\Hospital\Services;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Modules\Core\Helpers\Money;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Hospital\Models\Investigation;
use Modules\Hospital\Models\PatientInvestigation;

class PatientInvestigationService extends ClinicalEntryService
{
    public function save(Account $actor, ?PatientInvestigation $record, array $values, int $branchId): PatientInvestigation
    {
        return $this->write($actor, $record ?? new PatientInvestigation, $branchId, function (PatientInvestigation $entry) use ($values, $branchId): PatientInvestigation {
            $this->assertInvoiceEditable($entry);
            $settings = Setting::values('hospital', $entry->getConnectionName());
            $ids = $values['investigation_ids'];
            $eyes = $values['investigation_eyes'];
            if (count($ids) !== count($eyes) || array_diff(array_keys($eyes), $ids) !== []) {
                throw ValidationException::withMessages(['investigation_ids' => 'Each selected test must have its own eye type.']);
            }

            $tests = Investigation::whereKey($ids)
                ->orderBy('id')
                ->sharedLock()
                ->get(['id', 'single_rate', 'both_rate'])
                ->keyBy('id');
            if ($tests->count() !== count($ids)) {
                throw ValidationException::withMessages(['investigation_ids' => 'Select valid investigations from the catalog.']);
            }

            $previousItems = $entry->exists
                ? $entry->investigations()
                    ->get(['investigations.id'])
                    ->keyBy('id')
                : collect();
            $items = [];
            $amount = 0;
            foreach (array_values($ids) as $position => $id) {
                $eye = $eyes[$id];
                $test = $tests->get($id);
                $previousItem = $previousItems->get($id);
                $sameEye = $previousItem !== null && $previousItem->pivot->eye_type === $eye;
                $rate = $sameEye ? $previousItem->pivot->rate : ($eye === 'both' ? $test->both_rate : $test->single_rate);
                $amount += Money::minor($rate);
                $items[$id] = [
                    'rate' => $rate,
                    'eye_type' => $eye,
                    'position' => $position,
                ];
            }

            $this->assertInvoiceAmount($amount);
            $discount = $this->discountAmounts($values, $amount);
            $discountAmount = Money::minor($discount['discount_amount']);
            $extraPercent = Money::minor((string) $values['extra_discount_percent']);
            $extraDiscount = intdiv(($amount - $discountAmount) * $extraPercent + 5000, 10000);

            $entry->fill(Arr::only($values, $entry->getFillable()));
            $entry->fill($discount);
            $entry->fill($this->totals($amount, $discountAmount, Money::minor($values['paid_amount']), $extraDiscount));
            $entry->extra_discount_percent = Money::decimal($extraPercent);
            $entry->extra_discount_amount = Money::decimal($extraDiscount);
            $this->assignReference(
                $entry,
                isset($values['reference_id']) ? (int) $values['reference_id'] : null,
                $branchId,
                $settings['investigation_reference_enabled'],
                'investigation_percent',
                $settings['reference_investigation_discount_mode'],
                $settings['reference_investigation_due_mode'],
            );
            $this->assignInvoiceNumber($entry, 'investigation_invoice', $values['entry_date'], $settings);
            $entry->save();
            $entry->investigations()->sync($items);

            $this->queueInvoiceSms(
                $entry,
                'hospital.investigation', 'patient-investigation:'.$entry->getKey().':created',
                $settings['sms_investigation_enabled'], $settings['sms_investigation_template'],
                $entry->invoice_no,
            );

            return $entry;
        });
    }

    public function invoiceData(Account $actor, PatientInvestigation $entry): array
    {
        $this->authorize($actor, $entry, 'view');
        $state = $this->invoices->balance($entry);
        $data = $this->invoiceValues($actor, $entry, $state);
        $lines = $entry->investigations()
            ->get(['investigations.id', 'investigations.name', 'investigations.room_no'])
            ->map(fn (Investigation $investigation): array => [
                'name' => $investigation->name,
                'room' => $investigation->room_no,
                'eye' => eyeTypes()[$investigation->pivot->eye_type] ?? ucfirst($investigation->pivot->eye_type),
                'amount' => $investigation->pivot->rate,
            ])
            ->all();

        return $data + [
            'pageTitle' => 'Investigation invoice',
            'documentTitle' => 'Investigation invoice',
            'invoiceNumber' => filled($entry->invoice_no) ? $entry->invoice_no : '#'.$entry->getKey(),
            'originalInvoiceNumber' => null,
            'entryDate' => $entry->entry_date?->format('d M Y') ?? '—',
            'entryDateLabel' => 'Date / Time',
            'entryTime' => $entry->created_at?->copy()->timezone(config('app.timezone'))->format('h:i A') ?? '—',
            'deliveryDate' => $entry->delivery_date?->format('d M Y'),
            'lines' => $lines,
            'extraDiscount' => $entry->extra_discount_amount,
            'remarks' => $entry->getAttribute('remarks'),
            'refundCause' => null,
            'amountWords' => Money::words($state['remaining_amount']),
            'status' => $this->invoiceStatus($state),
            'backUrl' => route('hospital.patient-investigations.index'),
        ];
    }
}
