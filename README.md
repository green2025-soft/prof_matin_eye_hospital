# Laravel Modular Boilerplate

Reusable Laravel 13 foundation with exactly two modules: **Core** for platform/shared infrastructure and **TenantCore** for tenant accounts. It uses Blade, Bootstrap 5, jQuery, locally bundled Font Awesome Free 7.3.1, and Vite. Spatie multitenancy provides separate tenant databases and native context switching; platform and tenant accounts share session authentication with isolated identities. Explicit setup/adoption, all-tenant migrations, tenant management, account management, and separate role/permission management are available. Project-specific business workflows remain unspecified.

## Requirements

- PHP 8.4.1 or later within PHP 8, with the extensions required by Composer.
- Composer 2 and Node.js compatible with Vite 8: Node 20.19.x or Node 22.12 and later.
- MySQL when application database connections and module migrations are configured.

## Local setup

From the project root:

```powershell
composer install
npm ci
```

Only for a fresh checkout without an existing `.env`:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Preserve an existing `.env` and application key. Configure MySQL credentials, `DB_CONNECTION=central`, `CENTRAL_DB_DATABASE`, and `CENTRAL_DOMAINS` in `.env`. The default platform host is `platform.localhost`. The central database must differ from any tenant database. Optional `DB_PROVISION_USERNAME`/`DB_PROVISION_PASSWORD` supply database-creation credentials; otherwise the MySQL credentials are used. Setup commands need permission to create databases and apply schema changes.

Create the platform and its separate administrator, then the first tenant with its own database:

```powershell
php artisan platform:install
php artisan tenant:create
```

Commands prompt for account details and a hidden password (minimum 12 characters). For the local tenant, supply `localhost,127.0.0.1` as its domains. To attach an existing tenant database/account, use `php artisan tenant:adopt` instead; its password is preserved. Resume an unfinished setup with `php artisan tenant:retry SLUG` (add `--adopt` for an adoption). No fixed password is seeded. File sessions/cache and synchronous queues remain the development defaults.

```powershell
npm run build
php artisan serve
```

Open `http://platform.localhost:8000/auth/login` for the platform, or `http://127.0.0.1:8000/auth/login` for the registered local tenant. Each uses its own administrator. Unknown/inactive tenant hosts return 404. The root page requires authentication. Login POST uses `throttle:login`, allowing five submissions per scope/email/IP per minute. Build assets again after CSS/JavaScript changes, or use `composer dev` for Laravel and Vite together. Serve only `public/` with Laragon/another web server; production uses built assets and a configured web server.

Authorized platform accounts manage tenants at `/tenants` and platform accounts at `/admins`; tenant accounts are managed at `/users` on the tenant host. `/roles` manages role names only. The separate `/permissions` page selects a role and manages its grouped grants in the current database; permission viewing, grant management, and assigning roles to accounts have distinct policy abilities. Policies protect the reserved owner role and prevent granting access the actor does not hold. Tenant management has no database-delete action.

Lists use numbered pagination with 10 rows by default. Allowed page sizes are 5, 10, 15, 20, 30, 50, and 100; filters and sorting persist when changing pages.

## Tenant table and column changes

Create a new migration for each schema change; keep already-applied files unchanged:

```powershell
php artisan make:migration add_phone_to_users_table --table=users --path=Modules/TenantCore/database/migrations
```

Define the column in that migration, then run:

```powershell
php artisan tenants:migrate
```

This updates **every registered tenant database**, including suspended tenants. Each database tracks its own applied migrations; reruns skip completed files, and newly created tenants receive the latest schema. Shared account/permission changes belong in `Modules/Core/database/migrations/shared`; tenant-only changes belong in TenantCore. Central registry changes belong in Core's `database/migrations/central`.

Use `php artisan tenants:migrate --pretend` to preview SQL or `--tenant=ID_OR_SLUG` to select tenants (repeat for multiple). Digit-only selectors mean IDs. Production requires `--force`. Failed tenants are listed, other tenants continue and the command returns a nonzero exit code; fix the cause and rerun. There is no automatic rollback across databases. Apply populated-table changes in safe stages; never use `migrate:fresh` on real tenants. Schema work runs explicitly under a per-database lock after ownership verification, never during provider boot or an ordinary page request.

For production, configure the environment, run `composer install --no-dev --optimize-autoloader`, build assets, then run `composer optimize` for config/event/route/view caches. `composer optimize-clear` clears generated caches while preserving application-cache entries; keep ordinary development uncached. Providers and the shared asset pipeline stay lean; performance claims require repeated measurements through the HTTP runtime.

## Checks

```powershell
composer lint
npm run build
```

Use `composer format` to apply PHP formatting. Verify browser and database behavior manually as features are added; automated test scaffolding is outside the current project scope.

## Architecture and AI tools

[AGENTS.md](./AGENTS.md) and [ARCHITECTURE.md](./ARCHITECTURE.md) define module ownership, database/session boundaries, and the Hrm-style flow adapted to Blade: resource controller → `BaseController` → model; resource request → `BaseRequest`. Models declare safe query/display fields through `HasCrudContract`; `HandlesModelQueries` provides shared search/filter/eager-load/pagination behavior directly on the model. Thin controllers declare their model and metadata and delegate their typed `store()` to `saveData()`. `BaseRequest` and `ListRequest` share authorization and validation behavior. One Blade form serves create/edit, and native `Route::resource()` updates map to `store()` through Core's registrar. Form fields are direct HTML/Blade; shared markup uses ordinary partials. Helpers contain only value/selection/checking, validation-class, and normalization logic.

`nwidart/laravel-modules`, its Composer merge integration, `spatie/laravel-permission`, and `spatie/laravel-multitenancy` are installed. Tenant-aware jobs enforce tenant lifecycle eligibility; central jobs implement Spatie `NotTenantAware`. Authorize HTTP actions before temporarily switching tenant context. Keep protected resources under the common authenticated route group, with explicit central/tenant restrictions and per-action policies.

Inspect the existing modules with `php artisan module:list`. Module creation uses native `php artisan module:make ModuleName` with an explicitly approved name; `php artisan module:make` alone is incomplete. The command creates a lean skeleton, not a complete CRUD feature. Core and TenantCore already exist: do not regenerate or `--force` them, and obtain an explicit scope change before adding another module.

Laravel Boost, Pint, Pail, Collision, and Tinker are retained development tools. Portable Boost MCP configuration lives in [.codex/config.toml](./.codex/config.toml); project-local skills include [Laravel fullstack guidance](./.agents/skills/laravel-fullstack-development/SKILL.md), [Laravel best practices](./.agents/skills/laravel-best-practices/SKILL.md), and [Infer conventions](./.agents/skills/infer-conventions/SKILL.md). Run Infer conventions only when explicitly requested. Keep these files when cloning or moving the project. Project instructions take precedence over generic skill examples; Boost-generated guidelines and testing/Tailwind skills are disabled.

The admin UI uses a shared responsive sidebar, account menu, dashboard, and compact CRUD forms/tables. Debugbar is available for development with `DEBUGBAR_ENABLED=true`; it is disabled by default so its toolbar does not cover the admin interface.
