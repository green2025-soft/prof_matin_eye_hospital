<?php

namespace Modules\Core\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use LogicException;
use Modules\Core\Contracts\TenantAccounts;
use Modules\Core\Models\Account;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use RuntimeException;
use Throwable;

class TenantProvisioner
{
    public function __construct(private DatabaseInstaller $databases, private TenantAccounts $accounts, private AccessManager $access) {}

    public function delete(Account $actor, Model $record): void
    {
        $this->ensureCentral();
        if (! $record instanceof Tenant) {
            throw new AuthorizationException;
        }
        Gate::forUser($actor)->authorize('delete', $record);

        $this->databases->locked($record->database, function () use ($actor, $record): void {
            $record->execute(fn () => $this->databases->verifyTenantDeletion($record->database, $record->database_token));
            $tenant = $this->access->transaction($actor, function (Account $freshActor) use ($record): Tenant {
                $tenant = Tenant::query()->whereKey($record->getKey())->lockForUpdate()->firstOrFail();
                Gate::forUser($freshActor)->authorize('delete', $tenant);
                $connection = $tenant->getConnection();
                $invoices = $connection->table('invoices')->where('tenant_id', $tenant->getKey())->select('id');
                $pending = $connection->table('checkout_attempts')->whereIn('invoice_id', $invoices)
                    ->whereNotNull('active_invoice_id')->where('provider', '!=', 'manual')->lockForUpdate()->first(['id']);

                if ($pending !== null) {
                    throw ValidationException::withMessages(['tenant' => 'Resolve pending online payments before deleting this tenant.']);
                }

                $tenant->status = Tenant::STATUS_DELETING;
                $tenant->save();
                Subscription::query()->where('tenant_id', $tenant->getKey())->update(['status' => Subscription::STATUS_CANCELLED]);
                app(ContextCache::class)->flush($connection->getName(), 'subscriptions');

                return $tenant;
            });

            // MySQL DROP cannot roll back. Keep the registry until every cleanup succeeds.
            $tenant->execute(fn () => $this->databases->dropOwnedTenant($tenant->database, $tenant->database_token));
            $this->deleteFiles($tenant);

            $tenant->getConnection()->transaction(function () use ($tenant): void {
                $current = Tenant::query()->whereKey($tenant->getKey())->lockForUpdate()->firstOrFail();
                $connection = $current->getConnection();
                $invoices = $connection->table('invoices')->where('tenant_id', $current->getKey())->select('id');
                $connection->table('checkout_attempts')->whereIn('invoice_id', $invoices)->delete();
                $connection->table('payments')->where('tenant_id', $current->getKey())->delete();
                $connection->table('invoice_items')->whereIn('invoice_id', $invoices)->delete();
                $connection->table('invoices')->where('tenant_id', $current->getKey())->delete();
                $connection->table('subscriptions')->where('tenant_id', $current->getKey())->delete();
                app(ContextCache::class)->flush($connection->getName(), 'subscriptions');
                $current->domains()->delete();
                $current->delete();
            }, 3);
        });
    }

    private function deleteFiles(Tenant $tenant): void
    {
        $scope = 'tenant_'.(int) $tenant->getKey();
        foreach (['local', 'public'] as $name) {
            if (! Storage::disk($name)->deleteDirectory($scope)) {
                throw new RuntimeException('Tenant uploads could not be removed. Retry tenant deletion.');
            }
        }

        if ($tenant->logo_path !== null && ! Storage::disk('public')->delete($tenant->logo_path)) {
            throw new RuntimeException('The tenant logo could not be removed. Retry tenant deletion.');
        }

        $cacheRoot = realpath(config('cache.stores.file.path'));
        $cache = $cacheRoot === false ? false : realpath($cacheRoot.DIRECTORY_SEPARATOR.$scope);
        if ($cache !== false) {
            if (! str_starts_with($cache, $cacheRoot.DIRECTORY_SEPARATOR) || ! File::deleteDirectory($cache)) {
                throw new RuntimeException('Tenant cache files could not be safely removed. Retry tenant deletion.');
            }
        }
    }

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
                    if ($current->status === Tenant::STATUS_DELETING) {
                        throw new LogicException('Finish deleting this tenant before changing its settings.');
                    }
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

            if ($tenant->isReady() || $tenant->status === Tenant::STATUS_DELETING) {
                throw new LogicException('A ready or deleting tenant cannot be provisioned again.');
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
