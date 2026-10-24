<?php

namespace Modules\Core\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use LogicException;
use Modules\Core\Contracts\TenantAccounts;
use Modules\Core\Models\Account;
use Modules\Core\Models\Tenant;
use Throwable;

class TenantProvisioner
{
    public function __construct(private DatabaseInstaller $databases, private TenantAccounts $accounts) {}

    public function save(Account $actor, ?Model $record, #[\SensitiveParameter] array $values): Model
    {
        if ($record !== null && ! $record instanceof Tenant) {
            throw new AuthorizationException;
        }

        $this->ensureCentral();
        Gate::forUser($actor)->authorize($record?->exists ? 'update' : 'create', $record?->exists ? $record : Tenant::class);
        $details = Arr::only($values, ['address', 'phone', 'has_branches']);
        $disk = Storage::disk('public');
        $newLogo = null;

        if (($values['logo'] ?? null) instanceof UploadedFile) {
            $newLogo = $values['logo']->store('tenant-logos', 'public');
            if (! is_string($newLogo)) {
                throw ValidationException::withMessages(['logo' => 'The logo could not be stored. Please try again.']);
            }
            $details['logo_path'] = $newLogo;
        }

        try {
            if ($record?->exists) {
                return $this->update(
                    $record,
                    $values['name'] ?? null,
                    $values['slug'] ?? null,
                    $values['domains'] ?? null,
                    $values['status'] ?? null,
                    $details,
                );
            }

            return $this->create(
                $values['name'],
                $values['slug'],
                $values['domains'],
                $values['owner_name'],
                $values['owner_email'],
                $values['password'],
                $details,
            );
        } catch (Throwable $exception) {
            if ($newLogo !== null && ! Tenant::query()->where('logo_path', $newLogo)->exists()) {
                $disk->delete($newLogo);
            }

            throw $exception;
        }
    }

    public function formData(Account $actor, Model $record): array
    {
        if (! $record instanceof Tenant) {
            throw new AuthorizationException;
        }

        Gate::forUser($actor)->authorize($record->exists ? 'update' : 'create', $record->exists ? $record : Tenant::class);

        return [
            'tenant' => $record,
            'domains' => $record->exists ? ($record->domains->pluck('domain')->all() ?: ['']) : [''],
            'statusOptions' => $record->statusOptions(),
            'tenantBaseDomain' => config('multitenancy.tenant_base_domain'),
            'logoUrl' => $record->logo_url,
            'passwordMinLength' => config('auth.password_min_length', 5),
        ];
    }

    public function create(string $name, string $slug, array $domains, string $ownerName, string $email, #[\SensitiveParameter] string $password, array $details = []): Tenant
    {
        $database = config('multitenancy.database_prefix').str_replace('-', '', (string) Str::uuid());
        $tenant = $this->register($name, $slug, $domains, $database, $email, $details);

        try {
            return $this->retry($tenant, $ownerName, $password);
        } catch (Throwable $exception) {
            if (isset($details['logo_path'])) {
                $tenant->logo_path = null;
                $tenant->save();
            }

            throw $exception;
        }
    }

    public function adopt(string $name, string $slug, array $domains, string $database, string $email): Tenant
    {
        $tenant = $this->register($name, $slug, $domains, $database, $email);

        return $this->retry($tenant, adopt: true);
    }

    public function retry(Tenant $tenant, string $ownerName = '', #[\SensitiveParameter] ?string $password = null, bool $adopt = false): Tenant
    {
        $this->ensureCentral();

        return $this->prepare($tenant, function () use ($tenant, $ownerName, $password, $adopt): void {
            if ($adopt && ! $this->accounts->ownerExists($tenant->owner_email)) {
                throw ValidationException::withMessages(['email' => 'The existing tenant owner was not found.']);
            }

            if (! $adopt) {
                $this->databases->create($tenant->database);
            }

            $this->databases->claim('tenant', $tenant->database_token, allowExisting: $adopt);
            $this->databases->migrate('tenant');

            if ($adopt) {
                $this->accounts->adoptOwner($tenant->owner_email);
            } else {
                $this->accounts->createOwner($ownerName, $tenant->owner_email, $password);
            }
        });
    }

    public function update(Tenant $tenant, ?string $name = null, ?string $slug = null, ?array $domains = null, ?string $status = null, array $details = []): Tenant
    {
        $this->ensureCentral();

        try {
            return $this->databases->locked($tenant->database, function () use ($tenant, $name, $slug, $domains, $status, $details): Tenant {
                return $tenant->getConnection()->transaction(function () use ($tenant, $name, $slug, $domains, $status, $details): Tenant {
                    $current = Tenant::query()->whereKey($tenant->getKey())->lockForUpdate()->firstOrFail();
                    if ($status !== null && ! array_key_exists($status, $current->statusOptions())) {
                        throw ValidationException::withMessages(['status' => 'Workspace access cannot change before setup completes.']);
                    }

                    $current->fill([
                        'name' => $name ?? $current->name,
                        'slug' => $slug ?? $current->slug,
                    ] + Arr::only($details, ['address', 'phone', 'has_branches']));
                    $current->status = $status ?? $current->status;

                    if (isset($details['logo_path'])) {
                        $previousLogo = $current->logo_path;
                        $current->logo_path = $details['logo_path'];

                        if ($previousLogo !== null) {
                            $disk = Storage::disk('public');
                            $current->getConnection()->afterCommit(fn () => $disk->delete($previousLogo));
                        }
                    }

                    $current->save();

                    $existing = $current->domains()->pluck('domain')->all();
                    $domains ??= $existing;
                    $current->domains()->whereNotIn('domain', $domains)->delete();

                    foreach (array_diff($domains, $existing) as $domain) {
                        $current->domains()->create(['domain' => $domain]);
                    }

                    return $current->load('domains:id,tenant_id,domain');
                });
            });
        } catch (UniqueConstraintViolationException) {
            throw $this->duplicateIdentifier();
        }
    }

    private function register(string $name, string $slug, array $domains, string $database, string $email, array $details = []): Tenant
    {
        $this->ensureCentral();

        try {
            return (new Tenant)->getConnection()->transaction(function () use ($name, $slug, $domains, $database, $email, $details): Tenant {
                $tenant = new Tenant(['name' => $name, 'slug' => $slug] + Arr::only($details, ['address', 'phone', 'has_branches']));
                $tenant->logo_path = $details['logo_path'] ?? null;
                $tenant->database = $database;
                $tenant->database_token = (string) Str::uuid();
                $tenant->owner_email = $email;
                $tenant->save();

                foreach ($domains as $domain) {
                    $tenant->domains()->create(['domain' => $domain]);
                }

                return $tenant;
            });
        } catch (UniqueConstraintViolationException) {
            throw $this->duplicateIdentifier();
        }
    }

    private function ensureCentral(): void
    {
        if (Tenant::checkCurrent()) {
            throw new LogicException('Tenant management requires the central application context.');
        }
    }

    private function duplicateIdentifier(): ValidationException
    {
        return ValidationException::withMessages(['tenant' => 'The tenant slug or one of its domains is already registered.']);
    }

    private function prepare(Tenant $tenant, callable $callback): Tenant
    {
        $this->databases->locked($tenant->database, function () use ($tenant, $callback): void {
            $tenant->refresh();

            if ($tenant->isReady()) {
                throw new LogicException('This tenant is already set up.');
            }

            $tenant->status = Tenant::STATUS_PROVISIONING;
            $tenant->save();

            try {
                $tenant->execute($callback);
                app(SubscriptionService::class)->enrollDefault($tenant);
                $tenant->status = Tenant::STATUS_ACTIVE;
                $tenant->provisioning_error = null;
                $tenant->save();
            } catch (Throwable $exception) {
                $tenant->status = Tenant::STATUS_FAILED;
                $tenant->provisioning_error = 'Setup failed. Review the command error before retrying.';
                $tenant->save();

                throw $exception;
            }
        });

        return $tenant;
    }
}
