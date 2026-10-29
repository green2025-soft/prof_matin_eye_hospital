<?php

namespace Modules\Hospital\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Core\Models\Account;
use Modules\Core\Models\Setting;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use Modules\Core\Services\AccessManager;
use Modules\Core\Services\PermissionCatalog;
use Modules\Hospital\Models\Doctor;
use RuntimeException;
use SensitiveParameter;
use Spatie\Permission\Models\Permission;

class DoctorService
{
    public function __construct(private AccessManager $access, private PermissionCatalog $catalog) {}

    public function permissionPageData(Account $actor): array
    {
        $this->access->assertActorContext($actor);
        $gate = Gate::forUser($actor);
        $gate->authorize('viewPermissions', Doctor::class);
        $this->authorizePlan($actor, 'doctor-permissions.view');
        $actorPermissions = $this->access->permissionNames($actor);

        $permissions = Permission::on($actor->getConnectionName())->where('guard_name', 'web')
            ->whereIn('name', $actorPermissions)->orderBy('name')->limit(501)->get(['id', 'name', 'guard_name']);
        if ($permissions->count() > 500) {
            throw ValidationException::withMessages(['permissions' => 'There are too many available permissions for this page. Contact the application administrator.']);
        }

        return [
            'pageTitle' => 'Doctor permissions',
            'areas' => $this->catalog->areas($permissions->pluck('name', 'id')->all()),
            'selectedPermissions' => (new Doctor)->getAllPermissions()->pluck('id')->all(),
            'permissionCount' => $permissions->count(),
            'canManagePermissions' => $gate->allows('managePermissions', Doctor::class),
        ];
    }

