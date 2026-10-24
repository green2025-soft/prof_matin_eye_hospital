<?php

namespace Modules\Core\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use LogicException;
use Modules\Core\Contracts\TenantAccounts;
use Modules\Core\Models\Account;
use Modules\Core\Models\Tenant;
use Modules\Core\Validation\TenantRules;
use Throwable;

class TenantProvisioner
{
    public function __construct(private DatabaseInstaller $databases, private TenantAccounts $accounts) {}

    public function save(Account $actor, ?Model $record, #[\SensitiveParameter] array $values): Model
    {
        if ($record !== null && ! $record instanceof Tenant) {
            throw new AuthorizationException;
        }

        if ($record?->exists) {
            Gate::forUser($actor)->authorize('update', $record);

            return $this->update($record, $values['name'] ?? null, $values['slug'] ?? null, $values['domains'] ?? null, $values['status'] ?? null);
        }

        Gate::forUser($actor)->authorize('create', Tenant::class);

        return $this->create($values['name'], $values['slug'], $values['domains'], $values['owner_name'], $values['owner_email'], $values['password']);
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
        ];
    }

    public function create(string $name, string $slug, array $domains, string $ownerName, string $email, #[\SensitiveParameter] string $password): Tenant
    {
        $this->validateOwner($ownerName, $email, $password);

        $database = config('multitenancy.database_prefix').str_replace('-', '', (string) Str::uuid());
        $tenant = $this->register($name, $slug, $domains, $database, $email);

        return $this->retry($tenant, $ownerName, $password);
    }

    public function adopt(string $name, string $slug, array $domains, string $database, string $email): Tenant
    {
        $tenant = $this->register($name, $slug, $domains, $database, $email);

        return $this->retry($tenant, adopt: true);
    }

    public function retry(Tenant $tenant, string $ownerName = '', #[\SensitiveParameter] ?string $password = null, bool $adopt = false): Tenant
    {
        $this->ensureCentral();

        if (! $adopt) {
            $this->validateOwner($ownerName, $tenant->owner_email, $password);
        }

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

    public function update(Tenant $tenant, ?string $name = null, ?string $slug = null, ?array $domains = null, ?string $status = null): Tenant
    {
        $this->ensureCentral();

        try {
            return $this->databases->locked($tenant->database, function () use ($tenant, $name, $slug, $domains, $status): Tenant {
                return $tenant->getConnection()->transaction(function () use ($tenant, $name, $slug, $domains, $status): Tenant {
                    $current = Tenant::query()->whereKey($tenant->getKey())->lockForUpdate()->firstOrFail();
                    $data = TenantRules::normalize([
                        'name' => $name ?? $current->name,
                        'slug' => $slug ?? $current->slug,
                        'domains' => $domains ?? $current->domains()->pluck('domain')->all(),
                    ]);

                    if ($status !== null) {
                        $data['status'] = $status;
                    }

                    $rules = TenantRules::metadata($current);
                    $rules['status'] = ['sometimes', 'string', Rule::in(array_keys($current->statusOptions()))];
                    $values = Validator::make($data, $rules)->validate();

                    $current->fill(['name' => $values['name'], 'slug' => $values['slug']]);
                    $current->status = $values['status'] ?? $current->status;
                    $current->save();

                    $existing = $current->domains()->pluck('domain')->all();
                    $current->domains()->whereNotIn('domain', $values['domains'])->delete();

                    foreach (array_diff($values['domains'], $existing) as $domain) {
                        $current->domains()->create(['domain' => $domain]);
                    }

                    return $current->load('domains:id,tenant_id,domain');
                });
            });
        } catch (UniqueConstraintViolationException) {
            throw $this->duplicateIdentifier();
        }
    }

    private function validateOwner(string $ownerName, string $email, #[\SensitiveParameter] ?string $password): void
    {
        $data = TenantRules::normalize(['owner_name' => $ownerName, 'owner_email' => $email]);
        $data['password'] = $password;

        Validator::make($data, TenantRules::owner())->validate();
    }

    private function register(string $name, string $slug, array $domains, string $database, string $email): Tenant
    {
        $this->ensureCentral();
        $data = TenantRules::normalize(compact('name', 'slug', 'domains', 'database') + ['owner_email' => $email]);
        $rules = TenantRules::metadata() + [
            'database' => TenantRules::database(),
            'owner_email' => TenantRules::owner()['owner_email'],
        ];
        $values = Validator::make($data, $rules)->validate();

        try {
            return (new Tenant)->getConnection()->transaction(function () use ($values): Tenant {
                $tenant = new Tenant(['name' => $values['name'], 'slug' => $values['slug']]);
                $tenant->database = $values['database'];
                $tenant->database_token = (string) Str::uuid();
                $tenant->owner_email = $values['owner_email'];
                $tenant->save();

                foreach ($values['domains'] as $domain) {
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
