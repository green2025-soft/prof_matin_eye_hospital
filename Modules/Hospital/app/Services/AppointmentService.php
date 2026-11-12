<?php

namespace Modules\Hospital\Services;

use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Hospital\Models\Appointment;

class AppointmentService extends ClinicalEntryService
{
    public function save(Account $actor, ?Appointment $record, array $values, int $branchId): Appointment
    {
        try {
            return $this->write($actor, $record ?? new Appointment, $branchId, function (Appointment $entry) use ($values): Appointment {
                $entry->fill($values);
                $needsNumbers = ! $entry->exists || ($entry->serial_no !== null && $entry->isDirty(['doctor_id', 'appointment_date']));
                if ($needsNumbers) {
                    $settings = Setting::values('hospital', $entry->getConnectionName());
                    $this->assignSerial($entry, 'appointment_serial', 'appointment_date', $values['appointment_date'], $settings);
                    $this->assignInvoiceNumber($entry, 'appointment_invoice', $values['appointment_date'], $settings);
                }
                $entry->save();

                return $entry;
            });
        } catch (QueryException $exception) {
            if ((int) ($exception->errorInfo[1] ?? 0) === 1451) {
                throw ValidationException::withMessages(['record' => 'This appointment is linked to a consultation. Its doctor and patient must remain the same.']);
            }

            throw $exception;
        }
    }
}