    public function save(Account $actor, ?Model $record, #[SensitiveParameter] array $values, int $branchId): Doctor
    {
        if (($record !== null && ! $record instanceof Doctor) || $branchId < 1) {
            throw new AuthorizationException;
        }

        $action = $record === null ? 'create' : 'update';
        $permission = 'doctors.'.$action;
        Gate::forUser($actor)->authorize($action, $record ?? Doctor::class);
        $this->authorizePlan($actor, $permission);

        try {
            return $this->access->transaction($actor, function (Account $freshActor) use ($record, $values, $branchId, $action, $permission): Doctor {
                $this->authorizePlan($freshActor, $permission);
                if ($record === null) {
                    $doctor = new Doctor;
                    $doctor->branch_id = $branchId;
                } else {
                    $doctor = Doctor::query()->where('branch_id', $branchId)->whereKey($record->getKey())->lockForUpdate()->firstOrFail();
                }
                Gate::forUser($freshActor)->authorize($action, $doctor);

                if (! $doctor->exists) {
                    $doctor->doctor_id = $this->nextDoctorId($doctor);
                }

                $doctor->fill(Arr::only($values, $doctor->getFillable()));
                if (array_key_exists('email', $values)) {
                    $doctor->email = $values['email'];
                }
                if (filled($values['password'] ?? null)) {
                    $doctor->password = $values['password'];
                    $doctor->remember_token = Str::random(60);
                }

                if (isset($values['picture'])) {
                    $this->storePicture($doctor, $values['picture']);
                }

                try {
                    $doctor->save();
                } catch (UniqueConstraintViolationException $exception) {
                    $constraint = $exception->errorInfo[2] ?? '';
                    if (str_contains($constraint, 'doctors_doctor_id_unique')) {
                        throw ValidationException::withMessages(['record' => 'The generated Doctor ID already exists. Choose a distinct Doctor ID pattern in Hospital settings.']);
                    }
                    if (str_contains($constraint, 'doctors_email_unique')) {
                        throw ValidationException::withMessages(['email' => 'This email address is already in use by a doctor.']);
                    }

                    throw $exception;
                }

                return $doctor;
            });
        } catch (QueryException $exception) {
            if ((int) ($exception->errorInfo[1] ?? 0) === 1452) {
                throw ValidationException::withMessages(['department_id' => 'The selected department or branch is no longer available.']);
            }
            throw $exception;
        }
    }

    public function savePermissions(Account $actor, array $permissionIds): void
    {
        Gate::forUser($actor)->authorize('managePermissions', Doctor::class);
        $this->authorizePlan($actor, 'doctor-permissions.manage');

        $this->access->transaction($actor, function (Account $freshActor) use ($permissionIds): void {
            $this->authorizePlan($freshActor, 'doctor-permissions.manage');
            Gate::forUser($freshActor)->authorize('managePermissions', Doctor::class);

            $permissions = Permission::on($freshActor->getConnectionName())->where('guard_name', 'web')
                ->whereIn('id', $permissionIds)->get(['id', 'name', 'guard_name']);

            if ($permissions->count() !== count($permissionIds)) {
                throw ValidationException::withMessages(['permissions' => 'Select permissions from the current application.']);
            }
            if (! $this->access->permissionsWithinActor($freshActor, $permissions)) {
                throw ValidationException::withMessages(['permissions' => 'You may grant only permissions you hold yourself.']);
            }

            $connection = $freshActor->getConnection();
            $currentIds = $connection->table('doctor_permissions')->pluck('permission_id');
            $selectedIds = $permissions->modelKeys();
            $removedIds = $currentIds->diff($selectedIds);
            $addedIds = collect($selectedIds)->diff($currentIds);
            if ($removedIds->isNotEmpty()) {
                $connection->table('doctor_permissions')->whereIn('permission_id', $removedIds)->delete();
            }
            if ($addedIds->isNotEmpty()) {
                $connection->table('doctor_permissions')->insert($addedIds->map(fn ($id): array => ['permission_id' => $id])->all());
            }
            Doctor::forgetSharedPermissions();
        });
    }

    public function delete(Account $actor, Model $record): void
    {
        if (! $record instanceof Doctor) {
            throw new AuthorizationException;
        }
        Gate::forUser($actor)->authorize('delete', $record);
        $this->authorizePlan($actor, 'doctors.delete');

        $this->access->transaction($actor, function (Account $freshActor) use ($record): void {
            $this->authorizePlan($freshActor, 'doctors.delete');
            $doctor = Doctor::query()->where('branch_id', $record->branch_id)->whereKey($record->getKey())->lockForUpdate()->firstOrFail();
            Gate::forUser($freshActor)->authorize('delete', $doctor);
            $doctor->getConnection()->table('doctor_password_reset_tokens')->where('email', $doctor->email)->delete();
            $doctor->delete();
            $picturePath = $doctor->picture_path;
            if ($picturePath) {
                $disk = Storage::disk('public');
                $doctor->getConnection()->afterCommit(fn () => $disk->delete($picturePath));
            }
        });
    }

    private function nextDoctorId(Doctor $doctor): string
    {
        $settings = Setting::values('hospital', $doctor->getConnectionName());
        $branchNumbering = $settings['doctor_id_scope'] === 'branch';
        $scope = $branchNumbering ? 'branch:'.$doctor->branch_id : 'tenant';
        $sequences = $doctor->getConnection()->table('doctor_sequences');
        $sequences->insertOrIgnore(['scope' => $scope, 'last_number' => 0]);
        $sequences->where('scope', $scope);
        $sequence = $sequences->lockForUpdate()->first();
        $number = (int) $sequence->last_number + 1;
        if ($number > 999999999999) {
            throw ValidationException::withMessages(['record' => 'The Doctor ID numbering sequence is exhausted.']);
        }
        $sequences->update(['last_number' => $number]);

        $pattern = $settings[$branchNumbering ? 'doctor_branch_id_pattern' : 'doctor_id_pattern'];
        $date = Carbon::now(config('app.timezone'));
        $formatted = strtr($pattern, [
            '{BRANCH}' => (string) $doctor->branch_id,
            '{YYYY}' => $date->format('Y'), '{MM}' => $date->format('m'), '{DD}' => $date->format('d'),
        ]);
        $formatted = preg_replace_callback('/\{SEQ(?::([1-9]|1[0-2]))?\}/', fn (array $match): string => str_pad((string) $number, (int) ($match[1] ?? 6), '0', STR_PAD_LEFT), $formatted);
        if (mb_strlen($formatted) > 40) {
            throw ValidationException::withMessages(['record' => 'The generated Doctor ID is too long. Shorten its pattern in Hospital settings.']);
        }

        return $formatted;
    }

    private function storePicture(Doctor $doctor, UploadedFile $picture): void
    {
        $disk = Storage::disk('public');
        $picturePath = $disk->putFile('doctor-pictures', $picture);
        if ($picturePath === false) {
            throw new RuntimeException('The doctor picture could not be stored.');
        }

        $previousPicture = $doctor->picture_path;
        $doctor->getConnection()->afterRollBack(fn () => $disk->delete($picturePath));
        if ($previousPicture) {
            $doctor->getConnection()->afterCommit(fn () => $disk->delete($previousPicture));
        }

        $doctor->picture_path = $picturePath;
    }

    private function authorizePlan(Account $actor, string $permission): void
    {
        $this->access->assertActorContext($actor);
        $tenant = Tenant::current();
        $subscription = $tenant === null ? null : Subscription::forTenant($tenant);
        if (! $subscription?->isUsable()) {
            throw new AuthorizationException('Your account or plan does not include this action.');
        }

        $plan = $subscription->plan;
        if (! $plan->allowsRoutePermission('hospital.view') || ! $plan->allowsRoutePermission($permission)) {
            throw new AuthorizationException('Your account or plan does not include this action.');
        }
        if (! $this->access->isOwner($actor) && ! $actor->can('hospital.view')) {
            throw new AuthorizationException('Your account or plan does not include this action.');
        }
    }
}
