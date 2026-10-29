<?php

namespace Modules\Hospital\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;
use Modules\Hospital\Models\Patient;

class PatientService
{
    public function __construct(private AccessManager $access) {}

    public function save(Account $actor, ?Patient $record, array $values, int $branchId): Patient
    {
        Gate::forUser($actor)->authorize($record ? 'update' : 'create', $record ?? Patient::class);
        if ($branchId < 1 || ($record !== null && $record->branch_id !== $branchId)) {
            throw new AuthorizationException('This patient does not belong to the selected branch.');
        }

        $tenant = Tenant::current();
        $subscription = $tenant === null ? null : Subscription::forTenant($tenant);
        if (! $subscription?->isUsable()) {
            throw new AuthorizationException('An active subscription is required to save patients.');
        }
        if (! $subscription->plan->allowsRoutePermission('hospital.view')
            || ! $subscription->plan->allowsRoutePermission($record ? 'patients.update' : 'patients.create')) {
            throw new AuthorizationException('Your plan does not include this action.');
        }
        if (! $this->access->isOwner($actor) && ! $actor->can('hospital.view')) {
            throw new AuthorizationException('Hospital access is required to save patients.');
        }

        try {
            return (new Patient)->writeTransaction($actor, function (Account $actor) use ($record, $values, $branchId): Patient {
                $patient = $record ? $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail() : new Patient;

                if (! $patient->exists) {
                    $patient->branch_id = $branchId;
                }

                Gate::forUser($actor)->authorize($patient->exists ? 'update' : 'create', $patient);

                if (! $patient->exists) {
                    $patient->patient_id = $this->nextPatientId($patient);
                }

                $patient->fill($values)->save();

                return $patient;
            });
        } catch (UniqueConstraintViolationException $exception) {
            if (str_contains($exception->errorInfo[2] ?? '', 'patients_patient_id_unique')) {
                throw ValidationException::withMessages(['record' => 'The generated Patient ID already exists. Choose a distinct Patient ID pattern in Hospital settings.']);
            }

            throw $exception;
        } catch (QueryException $exception) {
            if (($exception->errorInfo[0] ?? null) === '23000' && (int) ($exception->errorInfo[1] ?? 0) === 1452) {
                throw ValidationException::withMessages(['record' => 'The selected branch or address has changed. Reload the form and select it again.']);
            }

            throw $exception;
        }
    }

    private function nextPatientId(Patient $patient): string
    {
        $settings = Setting::values('hospital', $patient->getConnectionName());
        $branchNumbering = $settings['id_scope'] === 'branch';
        $scope = $branchNumbering ? 'branch:'.$patient->branch_id : 'tenant';
        $sequences = $patient->getConnection()->table('patient_sequences');
        $sequences->insertOrIgnore(['scope' => $scope, 'last_number' => 0]);
        $sequences->where('scope', $scope);
        $sequence = $sequences->lockForUpdate()->first();
        $number = (int) $sequence->last_number + 1;
        if ($number > 999999999999) {
            throw ValidationException::withMessages(['record' => 'The Patient ID numbering sequence is exhausted.']);
        }
        $sequences->update(['last_number' => $number]);

        $pattern = $settings[$branchNumbering ? 'patient_branch_id_pattern' : 'patient_id_pattern'];
        $date = Carbon::now(config('app.timezone'));
        $formatted = strtr($pattern, [
            '{BRANCH}' => (string) $patient->branch_id,
            '{YYYY}' => $date->format('Y'), '{MM}' => $date->format('m'), '{DD}' => $date->format('d'),
        ]);
        $formatted = preg_replace_callback('/\{SEQ(?::([1-9]|1[0-2]))?\}/', fn (array $match): string => str_pad((string) $number, (int) ($match[1] ?? 6), '0', STR_PAD_LEFT), $formatted);
        if (mb_strlen($formatted) > 40) {
            throw ValidationException::withMessages(['record' => 'The generated Patient ID is too long. Shorten its pattern in Hospital settings.']);
        }

        return $formatted;
    }
}
