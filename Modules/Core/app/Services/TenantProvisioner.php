<?php

namespace Modules\Core\Services;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use LogicException;
use Modules\Core\Contracts\TenantAccounts;
use Modules\Core\Models\Account;
use Modules\Core\Models\CheckoutAttempt;
use Modules\Core\Models\Invoice;
use Modules\Core\Models\InvoiceItem;
use Modules\Core\Models\Payment;
use Modules\Core\Models\SmsAccount;
use Modules\Core\Models\SmsMessage;
use Modules\Core\Models\SmsRecharge;
use Modules\Core\Models\Subscription;
use Modules\Core\Models\Tenant;
use PDOException;
use RuntimeException;
use Throwable;

class TenantProvisioner
{
    public function __construct(private DatabaseInstaller $databases, private TenantAccounts $accounts, private Kernel $console) {}

    public function delete(Account $actor, Model $record): void
    {
        $this->ensureCentral();
        Gate::forUser($actor)->authorize('delete', $record);

        $this->databases->locked($record->database, function () use ($actor, $record): void {
            $databaseVerified = $record->execute(fn () => $this->databases->verifyTenantDeletion($record->database, $record->database_token));
            $tenant = $actor->withAccessLock(function (Account $freshActor) use ($record): Tenant {
                $tenant = Tenant::whereKey($record->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();
                Gate::forUser($freshActor)->authorize('delete', $tenant);
                $connection = $tenant->getConnection();
                $invoices = Invoice::where('tenant_id', $tenant->getKey())
                    ->select('id');
                $pending = CheckoutAttempt::whereIn('invoice_id', $invoices)
                    ->whereNotNull('active_invoice_id')
                    ->where('provider', '!=', 'manual')
                    ->lockForUpdate()
                    ->first(['id']);

                if ($pending !== null) {
                    throw ValidationException::withMessages(['tenant' => 'Resolve pending online payments before deleting this tenant.']);
                }

                $pendingSms = SmsMessage::where('tenant_id', $tenant->getKey())
                    ->whereIn('status', ['sending', 'uncertain'])
                    ->lockForUpdate()
                    ->first(['id']);
                if ($pendingSms !== null) {
                    throw ValidationException::withMessages(['tenant' => 'Resolve in-progress SMS submissions before deleting this tenant.']);
                }

                $tenant->status = Tenant::STATUS_DELETING;
                $tenant->save();
                Subscription::where('tenant_id', $tenant->getKey())
                    ->update(['status' => Subscription::STATUS_CANCELLED]);
                app(ContextCache::class)->flush($connection->getName(), 'subscriptions');

                return $tenant;
            });

            // MySQL DROP cannot roll back. Keep the registry until every cleanup succeeds.
            if ($databaseVerified) {
                $tenant->execute(fn () => $this->databases->dropOwnedTenant($tenant->database, $tenant->database_token));
            }
            $this->deleteFiles($tenant);

            $tenant->getConnection()->transaction(function () use ($tenant): void {
                $current = Tenant::whereKey($tenant->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();
                $connection = $current->getConnection();
                $invoices = Invoice::where('tenant_id', $current->getKey())
                    ->select('id');
                CheckoutAttempt::whereIn('invoice_id', $invoices)->delete();
                Payment::where('tenant_id', $current->getKey())->delete();
                InvoiceItem::whereIn('invoice_id', $invoices)->delete();
                Invoice::where('tenant_id', $current->getKey())->delete();
                Subscription::where('tenant_id', $current->getKey())->delete();
                SmsRecharge::where('tenant_id', $current->getKey())->delete();
                SmsMessage::where('tenant_id', $current->getKey())->delete();
                SmsAccount::where('tenant_id', $current->getKey())->delete();
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
                $values['credentials'],
                $details,
            );
        } catch (Throwable $exception) {
            if ($newLogo !== null && ! Tenant::where('logo_path', $newLogo)->exists()) {
                $disk->delete($newLogo);
            }

            throw $exception;
        }
    }

    public function formData(Account $actor, Model $record): array
    {
        Gate::forUser($actor)->authorize($record->exists ? 'update' : 'create', $record->exists ? $record : Tenant::class);

        $credentials = [
            'database' => '',
            'username' => '',
            'password' => '',
        ];

        if ($record->exists) {
            $username = $record->database_username;
            $credentials = [
                'database' => $record->database,
                'username' => $username ?? config('database.connections.tenant.username'),
                'password' => $username === null ? config('database.connections.tenant.password') : ($record->database_password ?? ''),
            ];
        }

        return [
            'tenant' => $record,
            'domains' => $record->exists ? ($record->domains->pluck('domain')->all() ?: ['']) : [''],
            'statusOptions' => $record->statusOptions(),
            'tenantBaseDomain' => config('multitenancy.tenant_base_domain'),
            'logoUrl' => $record->logo_url,
            'passwordMinLength' => config('auth.password_min_length', 5),
            'databaseCredentials' => $credentials,
        ];
    }

    public function create(
        string $name,
        string $slug,
        array $domains,
        string $ownerName,
        string $email,
        #[\SensitiveParameter] string $password,
        #[\SensitiveParameter] array $credentials,
        array $details = [],
    ): Tenant {
        $this->ensureCentral();
        $this->databases->verifyEmpty($credentials);
        $tenant = $this->register($name, $slug, $domains, $credentials, $email, $details);

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

    public function adopt(string $name, string $slug, array $domains, #[\SensitiveParameter] array $credentials, string $email): Tenant
    {
        $this->ensureCentral();
        $this->databases->verifyConnection($credentials);
        $tenant = $this->register($name, $slug, $domains, $credentials, $email);

        return $this->retry($tenant, adopt: true);
    }

    public function retry(Tenant $tenant, string $ownerName = '', #[\SensitiveParameter] ?string $password = null, bool $adopt = false): Tenant
    {
        $this->ensureCentral();

        return $this->prepare($tenant, function () use ($tenant, $ownerName, $password, $adopt): void {
            $connection = DB::connection('tenant');
            $username = $tenant->database_username;

            if ($username !== null && (
                $connection->getDatabaseName() !== $tenant->database
                || $connection->getConfig('username') !== $username
                || (string) $connection->getConfig('password') !== ($tenant->database_password ?? '')
            )) {
                throw ValidationException::withMessages([
                    'credentials.database' => 'The saved tenant database credentials were not applied. '
                        .'Update the server tenant connection configuration, clear cached configuration and retry setup.',
                ]);
            }

            if ($adopt && ! $this->accounts->ownerExists($tenant->owner_email)) {
                throw ValidationException::withMessages(['email' => 'The existing tenant owner was not found.']);
            }

            $this->databases->claim('tenant', $tenant->database_token, allowExisting: $adopt);
            $this->databases->migrate('tenant');

            // Start after migrations: account, settings and seed data roll back together.
            DB::connection('tenant')->transaction(function () use ($tenant, $ownerName, $password, $adopt): void {
                if ($adopt) {
                    $this->accounts->adoptOwner($tenant->owner_email);
                    $this->databases->seedTenantDefaults();
                } else {
                    $this->accounts->createOwner($ownerName, $tenant->owner_email, $password);

                    // Seed fresh tenant databases only; adoption preserves existing business data.
                    $status = $this->console->call('db:seed', ['--database' => 'tenant', '--force' => true]);

                    if ($status !== 0) {
                        throw new RuntimeException('Tenant catalog initialization failed. Retry tenant setup.');
                    }
                }

                $this->accounts->ensureDefaultBranch($tenant->name, $tenant->address ?? '');
            });
        });
    }

    public function update(Tenant $tenant, ?string $name = null, ?string $slug = null, ?array $domains = null, ?string $status = null, array $details = []): Tenant
    {
        $this->ensureCentral();

        try {
            return $this->databases->locked($tenant->database, function () use ($tenant, $name, $slug, $domains, $status, $details): Tenant {
                return $tenant->getConnection()->transaction(function () use ($tenant, $name, $slug, $domains, $status, $details): Tenant {
                    $current = Tenant::whereKey($tenant->getKey())
                        ->lockForUpdate()
                        ->firstOrFail();
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

                    if ($current->isReady()) {
                        $current->execute(fn () => $this->accounts->ensureDefaultBranch($current->name, $current->address ?? ''));
                    }

                    return $current->load('domains:id,tenant_id,domain');
                });
            });
        } catch (UniqueConstraintViolationException) {
            throw $this->duplicateIdentifier();
        }
    }

    private function register(string $name, string $slug, array $domains, #[\SensitiveParameter] array $credentials, string $email, array $details = []): Tenant
    {
        try {
            return (new Tenant)->getConnection()->transaction(function () use ($name, $slug, $domains, $credentials, $email, $details): Tenant {
                $tenant = new Tenant(['name' => $name, 'slug' => $slug] + Arr::only($details, ['address', 'phone', 'has_branches']));
                $tenant->logo_path = $details['logo_path'] ?? null;
                $tenant->database = $credentials['database'];
                $tenant->database_username = $credentials['username'];
                $tenant->database_password = (string) ($credentials['password'] ?? '');
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
        return ValidationException::withMessages(['tenant' => 'The tenant slug, database name or one of its domains is already registered.']);
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

                $tenant->getConnection()->transaction(function () use ($tenant): void {
                    app(SubscriptionService::class)->enrollDefault($tenant);
                    $tenant->status = Tenant::STATUS_ACTIVE;
                    $tenant->provisioning_error = null;
                    $tenant->save();
                });
            } catch (Throwable $exception) {
                $databaseError = $exception instanceof PDOException ? (int) ($exception->errorInfo[1] ?? 0) : 0;
                $databaseAccessFailed = in_array($databaseError, [1044, 1045, 1049, 1142, 1227], true);
                $databaseMessage = 'Cannot initialize database '.$tenant->database.' (MySQL error '.$databaseError.'). '
                    .'Check DB_DATABASE, DB_USERNAME and DB_PASSWORD, '
                    .'and grant that user full access to this exact database in the hosting control panel before retrying setup.';

                $tenant->status = Tenant::STATUS_FAILED;
                $tenant->provisioning_error = $databaseAccessFailed
                    ? $databaseMessage
                    : 'Setup failed. Review the command error before retrying.';
                $tenant->save();

                if ($databaseAccessFailed) {
                    throw ValidationException::withMessages([
                        'credentials.database' => $databaseMessage,
                    ]);
                }

                throw $exception;
            }
        });

        return $tenant;
    }
}
