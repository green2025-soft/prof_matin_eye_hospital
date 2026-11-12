<?php

namespace Modules\Prescription\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Hospital\Models\Doctor;
use Modules\Hospital\Models\Patient;
use Modules\Pharmacy\Models\Product;
use Modules\Prescription\Models\Prescription;

class PrescriptionService
{
    public function save(Account $actor, ?Prescription $record, array $values): Prescription
    {
        return $actor->withAccessLock(function (Account $freshActor) use ($record, $values): Prescription {
            $prescription = $record === null ? new Prescription : Prescription::whereKey($record->getKey())->lockForUpdate()->firstOrFail();

            Gate::forUser($freshActor)->authorize(
                $prescription->exists ? 'update' : 'create',
                $prescription->exists ? $prescription : Prescription::class,
            );

            $branchId = (int) request()->attributes->get('branch_id', 0);
            $patient = Patient::whereKey($values['patient_id'])
                ->where('branch_id', $branchId)->lockForUpdate()->first();
            $doctor = Doctor::whereKey($values['doctor_id'])
                ->where('branch_id', $branchId)->lockForUpdate()->first();

            if ($patient === null || $doctor === null) {
                throw ValidationException::withMessages(['record' => 'Choose a patient and doctor from the selected branch.']);
            }

            $medicines = $this->medicineRows($values['medicines'] ?? []);
            $values['section_details'] = array_replace(
                $prescription->section_details ?? [],
                $values['section_details'] ?? [],
            );

            if (($values['follow_up_interval'] ?? null) !== null) {
                $followUpDate = Carbon::createFromFormat('!Y-m-d', $values['prescription_date'], config('app.timezone'));
                $interval = (int) $values['follow_up_interval'];
                $values['follow_up_date'] = $values['follow_up_interval_unit'] === 'months'
                    ? $followUpDate->addMonthsNoOverflow($interval)->toDateString()
                    : $followUpDate->addDays($interval)->toDateString();
            }

            $prescription->fill(Arr::except($values, ['medicines']))->save();
            $prescription->medicines()->delete();
            $prescription->medicines()->createMany($medicines);

            return $prescription;
        });
    }

    public function delete(Account $actor, Prescription $record): void
    {
        $actor->withAccessLock(function (Account $freshActor) use ($record): void {
            $prescription = Prescription::whereKey($record->getKey())->lockForUpdate()->firstOrFail();
            Gate::forUser($freshActor)->authorize('delete', $prescription);
            $prescription->delete();
        });
    }

    private function medicineRows(array $inputs): array
    {
        $productIds = array_values(array_unique(array_filter(array_column($inputs, 'product_id'))));
        $products = Product::where('type', 'medicine')->whereIn('id', $productIds)
            ->orderBy('id')->lockForUpdate()->get(['id', 'name'])->keyBy('id');
        $rows = [];

        foreach ($inputs as $index => $input) {
            $productId = $input['product_id'] ?? null;
            $product = $products->get($productId);

            if ($productId !== null && $product === null) {
                throw ValidationException::withMessages(["medicines.$index.product_id" => 'Choose an available medicine from the catalog.']);
            }

            $rows[] = [
                'product_id' => $product?->id,
                'medicine_name' => $product?->name ?? trim($input['medicine_name']),
                'dosage_type' => $input['dosage_type'] ?? 'other',
                'dose' => $input['dose'] ?? null,
                'frequency' => $input['frequency'] ?? null,
                'duration' => $input['duration'] ?? null,
                'instructions' => $input['instructions'] ?? null,
                'ordering' => count($rows),
            ];
        }

        return $rows;
    }
}
