<?php

namespace Modules\Hospital\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;
use Modules\Hospital\Contracts\PharmacyCustomers;
use Modules\Hospital\Models\Patient;

class PatientService implements PharmacyCustomers
{
    public function __construct(private AccessManager $access) {}

    public function paginate(Account $actor, array $filters): LengthAwarePaginator
    {
        $this->authorizePharmacyCustomers($actor);
        $size = $filters['per_page'] ?? config('pagination.default');
        $all = $size === 'all';
        $size = $all ? 'all' : (int) $size;
        if (! in_array($size, config('pagination.options'), true)) {
            $size = config('pagination.default');
        }
        $records = $this->pharmacyCustomerQuery($filters['search'] ?? '', false)
            ->orderBy('name')->orderBy('id')
            ->paginate($all ? fn (int $total): int => max(1, $total) : $size, ['*'], 'page', $all ? 1 : max(1, (int) ($filters['page'] ?? 1)))
            ->appends(['search' => $filters['search'] ?? '', 'per_page' => $size]);
        $records->setCollection($records->getCollection()->map($this->customerOption(...)));

        return $records;
    }

    public function search(Account $actor, string $search, bool $customersOnly, int $page = 1, ?QueryBuilder $eligibleIds = null): array
    {
        $this->authorizePharmacyCustomers($actor);
        $query = $this->pharmacyCustomerQuery($search, $customersOnly);
        if ($eligibleIds !== null) {
            $query->whereIn('id', $eligibleIds);
        }
        $records = $query->orderBy('name')->orderBy('id')->simplePaginate(20, ['*'], 'page', max(1, $page));

        return ['results' => $records->getCollection()->map($this->customerOption(...))->all(), 'pagination' => ['more' => $records->hasMorePages()]];
    }

    private function pharmacyCustomerQuery(string $search, bool $customersOnly): Builder
    {
        $query = Patient::withoutGlobalScope('branch')->select(['id', 'patient_id', 'name', 'mobile_no', 'type']);

        if ($customersOnly) {
            $query->where('type', 'customer');
        }
        if ($search !== '') {
            $term = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], mb_substr($search, 0, 80));
            $query->where(function ($query) use ($term): void {
                $query->whereRaw("name LIKE ? ESCAPE '!'", [$term.'%'])->orWhereRaw("mobile_no LIKE ? ESCAPE '!'", [$term.'%'])
                    ->orWhereRaw("patient_id LIKE ? ESCAPE '!'", ['%'.$term.'%']);
            });
        }

        return $query;
    }

    public function findMany(Account $actor, array $ids): array
    {
        if ($ids === []) {
            return [];
        }
        $this->authorizePharmacyCustomers($actor);

        $customers = [];
        foreach (array_chunk(array_unique($ids), 500) as $chunk) {
            $records = Patient::withoutGlobalScope('branch')->whereIn('id', $chunk)
                ->get(['id', 'patient_id', 'name', 'mobile_no', 'type']);
            foreach ($records as $patient) {
                $customers[$patient->id] = $this->customerOption($patient);
            }
        }

        return $customers;
    }

    public function create(Account $actor, array $values, int $branchId): array
    {
        $values = Validator::make($values, [
            'name' => ['required', 'string', 'max:255'],
            'mobile_no' => ['required', 'string', 'max:30', 'regex:/\A(?=.*[0-9])[0-9+() .-]+\z/'],
            'age' => ['nullable', 'integer', 'between:0,150'],
            'gender' => ['nullable', Rule::in(array_keys(genders()))],
            'address' => ['nullable', 'string', 'max:2000'],
        ])->validate();

        try {
            return $this->access->transaction($actor, function (Account $freshActor) use ($values, $branchId): array {
                $this->authorizePharmacyCustomers($freshActor, creating: true);
                if ($branchId < 1 || $branchId !== (int) request()->attributes->get('branch_id')) {
                    throw new AuthorizationException('Select a branch before creating a customer.');
                }

                $patient = new Patient;
                $patient->branch_id = $branchId;
                $patient->type = 'customer';
                $patient->patient_id = $this->nextPatientId($patient);
                $patient->fill($values)->save();

                return $this->customerOption($patient);
            });
        } catch (UniqueConstraintViolationException $exception) {
            if (str_contains($exception->errorInfo[2] ?? '', 'patients_patient_id_unique')) {
                throw ValidationException::withMessages(['name' => 'The generated Patient ID already exists. Choose a distinct Patient ID pattern in Hospital settings.']);
            }

            throw $exception;
        }
    }

    private function authorizePharmacyCustomers(Account $actor, bool $creating = false): void
    {
        $this->access->assertActorContext($actor);
        $tenant = Tenant::current();
        $subscription = $tenant === null ? null : Subscription::forTenant($tenant);
        $plan = $subscription?->plan;
        if (! $subscription?->isUsable() || ! $plan->allowsRoutePermission('pharmacy.view')
            || (! $this->access->isOwner($actor) && ! $actor->can('pharmacy.view'))) {
            throw new AuthorizationException('Pharmacy access is required.');
        }

        $permissions = $creating ? ['pharmacy-sales.create', 'pharmacy-customers.create'] : [
            'pharmacy-sales.view', 'pharmacy-sales.create', 'pharmacy-sales.update',
            'pharmacy-sale-returns.view', 'pharmacy-sale-returns.create', 'pharmacy-sale-returns.update',
            'pharmacy-customers.view', 'pharmacy-payments.view', 'pharmacy-payments.create',
            'pharmacy-reports.view',
        ];
        $allowed = array_map(fn (string $permission): bool => $plan->allowsRoutePermission($permission)
            && ($this->access->isOwner($actor) || $actor->can($permission)), $permissions);

        if ($creating ? in_array(false, $allowed, true) : ! in_array(true, $allowed, true)) {
            throw new AuthorizationException('Your account or plan does not include this customer action.');
        }
    }

    private function customerOption(Patient $patient): array
    {
        return $patient->only(['id', 'patient_id', 'name', 'mobile_no', 'type'])
            + ['text' => $patient->patient_id.' — '.$patient->name.' — '.$patient->mobile_no];
    }

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
